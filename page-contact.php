<?php
/**
 * Template Name: お問い合わせ・体験予約
 * Template Post Type: page
 */
get_header();
geed_codex_page_hero( array(
	'slug' => 'contact',
	'en'   => 'CONTACT',
	'ja'   => '体験・見学予約',
	'sub'  => 'まずは無料体験・見学から！お気軽にご予約ください。',
	'bg'   => 'interior-entrance.jpg',
) );
$cta  = geed_codex_cta();
$sent = geed_codex_contact_clean_token( $_GET['sent'] ?? '' );
$noreply = $sent && 'ok' !== $sent && get_transient( 'geed_cf_noreply_' . $sent );
$state  = geed_codex_contact_error_state();
$val    = $state['values'] ?? array();
$errs   = $state['errors'] ?? array();
$token  = geed_codex_contact_new_token();
$today  = wp_date( 'Y-m-d' );

$time_slots  = geed_codex_contact_time_slots();
$visit_types = geed_codex_contact_visit_types();

// 保持した入力値（なければ空）
$fv = function ( $key, $i = 0 ) use ( $val ) {
	if ( $i ) {
		return $val[ $key ][ $i ] ?? '';
	}
	return $val[ $key ] ?? '';
};
// 項目別エラー
$fe = function ( $field ) use ( $errs ) {
	if ( empty( $errs[ $field ] ) ) {
		return;
	}
	echo '<p class="contact-form__error" id="err-' . esc_attr( $field ) . '">' . esc_html( $errs[ $field ] ) . '</p>';
};
$fa = function ( $field ) use ( $errs ) {
	if ( ! empty( $errs[ $field ] ) ) {
		echo ' aria-invalid="true" aria-describedby="err-' . esc_attr( $field ) . '"';
	}
};
?>

<section class="g-sec" aria-labelledby="contact-title">
	<div class="g-container">
		<div class="g-narrow">
	<h2 id="contact-title" class="screen-reader-text">お問い合わせ・体験予約</h2>
	<?php if ( $sent ) : ?>
		<div class="form-success g-callout" data-submit-token="<?php echo esc_attr( $sent ); ?>">
			<h2>ご予約のお申し込みありがとうございます。</h2>
			<p><?php if ( $noreply ) : ?>
				お申し込みは受け付けました。ただし、確認メールをお送りできなかった可能性があります（メールアドレスの入力違い・迷惑メール設定など）。<br>
			<?php else : ?>
				ご入力のメールアドレス宛に、確認メールをお送りしました。<br>
			<?php endif; ?>
			内容を確認のうえ、<strong>3営業日以内</strong>にスタッフより、お電話またはメールにてご連絡いたします。<br>
			お急ぎの方は<a href="<?php echo esc_attr( $cta['tel_link'] ); ?>">お電話（<?php echo esc_html( $cta['tel'] ); ?>）</a>または<a href="<?php echo esc_url( $cta['line'] ); ?>" target="_blank" rel="noopener">公式LINE</a>もご利用ください。</p>
		</div>
	<?php endif; ?>

	<?php if ( $state ) : ?>
		<div class="form-error g-callout" role="alert">
			<?php if ( 'mail' === $state['type'] ) : ?>
				<p><strong>送信できませんでした。</strong>お申し込みはまだ受け付けていません。<br>
				時間をおいて再度送信するか、<a href="<?php echo esc_url( $cta['line'] ); ?>" target="_blank" rel="noopener">公式LINE</a>または<a href="<?php echo esc_attr( $cta['tel_link'] ); ?>">お電話（<?php echo esc_html( $cta['tel'] ); ?>）</a>でご予約ください。</p>
			<?php elseif ( 'expired' === $state['type'] ) : ?>
				<p>ページの有効期限が切れたため、送信できませんでした。お手数ですが、内容をご確認のうえ再度送信してください。</p>
			<?php else : ?>
				<p>入力内容に不備があります。<strong>赤字の項目</strong>をご確認のうえ、再度送信してください。</p>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<p class="g-lead">
		まずは <strong>無料体験・見学</strong> から！<br>
		体験・見学予約フォームよりお申し込みください。<br>
		<small>「すぐ返事が欲しい」「文字より話したい」方はLINE・お電話が便利です。</small>
	</p>

	<div class="g-actions">
		<a class="g-btn g-btn--line" href="<?php echo esc_url( $cta['line'] ); ?>" target="_blank" rel="noopener">LINEで気軽に相談</a>
		<a class="g-btn g-btn--form" href="<?php echo esc_attr( $cta['tel_link'] ); ?>">電話する（<?php echo esc_html( $cta['tel'] ); ?>）</a>
	</div>
		</div>
	</div>
