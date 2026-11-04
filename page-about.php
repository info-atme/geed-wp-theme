<?php
/**
 * Template Name: GEEDとは（About）
 * Template Post Type: page
 */
get_header();
geed_codex_page_hero( array(
	'slug' => 'about',
	'en'   => 'ABOUT',
	'ja'   => 'GEEDとは',
	'sub'  => '「ここなら通えそう」と感じてもらえる場所を、那覇市壺屋で。',
	'bg'   => 'interior-main.jpg',
) );
?>

<section class="g-sec" aria-labelledby="concept-title">
	<div class="g-container g-sec__grid">
		<header class="g-head">
			<p class="g-head__en" aria-hidden="true"><span>01</span>CONCEPT</p>
			<h2 id="concept-title">生活に運動を、戻す場所</h2>
		</header>
		<div class="g-sec__body">
			<p class="g-lead">
				GEEDは、那覇市壺屋にある地域密着型のフィットネスジムです。<br>
				「通えるジムが、結局いちばん変われる」 — このシンプルな考えを大切に、
				初心者でも、女性でも、忙しい働く人でも、無理なく続けられる場所を目指しています。
			</p>
			<p class="g-lead">
				ラグジュアリーではなく、<strong>「洗練された親しみ」</strong>。
				高品質な設備と、地域の方ひとりひとりに寄り添える距離感。
				都会的すぎず、敷居も高すぎない、ちょうどいいジムでありたいと思っています。
			</p>
		</div>
	</div>
</section>

<section class="g-sec g-sec--surface" aria-labelledby="thoughts-title">
	<div class="g-container g-sec__grid">
		<header class="g-head">
			<p class="g-head__en" aria-hidden="true"><span>02</span>OUR THOUGHTS</p>
			<h2 id="thoughts-title">GEEDの想い</h2>
		</header>
		<div class="g-sec__body">
			<ul class="g-points g-points--3">
				<li>
					<span class="g-points__no" lang="en">01</span>
					<h3>沖縄に、続けやすいジムを</h3>
					<p>沖縄の暮らしに自然に溶け込むジムを目指しています。仕事帰り、買い物のついで、朝の散歩がてら。生活の延長線上に「運動」を戻す。それがGEEDのテーマです。</p>
				</li>
				<li>
					<span class="g-points__no" lang="en">02</span>
					<h3>初心者の不安に寄り添う</h3>
					<p>「ジムって入りにくい」「マシンの使い方がわからない」「勧誘されたら嫌」 — そんな不安をひとつずつ解消することから。スタッフは全員、勧誘ゼロをお約束します。</p>
				</li>
				<li>
					<span class="g-points__no" lang="en">03</span>
					<h3>女性が安心して通える環境</h3>
					<p>会員さまの約6割が女性です。清潔な更衣室・パウダールームも完備。「ひとりで来ても気まずくない」を徹底しています。</p>
				</li>
			</ul>
		</div>
	</div>
</section>

<section class="g-sec" aria-labelledby="continuity-title">
	<div class="g-container g-sec__grid">
		<header class="g-head">
			<p class="g-head__en" aria-hidden="true"><span>03</span>CONTINUITY</p>
			<h2 id="continuity-title">続けられる、3つのしくみ</h2>
		</header>
		<div class="g-sec__body">
			<ol class="g-steps">
				<li>
					<span class="g-steps__no" lang="en" aria-hidden="true">01</span>
					<h3>朝7時〜夜23時まで通える</h3>
					<p>朝7時から夜23時まで、ライフスタイルに合わせて通えます。仕事前後の時間でも、無理なく続けやすい環境を整えています。</p>
				</li>
				<li>
					<span class="g-steps__no" lang="en" aria-hidden="true">02</span>
					<h3>始めやすい料金 + 入会キャンペーン</h3>
					<p>月額¥6,600〜（学生プラン）、スタンダードプランでも月額¥9,900と、那覇エリアでも始めやすい料金です。おトクな入会キャンペーンの最新情報は<a href="<?php echo esc_url( home_url( '/price/' ) ); ?>">料金プランページ</a>をご覧ください。</p>
				</li>
				<li>
					<span class="g-steps__no" lang="en" aria-hidden="true">03</span>
					<h3>スタッフの「気軽に話せる」距離感</h3>
					<p>マンツーマン指導は希望時のみ。普段はゆるく見守りつつ、聞きたいことがあればすぐ答える。会員さまとの距離感を大切にしています。</p>
				</li>
			</ol>
		</div>
	</div>
