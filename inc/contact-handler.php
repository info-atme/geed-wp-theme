<?php
/**
 * 体験・見学予約フォーム（/contact/）の送信処理
 *
 * - 第1希望は必須、第2・第3希望は任意（入れるなら日付と時間の両方）
 * - 日曜（ノースタッフ）・毎月15日（休館）・過去日は受け付けない
 * - エラー時は入力内容を一時保存して戻す（URLに個人情報は載せない）
 * - 運営宛 wp_mail が失敗したら完了にしない（入力を残してエラー表示）
 * - 同じフォームの二重送信は1回だけ処理する
 * - 受付成功時は ?sent=<トークン> で戻し、ページ側で trial_form_submit を送る
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const GEED_CODEX_CONTACT_TTL = 1800; // 入力保持・二重送信判定の保持秒数

/**
 * 体験・見学予約フォーム送信先メールアドレス
 */
function geed_codex_contact_to() {
	return apply_filters( 'geed_codex_contact_to', 'contact@gear.co.jp' );
}

/**
 * 時間の選択肢（07:00〜22:30 の30分刻み）
 */
function geed_codex_contact_time_slots() {
	$slots = array();
	for ( $h = 7; $h <= 22; $h++ ) {
		$slots[] = sprintf( '%02d:00', $h );
		$slots[] = sprintf( '%02d:30', $h );
	}
	return $slots;
}

function geed_codex_contact_visit_types() {
	return array( '無料体験見学', '入会相談', 'パーソナル相談', '法人契約相談', 'その他' );
}

/**
 * フォーム表示ごとの使い捨てトークン（二重送信の判定キー）
 */
function geed_codex_contact_new_token() {
	return wp_generate_password( 20, false, false );
}

function geed_codex_contact_clean_token( $token ) {
	return preg_replace( '/[^A-Za-z0-9]/', '', (string) $token );
}

/**
 * 希望日の検証。問題なければ空文字、あればエラーメッセージ
 */
function geed_codex_contact_date_error( $date ) {
	$tz = wp_timezone();
	$d  = DateTimeImmutable::createFromFormat( '!Y-m-d', $date, $tz );
	if ( ! $d || $d->format( 'Y-m-d' ) !== $date ) {
		return '日付の形式が正しくありません。';
	}
	$today = new DateTimeImmutable( 'today', $tz );
	if ( $d < $today ) {
		return '過去の日付は選べません。';
	}
	if ( '7' === $d->format( 'N' ) ) {
		return '日曜日はノースタッフ営業のためご予約を承れません。';
	}
	if ( '15' === $d->format( 'j' ) ) {
		return '毎月15日は休館日です。';
	}
	return '';
}

/**
 * 入力値の取得と検証
 *
 * @return array{0: array, 1: array} [値, エラー（フィールド名 => メッセージ）]
 */
function geed_codex_contact_validate( $src ) {
	$v = array(
		'name'         => sanitize_text_field( wp_unslash( $src['your-name'] ?? '' ) ),
		'furigana'     => sanitize_text_field( wp_unslash( $src['furigana'] ?? '' ) ),
		'email'        => sanitize_email( wp_unslash( $src['your-email'] ?? '' ) ),
		'phone'        => sanitize_text_field( wp_unslash( $src['phone'] ?? '' ) ),
		'visit_type'   => sanitize_text_field( wp_unslash( $src['visit-type'] ?? '' ) ),
		'participants' => max( 1, min( 10, absint( $src['participants'] ?? 1 ) ) ),
		'inquiry'      => sanitize_textarea_field( wp_unslash( $src['inquiry'] ?? '' ) ),
		'dates'        => array(),
		'times'        => array(),
	);
	for ( $i = 1; $i <= 3; $i++ ) {
		$v['dates'][ $i ] = sanitize_text_field( wp_unslash( $src[ 'date' . $i ] ?? '' ) );
		$v['times'][ $i ] = sanitize_text_field( wp_unslash( $src[ 'time' . $i ] ?? '' ) );
	}

	$e = array();
	if ( '' === $v['name'] ) {
		$e['your-name'] = 'お名前を入力してください。';
	}
	if ( '' === $v['furigana'] ) {
		$e['furigana'] = 'ふりがなを入力してください。';
	}
	if ( ! is_email( $v['email'] ) ) {
		$e['your-email'] = 'メールアドレスを正しく入力してください。';
	}
	if ( ! preg_match( '/^[0-9０-９\-－ー+()（） ]{10,}$/u', $v['phone'] ) ) {
		$e['phone'] = '電話番号を正しく入力してください。';
	}
	if ( ! in_array( $v['visit_type'], geed_codex_contact_visit_types(), true ) ) {
		$e['visit-type'] = 'ご希望の内容を選択してください。';
	}

	$slots = geed_codex_contact_time_slots();
	for ( $i = 1; $i <= 3; $i++ ) {
		$date = $v['dates'][ $i ];
		$time = $v['times'][ $i ];
		if ( '' === $date && '' === $time ) {
			if ( 1 === $i ) {
				$e['date1'] = '第1希望の日付と時間を選択してください。';
			}
			continue;
		}
		if ( '' === $date || '' === $time ) {
			$e[ 'date' . $i ] = '第' . $i . '希望は日付と時間の両方を選択してください。';
			continue;
		}
		if ( ! in_array( $time, $slots, true ) ) {
			$e[ 'date' . $i ] = '時間を選択肢から選んでください。';
			continue;
		}
		$msg = geed_codex_contact_date_error( $date );
		if ( $msg ) {
			$e[ 'date' . $i ] = $msg;
		}
	}

	return array( $v, $e );
}