</section>

<section class="g-sec g-sec--surface" id="reservation" aria-labelledby="reservation-title">
	<div class="g-container g-sec__grid">
		<header class="g-head">
			<p class="g-head__en" aria-hidden="true"><span>01</span>RESERVATION</p>
			<h2 id="reservation-title">体験・見学予約フォーム</h2>
		</header>
		<div class="g-sec__body">
			<form class="contact-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
				<input type="hidden" name="action" value="geed_codex_contact">
				<input type="hidden" name="form_token" value="<?php echo esc_attr( $token ); ?>">
				<?php wp_nonce_field( 'geed_codex_contact', 'geed_codex_contact_nonce' ); ?>
				<!-- honeypot -->
				<input type="text" name="website" class="contact-form__hp" tabindex="-1" autocomplete="off" aria-hidden="true">

				<div class="contact-form__row">
					<label for="cf-name">お名前 <span class="contact-form__req">必須</span></label>
					<input type="text" id="cf-name" name="your-name" required autocomplete="name" placeholder="例: 山田 太郎" value="<?php echo esc_attr( $fv( 'name' ) ); ?>"<?php $fa( 'your-name' ); ?>>
					<?php $fe( 'your-name' ); ?>
				</div>

				<div class="contact-form__row">
					<label for="cf-furigana">ふりがな <span class="contact-form__req">必須</span></label>
					<input type="text" id="cf-furigana" name="furigana" required placeholder="例: やまだ たろう" value="<?php echo esc_attr( $fv( 'furigana' ) ); ?>"<?php $fa( 'furigana' ); ?>>
					<?php $fe( 'furigana' ); ?>
				</div>

				<div class="contact-form__row">
					<label for="cf-email">メールアドレス <span class="contact-form__req">必須</span></label>
					<input type="email" id="cf-email" name="your-email" required autocomplete="email" placeholder="例: yamada@example.com" value="<?php echo esc_attr( $fv( 'email' ) ); ?>"<?php $fa( 'your-email' ); ?>>
					<?php $fe( 'your-email' ); ?>
				</div>

				<div class="contact-form__row">
					<label for="cf-phone">電話番号 <span class="contact-form__req">必須</span></label>
					<input type="tel" id="cf-phone" name="phone" required autocomplete="tel" placeholder="例: 090-1234-5678" value="<?php echo esc_attr( $fv( 'phone' ) ); ?>"<?php $fa( 'phone' ); ?>>
					<?php $fe( 'phone' ); ?>
				</div>

				<?php for ( $i = 1; $i <= 3; $i++ ) : $req = ( 1 === $i ); ?>
					<div class="contact-form__row contact-form__row--datetime">
						<label for="cf-date<?php echo $i; ?>">
							ご予約第<?php echo $i; ?>希望日時
							<?php if ( $req ) : ?>
								<span class="contact-form__req">必須</span>
							<?php else : ?>
								<span class="contact-form__opt">任意</span>
							<?php endif; ?>
						</label>
						<div class="contact-form__datetime">
							<div>
								<small>日付</small>
								<input type="date" id="cf-date<?php echo $i; ?>" name="date<?php echo $i; ?>" class="js-visit-date" min="<?php echo esc_attr( $today ); ?>" value="<?php echo esc_attr( $fv( 'dates', $i ) ); ?>"<?php echo $req ? ' required' : ''; ?><?php $fa( 'date' . $i ); ?>>
							</div>
							<div>
								<small>時間</small>
								<select id="cf-time<?php echo $i; ?>" name="time<?php echo $i; ?>" aria-label="第<?php echo $i; ?>希望の時間"<?php echo $req ? ' required' : ''; ?>>
									<option value="">選択してください</option>
									<?php foreach ( $time_slots as $t ) : ?>
										<option value="<?php echo esc_attr( $t ); ?>"<?php selected( $fv( 'times', $i ), $t ); ?>><?php echo esc_html( $t ); ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
						<?php $fe( 'date' . $i ); ?>
						<?php if ( $req ) : ?>
							<p class="contact-form__hint">※休館日：毎月15日<br>※毎週日曜日はノースタッフ営業のためご予約を承れません。ご了承ください。<br>※第2・第3希望は任意です。候補があればご入力ください。</p>
						<?php endif; ?>
					</div>
				<?php endfor; ?>

				<p class="contact-form__lead">
					ご入力いただいた希望日時を確認のうえ、ご対応可能な日時を「メールアドレスまたはお電話」にてご連絡させていただきます。<br>
					当店からのご連絡後3営業日以内に折り返しがない場合は、自動的にキャンセルとさせていただきますのであらかじめご了承ください。
				</p>

				<div class="contact-form__row">
					<label for="cf-visit">ご希望の内容を選択してください <span class="contact-form__req">必須</span></label>
					<select id="cf-visit" name="visit-type" required<?php $fa( 'visit-type' ); ?>>
						<?php foreach ( $visit_types as $v ) : ?>
							<option value="<?php echo esc_attr( $v ); ?>"<?php selected( $fv( 'visit_type' ), $v ); ?>><?php echo esc_html( $v ); ?></option>
						<?php endforeach; ?>
					</select>
					<?php $fe( 'visit-type' ); ?>
				</div>

				<div class="contact-form__row">
					<label for="cf-participants">参加人数</label>
					<div class="contact-form__inline">
						<input type="number" id="cf-participants" name="participants" min="1" max="10" value="<?php echo esc_attr( $fv( 'participants' ) ?: 1 ); ?>" inputmode="numeric">
						<span>人</span>
					</div>
				</div>

				<div class="contact-form__row">
					<label for="cf-inquiry">ご希望・ご質問など</label>
					<textarea id="cf-inquiry" name="inquiry" rows="6" placeholder="例：「腰痛がある」「初心者です」「ダイエット希望」など"><?php echo esc_textarea( $fv( 'inquiry' ) ); ?></textarea>
				</div>

				<p class="contact-form__note">
					当社では、お客様より収集する個人情報を、お問い合わせに対して回答する以外の目的で使用することはありません。なお、ご入力いただいた情報は全て暗号化して送信されます。その他、当社のプライバシーポリシーについては<a href="<?php echo esc_url( home_url( '/privacypolicy/' ) ); ?>">こちら</a>をご覧ください。
					<br><small>（お問い合わせの内容によっては返信に多少のお時間がかかる場合やお答えできない場合もございますので、あらかじめご了承ください）</small>
				</p>

				<div class="contact-form__submit">
					<button type="submit" class="g-btn g-btn--form" data-sending-label="送信しています…">予約フォームを送信する</button>
				</div>
			</form>

		</div>
	</div>