</section>

<section class="g-sec g-sec--surface" aria-labelledby="staff-title">
	<div class="g-container g-sec__grid">
		<header class="g-head">
			<p class="g-head__en" aria-hidden="true"><span>04</span>STAFF</p>
			<h2 id="staff-title">「人」で選ばれるジムでありたい</h2>
		</header>
		<div class="g-sec__body">
			<ul class="g-media">
				<li>
					<img src="<?php echo geed_codex_asset( 'img/staff-reception.jpg' ); ?>"<?php echo geed_codex_img_size( 'img/staff-reception.jpg' ); ?> decoding="async" alt="GEEDの受付とエントランス" loading="lazy" data-reveal="clip">
					<div>
						<p>ジム選びは、結局のところ「人」で決まります。マシンの種類より、スタッフの態度。料金より、来た時の空気。<br>GEEDは、会員さまに「来やすい」と思ってもらえるよう、毎日心がけています。</p>
						<a class="g-link" href="<?php echo esc_url( geed_codex_cta()['trial'] ); ?>">見学を予約して相談する</a>
					</div>
				</li>
			</ul>
		</div>
	</div>
</section>

<section class="g-sec" aria-labelledby="interior-title">
	<div class="g-container g-sec__grid g-sec__grid--stack">
		<header class="g-head">
			<p class="g-head__en" aria-hidden="true"><span>05</span>INTERIOR</p>
			<h2 id="interior-title">館内のようす</h2>
		</header>
		<div class="g-sec__body">
			<ul class="g-gallery">
				<li><img src="<?php echo geed_codex_asset( 'img/interior-entrance.jpg' ); ?>"<?php echo geed_codex_img_size( 'img/interior-entrance.jpg' ); ?> decoding="async" alt="エントランス" loading="lazy" data-reveal="clip"><p class="g-gallery__cap"><span lang="en">01</span><b>エントランス</b></p></li>
				<li><img src="<?php echo geed_codex_asset( 'img/interior-main.jpg' ); ?>"<?php echo geed_codex_img_size( 'img/interior-main.jpg' ); ?> decoding="async" alt="メインフロア" loading="lazy" data-reveal="clip"><p class="g-gallery__cap"><span lang="en">02</span><b>メインフロア</b></p></li>
				<li><img src="<?php echo geed_codex_asset( 'img/interior-inbody.jpg' ); ?>"<?php echo geed_codex_img_size( 'img/interior-inbody.jpg' ); ?> decoding="async" alt="InBodyコーナー" loading="lazy" data-reveal="clip"><p class="g-gallery__cap"><span lang="en">03</span><b>InBodyコーナー</b></p></li>
				<li><img src="<?php echo geed_codex_asset( 'img/interior-treadmill.jpg' ); ?>"<?php echo geed_codex_img_size( 'img/interior-treadmill.jpg' ); ?> decoding="async" alt="トレッドミルエリア" loading="lazy" data-reveal="clip"><p class="g-gallery__cap"><span lang="en">04</span><b>トレッドミルエリア</b></p></li>
			</ul>
			<a class="g-link" href="<?php echo esc_url( home_url( '/facility/' ) ); ?>">設備とマシンの詳細を見る</a>
		</div>
	</div>
</section>

<?php geed_codex_page_cta(); ?>
<?php get_footer();