/**
 * 戻り先URL（前回の ?sent / ?err を外す）
 */
function geed_codex_contact_back_url() {
	$url = wp_get_referer() ?: home_url( '/contact/' );
	return remove_query_arg( array( 'sent', 'err' ), $url );
}

/**
 * 入力を一時保存してフォームへ戻す
 */
function geed_codex_contact_fail( $type, $values, $errors = array() ) {
	$key = geed_codex_contact_new_token();
	set_transient( 'geed_cf_err_' . $key, array(
		'type'   => $type, // invalid | mail
		'values' => $values,
		'errors' => $errors,
	), GEED_CODEX_CONTACT_TTL );
	wp_safe_redirect( add_query_arg( 'err', $key, geed_codex_contact_back_url() ) . '#reservation' );
	exit;
}

/**
 * エラー時の保存内容を読む（ページ側から呼ぶ。1回読んだら消す）
 */
function geed_codex_contact_error_state() {
	$key = geed_codex_contact_clean_token( $_GET['err'] ?? '' );
	if ( '' === $key ) {
		return null;
	}
	$state = get_transient( 'geed_cf_err_' . $key );
	if ( ! is_array( $state ) ) {
		// 期限切れ・不正なキー。入力は戻せないが、エラーだったことだけ伝える
		return array( 'type' => 'expired', 'values' => array(), 'errors' => array() );
	}
	delete_transient( 'geed_cf_err_' . $key );
	return $state;
}

function geed_codex_contact_line( $i, $v ) {
	if ( '' === $v['dates'][ $i ] ) {
		return '（なし）';
	}
	return $v['dates'][ $i ] . ' ' . $v['times'][ $i ];
}

