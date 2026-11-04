<?php
/**
 * コラム記事の本文まわり（2026-10-04 記事ページ改善）
 *
 * 既存の記事本文は書き換えず、表示時に次の加工をする。
 * - 冒頭の手書き「監修: …｜更新日: …」段落を外す（日付・監修はテンプレートで出す）
 * - その次の段落＝結論を「この記事の答え」ボックスで包む
 * - H2 に id を振り、目次を作る（geed_article_toc()）
 * - 中盤の LINE 相談 CTA（.inline-cta）を無料体験の2本立てに差し替える
 * - AIOSEO の Article の著者を、WP ログイン名ではなく監修トレーナーにする
 */

/** 記事の監修者キー（投稿メタ geed_supervisor。未設定は宮本） */
function geed_article_supervisor( $post_id = 0 ) {
	$key  = get_post_meta( $post_id ?: get_the_ID(), 'geed_supervisor', true );
	$list = geed_cs_supervisors();
	return isset( $list[ $key ] ) ? $list[ $key ] : $list['miyamoto'];
}

/** 本文を解析した結果（目次用の H2 一覧・結論ボックス）。the_content フィルタで埋める */
$GLOBALS['geed_article_toc']    = array();
$GLOBALS['geed_article_answer'] = '';

add_filter( 'the_content', function ( $html ) {
	if ( ! is_singular( 'post' ) || ! in_the_loop() || ! is_main_query() ) {
		return $html;
	}

	// 1. 冒頭の「監修: …｜更新日: …」段落を外す
	$html = preg_replace( '#^\s*<p>\s*監修[:：][^<]*</p>\s*#u', '', $html, 1 );

	// 2. 先頭の段落（見出し・図より前）を結論として切り出す。表示は geed_article_answer()
	$GLOBALS['geed_article_answer'] = '';
	if ( preg_match( '#^\s*(<p>(?:(?!</p>).)+</p>)#su', $html, $m ) ) {
		$GLOBALS['geed_article_answer'] = $m[1];
		$html = substr( $html, strlen( $m[0] ) );
	}

	// 3. 本文中の h1 は h2 として扱う（古い記事に残っている。h1 はタイトルだけにする）
	$html = preg_replace( '#<(/?)h1(\s|>)#i', '<$1h2$2', $html );

	// 4. H2 に id を振って目次を作る
	$toc = array();
	$n   = 0;
	$html = preg_replace_callback( '#<h2([^>]*)>(.*?)</h2>#su', function ( $m ) use ( &$toc, &$n ) {
		$n++;
		if ( preg_match( '/\sid="([^"]+)"/', $m[1], $id ) ) {
			$anchor = $id[1];
			$attrs  = $m[1];
		} else {
			$anchor = 's' . $n;
			$attrs  = $m[1] . ' id="' . $anchor . '"';
		}
		$toc[] = array( $anchor, trim( wp_strip_all_tags( $m[2] ) ) );
		return '<h2' . $attrs . '>' . $m[2] . '</h2>';
	}, $html );
	$GLOBALS['geed_article_toc'] = $toc;

	// 5. 中盤の LINE 相談 CTA → 無料体験（Web=金 / LINE=緑）
	$html = preg_replace_callback( '#<div class="inline-cta">.*?</div>#su', function () {
		ob_start();
		echo '<aside class="p-inline-cta" aria-label="無料体験のご案内"><p class="p-inline-cta__title">読んだ内容を、スタッフと一緒に試せます</p>';
		echo '<p class="p-inline-cta__sub">約60分の無料体験で、館内とマシンの使い方をご案内します。ウェア・シューズ・タオルは無料で借りられます。</p>';
		get_template_part( 'template-parts/trial-actions', null, array( 'where' => 'inline' ) );
		echo '</aside>';
		return ob_get_clean();
	}, $html );

	// 6. 古い記事の http:// の画像を https:// に（混在コンテンツ対策）
	$html = str_replace( 'src="http://' . wp_parse_url( home_url(), PHP_URL_HOST ), 'src="https://' . wp_parse_url( home_url(), PHP_URL_HOST ), $html );

	return $html;
}, 20 );