</section>

<section class="g-sec" aria-labelledby="access-title">
	<div class="g-container g-sec__grid">
		<header class="g-head">
			<p class="g-head__en" aria-hidden="true"><span>02</span>ACCESS</p>
			<h2 id="access-title">店舗情報・アクセス</h2>
		</header>
		<div class="g-sec__body">
			<dl class="g-table">
				<div><dt>店舗名</dt><dd>GEED Body Make Up Gym</dd></div>
				<div><dt>住所</dt><dd><?php echo esc_html( $cta['address'] ); ?><br>ライオン像と「入会キャンペーン実施中」のノボリが目印です。</dd></div>
				<div><dt>電話</dt><dd><a href="<?php echo esc_attr( $cta['tel_link'] ); ?>"><?php echo esc_html( $cta['tel'] ); ?></a></dd></div>
				<div><dt>営業時間</dt><dd><?php echo esc_html( $cta['hours'] ); ?>（<?php echo esc_html( $cta['closed'] ); ?>）</dd></div>
				<div><dt>公式LINE</dt><dd><a href="<?php echo esc_url( $cta['line'] ); ?>" target="_blank" rel="noopener">LINEで友だち追加</a></dd></div>
				<div><dt>Instagram</dt><dd><a href="<?php echo esc_url( $cta['instagram'] ); ?>" target="_blank" rel="noopener">@geed_style</a></dd></div>
			</dl>
		</div>
	</div>
</section>

<?php get_footer();
