<?php
/**
 * Template Name: 法人プラン
 * Template Post Type: page
 */
get_header();
geed_codex_page_hero( array(
	'slug' => 'corporate',
	'en'   => 'CORPORATE',
	'ja'   => '法人プラン',
	'sub'  => '従業員の健康と、企業のブランド価値を、いっしょに。',
	'bg'   => 'interior-treadmill.jpg',
) );
$cta = geed_codex_cta();
?>

<section class="g-sec">
	<div class="g-container">
		<p class="g-lead g-narrow">
			従業員の健康保持・増進は、企業の競争力の根幹です。<br>
			GEEDの法人プランは、<strong>従業員数や利用頻度にあわせて柔軟に設計</strong>。<br>
			健康経営・福利厚生・社内コミュニケーションのきっかけづくりに、ぜひご活用ください。
		</p>
	</div>
</section>

<section class="g-sec g-sec--surface" aria-labelledby="benefits-title">
	<div class="g-container g-sec__grid g-sec__grid--stack">
		<header class="g-head">
			<p class="g-head__en" aria-hidden="true"><span>01</span>BENEFITS</p>
			<h2 id="benefits-title">法人契約のメリット</h2>
		</header>
		<div class="g-sec__body">
			<ul class="g-points g-points--3">
				<li><span class="g-points__no" lang="en">01</span><h3>福利厚生として活用</h3><p>従業員のジム利用料を法人側で負担できます。健康経営優良法人の認定対策にも。</p></li>
				<li><span class="g-points__no" lang="en">02</span><h3>柔軟なプラン設計</h3><p>「全社員一括」「希望者のみ」「月◯回まで補助」など、企業ごとに最適な設計をご提案。</p></li>
				<li><span class="g-points__no" lang="en">03</span><h3>請求書一括対応</h3><p>個々の従業員さまへの請求は発生しません。月締めの請求書払いで経理処理もスムーズ。</p></li>
				<li><span class="g-points__no" lang="en">04</span><h3>社内交流のきっかけ</h3><p>同僚と一緒に通うことで、部署を越えたコミュニケーションが自然に生まれます。</p></li>
				<li><span class="g-points__no" lang="en">05</span><h3>健康診断後のフォロー</h3><p>健診結果が気になる従業員さまへの運動指導も、専門スタッフが対応可能です。</p></li>
				<li><span class="g-points__no" lang="en">06</span><h3>那覇市中心部・通いやすい立地</h3><p>那覇市壺屋の中心部。仕事帰り・お昼休みにも気軽に立ち寄れる便利な場所にあります。</p></li>
			</ul>
		</div>
	</div>
</section>

<section class="g-sec" aria-labelledby="example-title">
	<div class="g-container g-sec__grid">
		<header class="g-head">
			<p class="g-head__en" aria-hidden="true"><span>02</span>PLAN EXAMPLE</p>
			<h2 id="example-title">プラン例</h2>
		</header>
		<div class="g-sec__body">
			<dl class="g-table p-corp-wide">
				<div><dt>10名規模・全員加入</dt><dd><strong>¥4,500 / 月・人 〜</strong><br>全従業員にフリーパス。月額×10名で割引適用。健康保険組合との連携も可能です。</dd></div>
				<div><dt>30名規模・希望者制（人気）</dt><dd><strong>応相談</strong><br>希望者のみ加入。会社負担＋個人負担のハイブリッドが多いパターンです。</dd></div>
				<div><dt>小規模・月◯回まで補助</dt><dd><strong>応相談</strong><br>月◯回までは会社負担、それ以上は個人負担。小規模事業者さまにおすすめ。</dd></div>
			</dl>
			<p class="g-note">※ 上記はあくまで一例です。御社の規模・ご予算・運用方針に合わせて柔軟にご提案いたします。</p>
		</div>
	</div>
</section>

<section class="g-sec g-sec--surface" aria-labelledby="flow-title">
	<div class="g-container g-sec__grid g-sec__grid--stack">
		<header class="g-head">
			<p class="g-head__en" aria-hidden="true"><span>03</span>FLOW</p>
			<h2 id="flow-title">ご契約までの流れ</h2>
		</header>
		<div class="g-sec__body">
			<ol class="g-steps">
				<li><span class="g-steps__no" lang="en" aria-hidden="true">01</span><h3>お問い合わせ</h3><p>下記フォームまたはLINEからお気軽にご連絡ください。</p></li>
				<li><span class="g-steps__no" lang="en" aria-hidden="true">02</span><h3>ヒアリング</h3><p>御社の状況・ご希望をお伺いします（オンライン可・所要30分）。</p></li>
				<li><span class="g-steps__no" lang="en" aria-hidden="true">03</span><h3>プラン提案・お見積り</h3><p>3営業日以内にお見積書を提出いたします。</p></li>
				<li><span class="g-steps__no" lang="en" aria-hidden="true">04</span><h3>ご契約・運用開始</h3><p>契約締結後、最短2週間で従業員さまのご利用を開始できます。</p></li>
			</ol>
		</div>
	</div>
</section>

<section class="g-sec" aria-label="法人契約のお問い合わせ">
	<div class="g-container">
		<div class="g-actions">
			<a class="g-btn g-btn--form" href="<?php echo esc_url( home_url( '/contact/?topic=' . rawurlencode( '法人契約' ) ) ); ?>">法人契約について問い合わせる</a>
			<a class="g-btn g-btn--line" href="<?php echo esc_url( $cta['line'] ); ?>" target="_blank" rel="noopener">LINEで気軽に相談</a>
		</div>
	</div>
</section>

<?php get_footer();