/** 結論ボックス「この記事の答え」。the_content より後に呼ぶ */
function geed_article_answer() {
	if ( '' === $GLOBALS['geed_article_answer'] ) {
		return;
	}
	echo '<div class="p-answer"><p class="p-answer__label">この記事の答え</p>' . $GLOBALS['geed_article_answer'] . '</div>';
}

/** 目次（H2 が3本以上のときだけ）。the_content より後に呼ぶ */
function geed_article_toc() {
	$toc = $GLOBALS['geed_article_toc'];
	if ( count( $toc ) < 3 ) {
		return;
	}
	echo '<details class="p-toc" open><summary class="p-toc__title">この記事の内容</summary><ol class="p-toc__list">';
	foreach ( $toc as $t ) {
		echo '<li><a href="#' . esc_attr( $t[0] ) . '">' . esc_html( $t[1] ) . '</a></li>';
	}
	echo '</ol></details>';
}

/** 監修者ボックス */
function geed_article_supervisor_box() {
	$s = geed_article_supervisor();
	?>
	<aside class="p-sup" id="supervisor" aria-labelledby="sup-title">
		<p class="p-sup__label" id="sup-title">この記事の監修</p>
		<p class="p-sup__name"><?php echo esc_html( $s['name'] ); ?><span><?php echo esc_html( $s['job'] ); ?></span></p>
		<?php if ( ! empty( $s['creds'] ) ) : ?>
			<ul class="p-sup__creds">
				<?php foreach ( $s['creds'] as $c ) : ?><li><?php echo esc_html( $c ); ?></li><?php endforeach; ?>
			</ul>
		<?php endif; ?>
		<p class="p-sup__text">GEED の現場で指導しているトレーナーが、内容を確認しています。</p>
		<a class="g-link" href="<?php echo esc_url( home_url( $s['url'] ) ); ?>">パーソナルトレーニングを見る</a>
	</aside>
	<?php
}

/** AIOSEO の Article: 著者を監修トレーナーに差し替え、WP ユーザー（ログイン名）の Person を消す */
add_filter( 'aioseo_schema_output', function ( $graphs ) {
	if ( ! is_singular( 'post' ) || ! is_array( $graphs ) ) {
		return $graphs;
	}
	$s      = geed_article_supervisor( get_queried_object_id() );
	$person = array(
		'@type'    => 'Person',
		'@id'      => home_url( '/personal/#trainer' ),
		'name'     => $s['name'],
		'jobTitle' => $s['job'],
		'url'      => home_url( $s['url'] ),
		'worksFor' => array( '@id' => home_url( '/' ) . '#gym' ),
	);
	if ( ! empty( $s['creds'] ) ) {
		$person['hasCredential'] = array_map( function ( $c ) {
			return array( '@type' => 'EducationalOccupationalCredential', 'name' => $c );
		}, $s['creds'] );
	}
	$out = array();
	foreach ( $graphs as $g ) {
		$type = isset( $g['@type'] ) ? (array) $g['@type'] : array();
		if ( in_array( 'Person', $type, true ) && isset( $g['@id'] ) && false !== strpos( $g['@id'], '/author/' ) ) {
			continue;
		}
		if ( array_intersect( array( 'Article', 'BlogPosting', 'NewsArticle' ), $type ) ) {
			$g['author'] = array( '@id' => $person['@id'] );
		}
		if ( in_array( 'WebPage', $type, true ) && isset( $g['author'] ) ) {
			$g['author'] = array( '@id' => $person['@id'] );
		}
		$out[] = $g;
	}
	$out[] = $person;
	return $out;
} );

/** 著者アーカイブ（/author/<ログイン名>/）は使わないのでコラム一覧へ */
add_action( 'template_redirect', function () {
	if ( is_author() ) {
		wp_safe_redirect( home_url( '/column/' ), 301 );
		exit;
	}
} );
