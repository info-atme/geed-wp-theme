<?php
/**
 * Template Name: 料金プラン
 * Template Post Type: page
 */
get_header();
geed_codex_page_hero( array(
	'slug' => 'price',
	'en'   => 'PRICE',
	'ja'   => '料金プラン',
	'sub'  => 'シンプルでわかりやすい料金体系。あなたに合うプランをお選びください。',
	'bg'   => 'interior-treadmill.jpg',
) );
$cta = geed_codex_cta();
?>

<?php
$campaign = geed_codex_campaign();
// 料金プラン一覧は template-parts/price-table が geed_price_plans（カスタマイザー）から出す
$price_common = geed_codex_lines( 'geed_price_common', array(
	array( 'トレーニングマシン使い放題' ),
	array( 'ミネラルウォーター飲み放題' ),
	array( 'レンタルウェア・タオル・シューズ無料' ),
	array( '体成分分析装置【InBody】利用可' ),
), 1 );
?>
<section class="g-sec" aria-labelledby="plans-title">
	<div class="g-container g-sec__grid g-sec__grid--stack">
		<header class="g-head">
			<p class="g-head__en" aria-hidden="true"><span>01</span>PLANS</p>
			<h2 id="plans-title">料金プラン詳細</h2>
			<p class="g-head__lead">GEEDの料金プランは、<strong>「無理なく続けられる」</strong>を最優先に設計しています。通い方・年齢・ライフスタイルに合わせてお選びください。</p>
		</header>
		<div class="g-sec__body">
			<?php if ( $campaign['show'] ) : ?>
			<div class="g-callout">
				<p><strong><?php echo esc_html( $campaign['title'] ); ?></strong></p>
				<ul class="g-list">
					<?php foreach ( $campaign['items'] as $item ) : ?>
						<li><?php echo esc_html( trim( $item[0] . ' ' . $item[1] ) ); ?></li>
					<?php endforeach; ?>
				</ul>
				<p class="g-note"><?php echo esc_html( $campaign['note'] ); ?></p>
			</div>
			<?php endif; ?>
			<?php get_template_part( 'template-parts/price-table', null, array( 'with_quote' => true ) ); ?>
			<p class="g-note">※ 表記の料金はすべて税込です。パーソナルトレーニングのみのご利用は <a href="<?php echo esc_url( home_url( '/personal/' ) ); ?>">パーソナルページ</a> をご覧ください。</p>
			<a class="g-link" href="<?php echo esc_url( home_url( '/corporate/' ) ); ?>">法人ページへ</a>
		</div>
	</div>
</section>

<section class="g-sec g-sec--surface" aria-labelledby="common-title">
	<div class="g-container g-sec__grid">
		<header class="g-head">
			<p class="g-head__en" aria-hidden="true"><span>02</span>INCLUDED</p>
			<h2 id="common-title">全プラン共通でご利用いただけます</h2>
		</header>
		<div class="g-sec__body">
			<ul class="g-list">
				<?php foreach ( $price_common as $benefit ) : ?>
					<li><?php echo esc_html( $benefit[0] ); ?></li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
</section>

<section class="g-sec" aria-labelledby="recommend-title">
	<div class="g-container g-sec__grid">
		<header class="g-head">
			<p class="g-head__en" aria-hidden="true"><span>03</span>RECOMMEND</p>
			<h2 id="recommend-title">タイプ別おすすめプラン</h2>
		</header>
		<div class="g-sec__body">
			<dl class="g-table p-price-wide">
				<div>
					<dt>まずは気軽に始めたい方</dt>
					<dd><strong>スタンダードプラン</strong><br>マシン使い放題に無料レンタル・InBodyまで揃った、迷ったらこちらの王道プラン。</dd>
				</div>
				<div>
					<dt>本気で結果を出したい方</dt>
					<dd><strong>プレミアムプラン</strong><br>ジム使い放題＋パーソナルトレーニング月2回付き。短期間で目標達成したい方に。</dd>
				</div>
				<div>
					<dt>条件に合えばさらにおトク</dt>
					<dd><strong>シニア / デイ / 学生プラン</strong><br>60歳以上の方、平日昼に通える方、24歳以下の学生の方は特別価格でご利用いただけます。</dd>
				</div>
			</dl>
		</div>
	</div>
</section>

<section class="g-sec g-sec--surface" aria-labelledby="payment-title">
	<div class="g-container g-sec__grid">
		<header class="g-head">
			<p class="g-head__en" aria-hidden="true"><span>04</span>PAYMENT</p>
			<h2 id="payment-title">お支払い方法</h2>
		</header>
		<div class="g-sec__body">
			<dl class="g-table">
				<div><dt>月額プラン</dt><dd>クレジットカード / 口座振替 / コンビニ払い（初月のみ）</dd></div>
				<div><dt>パーソナル</dt><dd>月額決済 / 都度払い（クレジット可）</dd></div>
				<div><dt>法人</dt><dd>請求書払い（月締め翌月末）</dd></div>
			</dl>
		</div>
	</div>
</section>

<section class="g-sec" aria-labelledby="required-title">
	<div class="g-container g-sec__grid">
		<header class="g-head">
			<p class="g-head__en" aria-hidden="true"><span>05</span>REQUIRED</p>
			<h2 id="required-title">ご入会時に必要なもの</h2>
		</header>
		<div class="g-sec__body">
			<ul class="g-list">
				<li>身分証明書</li>
				<li>通帳など口座情報がわかるもの（口座振替をご希望の場合）</li>
				<li>クレジットカード（カード決済をご希望の場合）</li>
				<li>初月会費</li>
				<li>入会金<?php echo $campaign['show'] ? '（キャンペーン適用あり。内容は上記）' : ''; ?></li>
			</ul>
		</div>
	</div>
</section>

<section class="g-sec g-sec--surface" aria-labelledby="notes-title">
	<div class="g-container g-sec__grid">
		<header class="g-head">
			<p class="g-head__en" aria-hidden="true"><span>06</span>NOTES</p>
			<h2 id="notes-title">ご利用上のご注意</h2>
		</header>
		<div class="g-sec__body">
			<ul class="g-list">
				<li>表記の料金はすべて税込です。</li>
				<li>料金は予告なく変更となる場合があります。</li>
				<?php if ( $campaign['show'] ) : ?><li><?php echo esc_html( preg_replace( '/^※\s*/u', '', $campaign['note'] ) ); ?>。途中退会の場合、通常料金が遡及適用される場合がございます。</li><?php endif; ?>
				<li>休会制度（月額 ¥1,100 / 最長6ヶ月）をご利用いただけます。</li>
			</ul>
		</div>
	</div>
</section>

<?php geed_codex_page_cta(); ?>
<?php get_footer();