function geed_codex_handle_contact() {
	if ( ! isset( $_POST['geed_codex_contact_nonce'] ) ) {
		return;
	}
	if ( ! wp_verify_nonce( $_POST['geed_codex_contact_nonce'], 'geed_codex_contact' ) ) {
		// 長時間開いたままのページなど。入力は残して再送信してもらう
		list( $values ) = geed_codex_contact_validate( $_POST );
		geed_codex_contact_fail( 'expired', $values );
	}

	// honeypot（ボットには成功に見せる）
	if ( ! empty( $_POST['website'] ) ) {
		wp_safe_redirect( add_query_arg( 'sent', 'ok', geed_codex_contact_back_url() ) );
		exit;
	}

	// 二重送信: 同じトークンが処理済みなら、送らずに完了画面へ
	$token = geed_codex_contact_clean_token( $_POST['form_token'] ?? '' );
	if ( '' !== $token && get_transient( 'geed_cf_done_' . $token ) ) {
		wp_safe_redirect( add_query_arg( 'sent', $token, geed_codex_contact_back_url() ) );
		exit;
	}

	list( $v, $errors ) = geed_codex_contact_validate( $_POST );
	if ( $errors ) {
		geed_codex_contact_fail( 'invalid', $v, $errors );
	}

	$bar = str_repeat( '━', 32 );

	// === 運営宛メール ===
	$admin_to      = geed_codex_contact_to();
	$admin_subject = '【GEED】体験・見学予約のお申し込み: ' . $v['name'] . ' 様';
	$admin_body    = "新しい体験・見学予約の申し込みがありました。\n\n";
	$admin_body   .= $bar . "\n【申込者情報】\n" . $bar . "\n\n";
	$admin_body   .= "お名前: {$v['name']}\n";
	$admin_body   .= "ふりがな: {$v['furigana']}\n";
	$admin_body   .= "メールアドレス: {$v['email']}\n";
	$admin_body   .= "電話番号: {$v['phone']}\n\n";
	$admin_body   .= $bar . "\n【希望日時】\n" . $bar . "\n\n";
	for ( $i = 1; $i <= 3; $i++ ) {
		$admin_body .= "第{$i}希望: " . geed_codex_contact_line( $i, $v ) . "\n";
	}
	$admin_body   .= "\n" . $bar . "\n【詳細情報】\n" . $bar . "\n\n";
	$admin_body   .= "希望内容: {$v['visit_type']}\n";
	$admin_body   .= "参加人数: {$v['participants']}名\n\n";
	$admin_body   .= "ご希望・ご質問:\n" . ( $v['inquiry'] ?: '（なし）' ) . "\n\n";
	$admin_body   .= $bar . "\n\n";
	$admin_body   .= "※3営業日以内に対応してください。\n";
	$admin_body   .= "※スケジュール調整後、お客様にご連絡をお願いします。\n";

	$admin_headers = array(
		'Reply-To: ' . $v['name'] . ' <' . $v['email'] . '>',
		'Content-Type: text/plain; charset=UTF-8',
	);

	if ( ! wp_mail( $admin_to, $admin_subject, $admin_body, $admin_headers ) ) {
		error_log( '[GEED contact] 運営宛メールの送信に失敗しました（申込は未受付・入力は利用者側に保持）' );
		geed_codex_contact_fail( 'mail', $v );
	}

	// 運営に届いた時点で受付済み。以降の再送は処理しない
	if ( '' !== $token ) {
		set_transient( 'geed_cf_done_' . $token, 1, GEED_CODEX_CONTACT_TTL );
	}

	// === 申込者宛 自動返信メール ===
	$reply_subject = '【GEED】体験・見学予約のお申し込みを承りました';
	$reply_body    = "{$v['name']} 様\n\n";
	$reply_body   .= "この度は、体験・見学予約のお申し込みをいただき、誠にありがとうございます。\n";
	$reply_body   .= "以下の内容で承りました。\n\n";
	$reply_body   .= $bar . "\n【お申し込み内容】\n" . $bar . "\n\n";
	$reply_body   .= "■お名前\n{$v['name']} 様\n\n";
	$reply_body   .= "■ふりがな\n{$v['furigana']}\n\n";
	$reply_body   .= "■ご連絡先\n";
	$reply_body   .= "メールアドレス：{$v['email']}\n";
	$reply_body   .= "電話番号：{$v['phone']}\n\n";
	$reply_body   .= "■ご希望日時\n";
	for ( $i = 1; $i <= 3; $i++ ) {
		$reply_body .= "第{$i}希望：" . geed_codex_contact_line( $i, $v ) . "\n";
	}
	$reply_body   .= "\n■ご希望内容\n{$v['visit_type']}\n\n";
	$reply_body   .= "■参加人数\n{$v['participants']}名\n\n";
	$reply_body   .= "■ご希望・ご質問など\n" . ( $v['inquiry'] ?: '（なし）' ) . "\n\n";
	$reply_body   .= $bar . "\n【今後の流れ】\n" . $bar . "\n\n";
	$reply_body   .= "1. 内容確認後、3営業日以内にお電話またはメールにてご連絡いたします\n";
	$reply_body   .= "2. ご都合の良い日時を調整させていただきます\n";
	$reply_body   .= "3. 当日は動きやすい服装でお越しください\n\n";
	$reply_body   .= "※3営業日以内にご連絡がない場合は、自動的にキャンセルとなりますので\n";
	$reply_body   .= "　あらかじめご了承ください。\n\n";
	$reply_body   .= $bar . "\n【定休日のご案内】\n" . $bar . "\n\n";
	$reply_body   .= "毎週日曜日：ノースタッフのため予約不可\n";
	$reply_body   .= "毎月15日：定休日\n\n";
	$reply_body   .= $bar . "\n【お問い合わせ】\n" . $bar . "\n\n";
	$reply_body   .= "会社名：株式会社GEAR\n";
	$reply_body   .= "所在地：沖縄県那覇市壺屋2丁目26番地11号 1F\n";
	$reply_body   .= "TEL：098-855-1777\n";
	$reply_body   .= "FAX：098-855-1778\n";
	$reply_body   .= "HP：http://geed-style.com/\n\n";
	$reply_body   .= $bar . "\n\n";
	$reply_body   .= "※このメールは自動送信です。このメールに返信いただいても\n";
	$reply_body   .= "　確認できませんので、ご質問等がございましたら上記連絡先まで\n";
	$reply_body   .= "　お問い合わせください。\n\n";
	$reply_body   .= "ご不明な点がございましたらお気軽にお問い合わせください。\n";
	$reply_body   .= "お会いできることを楽しみにしております。\n";

	$reply_headers = array(
		'From: GEED <' . $admin_to . '>',
		'Reply-To: GEED <' . $admin_to . '>',
		'Content-Type: text/plain; charset=UTF-8',
	);
	if ( ! wp_mail( $v['email'], $reply_subject, $reply_body, $reply_headers ) ) {
		// 運営には届いているので受付は成立。完了画面で控えメールが届かない可能性を伝える
		error_log( '[GEED contact] 自動返信メールの送信に失敗しました（運営宛は送信済み）' );
		$token = $token ?: geed_codex_contact_new_token();
		set_transient( 'geed_cf_noreply_' . $token, 1, GEED_CODEX_CONTACT_TTL );
	}

	wp_safe_redirect( add_query_arg( 'sent', $token ?: 'ok', geed_codex_contact_back_url() ) );
	exit;
}
add_action( 'admin_post_nopriv_geed_codex_contact', 'geed_codex_handle_contact' );
add_action( 'admin_post_geed_codex_contact', 'geed_codex_handle_contact' );
