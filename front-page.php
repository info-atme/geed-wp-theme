<?php
/**
 * TOP（v2・ギャラリー水準への作り直し 2026-10-04）
 * 目的: 無料体験の申し込み数を最大化する。
 * 並び: ヒーロー → キャンペーン（管理画面で設定・終了日で自動非表示） → 01料金 → 02口コミ → 03スタッフ → 04無料体験の約60分 → 05場所 → 06設備 → 07FAQ → 申込 → コラム（research/marketing_framework.md §8）。
 * 体験の申込口は LINE とフォームの2本立て。本文のボタンはヒーロー・料金・最後の申込の3か所だけ（フッター申込帯はTOPでは出さない）。
 * 型: ヒーローは左に墨のパネル（ロゴ・コピー・申込）、右に人物の実写（1画面目で「ジム・無料体験」と分かること）、番号＋英字＋和文の見出し、カードを並べず罫線と余白で区切る（research/renewal_spec.md 2026-10-04 追加分）。
 * 墨はヒーローだけ。写真は実写のみ（GEED_photo から切り出し）。
 */
get_header();
$cta      = geed_codex_cta();
$campaign = geed_codex_campaign();

$faqs = geed_codex_lines( 'geed_faqs', array(
	array( '体験は無料ですか？', '無料です。所要は約60分で、館内とマシンの使い方をスタッフがご案内します。' ),
	array( '何を持って行けばいいですか？', 'ウェア・シューズ・タオルは無料で借りられます。水も無料で飲めます。' ),
	array( '体験のあと、入会を勧められますか？', '勧誘はしません。その日に決める必要はなく、持ち帰って考えていただけます。' ),
	array( '運動経験がなくても大丈夫ですか？', '大丈夫です。運動が久しぶりの方、ジムが初めての方も、軽い運動から始められます。' ),
	array( '空いている時間はありますか？', '朝7〜10時と11〜15時は比較的ゆったりしています。仕事帰りの18〜21時は混みやすい時間です（時期によって変わります）。' ),
	array( '駐車場はありますか？', '提携駐車場が10台分あり、無料でご利用いただけます。場所は店頭・公式LINEでご案内します。' ),
), 2 );

// Google口コミ（2026-10-03 時点の転記。本文は原文から抜粋、言い換えない）
$review_score = '4.6';
$review_count = '34';
$reviews      = array(
	array( '宮本さんがフォームや効かせ方を丁寧に教えてくださり、どこに効くのか、こうするともっと良いなど具体的なアドバイスもたくさんいただきました。', 'ゆい さん' ),
	array( '以前は別の場所に通っていましたが、人が多いとマシン待ちなどがあってストレスだったのでその面ではとても良い。', 'Googleの口コミより' ),
	array( '施設内は清潔でロッカールーム・シャワールーム完備。マシンも多く非常に使いやすい施設でした。', '多田 さん' ),
);
$reviews_url = geed_codex_mod( 'geed_social_google_url', 'https://www.google.com/maps/search/?api=1&query=GEED%20Body%20Make%20Up%20Gym' );

$facilities = array(
	array( 'top/booty-builder-woman.jpg', 'Booty Builder', 'お尻と脚まわりを集中して鍛えるマシン' ),
	array( 'top/legpress-woman.jpg', 'レッグプレス', '座ったまま下半身を鍛えられる' ),
	array( 'top/cardio-area.jpg', '有酸素エリア', 'トレッドミルとバイク。窓際で明るい' ),
	array( 'top/free-weight-squat.jpg', 'フリーウェイト', 'ダンベル・ラック・スミスマシン' ),
	array( 'top/interior-inbody.jpg', 'InBody（体組成計）', '体重だけでなく筋肉量・体脂肪率を測定' ),
);

/** 見出し: 番号＋英字＋和文 */
$head = function ( $no, $en, $title_id, $title, $lead = '' ) {
	?>
	<header class="g-head">
		<p class="g-head__en" aria-hidden="true"><span><?php echo esc_html( $no ); ?></span><?php echo esc_html( $en ); ?></p>
		<h2 id="<?php echo esc_attr( $title_id ); ?>"><?php echo wp_kses( $title, array( 'br' => array() ) ); ?></h2>
		<?php if ( $lead ) : ?><p class="g-head__lead"><?php echo esc_html( $lead ); ?></p><?php endif; ?>
	</header>
	<?php
};

$logo_svg = file_get_contents( get_theme_file_path( '/assets/img/logo-geed.svg' ) );
?>

<section class="t-hero" aria-labelledby="hero-title">
	<div class="t-hero__grid">
		<picture class="t-hero__photo">
			<source media="(min-width: 900px)" srcset="<?php echo geed_codex_asset( 'img/v2/hero-train-pc.jpg' ); ?>" width="1400" height="1458">
			<img src="<?php echo geed_codex_asset( 'img/v2/hero-train-sp.jpg' ); ?>" alt="GEEDのマシンでトレーニングする会員" width="1200" height="900" fetchpriority="high">
		</picture>
		<div class="t-hero__panel">
			<div class="t-hero__mark" role="img" aria-label="GEED"><?php echo str_replace( '<svg ', '<svg aria-hidden="true" focusable="false" ', $logo_svg ); // テーマ同梱のSVG ?></div>
			<p class="t-hero__kicker">壺屋で6年のトレーニングジム<span lang="en">TRAINING GYM &mdash; TSUBOYA, NAHA</span></p>
			<h1 id="hero-title">那覇市壺屋の、<br>ジムが初めての方へ。</h1>
			<p class="t-hero__offer"><span>無料体験</span>約60分・スタッフが案内</p>
			<p class="t-hero__lead">館内とマシンの使い方をご案内します。ウェア・シューズ・タオルは無料で借りられます。</p>
			<div class="t-hero__cta"><?php get_template_part( 'template-parts/trial-actions', null, array( 'where' => 'hero' ) ); ?></div>
		</div>
	</div>
	<dl class="t-hero__facts g-container">
		<div><dt>月額（スタンダード・税込）</dt><dd><b lang="en">9,900</b>円</dd></div>
		<div><dt>営業（毎月15日休館）</dt><dd><b lang="en">7:00&ndash;23:00</b></dd></div>
		<div><dt>提携駐車場・無料</dt><dd><b lang="en">10</b>台</dd></div>
	</dl>
</section>

<?php if ( $campaign['show'] ) : // ビラ・Instagram から来た人が最初に探す情報なのでヒーロー直下。内容は管理画面で入れ替える ?>
<section class="t-cp" id="campaign" aria-labelledby="campaign-title">
	<div class="g-container t-cp__inner<?php echo $campaign['image'] ? ' has-image' : ''; ?>">
		<?php if ( $campaign['image'] ) : ?>
			<figure class="t-cp__image"><img src="<?php echo esc_url( $campaign['image'] ); ?>" alt="<?php echo esc_attr( $campaign['title'] ); ?>" loading="lazy"></figure>
		<?php endif; ?>
		<div class="t-cp__body">
			<p class="t-cp__label"><span lang="en">CAMPAIGN</span><?php if ( $campaign['period'] ) : ?><b><?php echo esc_html( $campaign['period'] ); ?></b><?php endif; ?></p>
			<h2 id="campaign-title"><?php echo esc_html( $campaign['title'] ); ?></h2>
			<dl class="t-cp__offers">
				<?php foreach ( $campaign['items'] as $item ) : ?>
					<div><dt><?php echo esc_html( $item[0] ); ?></dt><dd><?php echo esc_html( $item[1] ); ?></dd></div>
				<?php endforeach; ?>
			</dl>
			<?php if ( $campaign['note'] ) : ?><p class="t-cp__note"><?php echo esc_html( $campaign['note'] ); ?></p><?php endif; ?>
			<a class="g-link" href="#price"><?php echo esc_html( geed_codex_mod( 'geed_join_btn', '料金プランを見る' ) ); ?></a>
		</div>
	</div>
</section>
<?php endif; ?>

<section class="g-sec" id="price" aria-labelledby="price-title">
	<div class="g-container g-sec__grid">
		<?php $head( '01', 'PRICE', 'price-title', 'スタンダード<br>月額9,900円（税込）', '全プランで、マシン使い放題・ウェアとシューズとタオルのレンタル・水・InBody測定が無料です。' ); ?>
		<div class="g-sec__body">
			<?php get_template_part( 'template-parts/price-table', null, array( 'with_quote' => false ) ); ?>
			<div class="t-price__notes">
				<div class="t-join">
					<h3>入会時に払うもの</h3>
					<ul>
						<li>初月の月会費</li>
						<li>入会金 11,000円<?php echo $campaign['show'] ? '（キャンペーン適用あり）' : ''; ?></li>
						<li>事務手数料（金額は店頭・公式LINEでご案内します）</li>
					</ul>
					<p>退会は、退会したい月の前月9日までに店頭か所定のフォームで。引き止めはしません。</p>
				</div>
			</div>
			<a class="g-link" href="<?php echo esc_url( home_url( '/price/' ) ); ?>">料金の詳細を見る</a>
			<?php get_template_part( 'template-parts/trial-actions', null, array( 'where' => 'price' ) ); ?>
		</div>
	</div>
</section>

<section class="g-sec g-sec--surface" aria-labelledby="reviews-title">
	<div class="g-container g-sec__grid">
		<?php $head( '02', 'VOICE', 'reviews-title', 'Googleの口コミ' ); ?>
		<div class="g-sec__body">
			<p class="t-score"><b lang="en"><?php echo esc_html( $review_score ); ?></b><span>/ 5<br><?php echo esc_html( $review_count ); ?>件・2026年10月時点</span></p>
			<ul class="t-quotes">
				<?php foreach ( $reviews as $review ) : ?>
					<li>
						<blockquote><?php echo esc_html( $review[0] ); ?></blockquote>
						<p><?php echo esc_html( $review[1] ); ?></p>
					</li>
				<?php endforeach; ?>
			</ul>
			<a class="g-link" href="<?php echo esc_url( $reviews_url ); ?>" target="_blank" rel="noopener">Googleマップで口コミを見る</a>
		</div>
	</div>
</section>

<section class="g-sec" aria-labelledby="staff-title">
	<div class="g-container g-sec__grid">
		<?php $head( '03', 'STAFF', 'staff-title', 'GEEDのスタッフ', '体験の案内は、その日にいるスタッフが担当します。' ); ?>
		<div class="g-sec__body">
			<div class="t-staff">
				<figure class="t-staff__photo">
					<img src="<?php echo geed_codex_asset( 'img/staff/miyamoto-025.jpg' ); ?>"<?php echo geed_codex_img_size( 'img/staff/miyamoto-025.jpg' ); ?> decoding="async" alt="宮本 達矢" loading="lazy" data-reveal="clip">
				</figure>
				<div class="t-staff__text">
					<p class="t-staff__role">トレーナー / 元プロボクサー</p>
					<h3>宮本 達矢</h3>
					<p>プロボクサーA級ライセンスで10年・通算28戦。「あなたの身体が1番大事」を軸に、運動と食事と体のケアを一緒に考えます。</p>
					<a class="g-link" href="<?php echo esc_url( home_url( '/personal/' ) ); ?>">パーソナルトレーニングを見る</a>
				</div>
			</div>
			<dl class="t-staff__others">
				<div><dt>スタッフ</dt><dd>仲里</dd></div>
				<div><dt>スタッフ</dt><dd>當間</dd></div>
			</dl>
		</div>
	</div>
</section>

<section class="g-sec g-sec--surface" id="trial-flow" aria-labelledby="flow-title">
	<div class="g-container g-sec__grid">
		<?php $head( '04', 'FIRST VISIT', 'flow-title', '無料体験の<br>約60分でやること' ); ?>
		<div class="g-sec__body">
			<ol class="g-steps">
				<li>
					<span class="g-steps__no" lang="en" aria-hidden="true">01</span>
					<h3>予約して来店</h3>
					<p>Webフォームか公式LINEで希望日時を送ってください。スタッフから連絡して日時を確定します。</p>
				</li>
				<li>
					<span class="g-steps__no" lang="en" aria-hidden="true">02</span>
					<h3>館内とマシンの案内</h3>
					<p>ロッカー、マシンの使い方、通い方をスタッフがご案内します。</p>
				</li>
				<li>
					<span class="g-steps__no" lang="en" aria-hidden="true">03</span>
					<h3>実際に動いてみる</h3>
					<p>無理のない範囲でマシンを使ってみます。希望があればInBodyでの測定もできます。</p>
				</li>
			</ol>
		</div>
	</div>
</section>

<section class="g-sec" id="access" aria-labelledby="access-title">
	<div class="g-container g-sec__grid">
		<?php $head( '05', 'ACCESS', 'access-title', '壺屋の店舗・駐車場', 'ライオン像とノボリが目印。安里駅から歩いて約10分です。' ); ?>
		<div class="g-sec__body">
			<dl class="g-table">
				<div><dt>住所</dt><dd><?php echo esc_html( $cta['address'] ); ?></dd></div>
				<div><dt>徒歩</dt><dd>安里駅から約10分、バス停「三原」から約5分</dd></div>
				<div><dt>駐車場</dt><dd>提携駐車場10台・無料（場所は店頭・公式LINEでご案内）</dd></div>
				<div><dt>営業</dt><dd><?php echo esc_html( $cta['hours'] ); ?>（<?php echo esc_html( $cta['closed'] ); ?>）<br>体験予約：日曜・毎月15日を除く</dd></div>
				<div><dt>電話</dt><dd><a href="<?php echo esc_url( $cta['tel_link'] ); ?>"><?php echo esc_html( $cta['tel'] ); ?></a></dd></div>
			</dl>
			<a class="g-link" href="https://www.google.com/maps/search/?api=1&query=GEED%20Body%20Make%20Up%20Gym" target="_blank" rel="noopener">Googleマップで見る</a>
		</div>
	</div>
</section>

<section class="g-sec g-sec--surface" id="facility" aria-labelledby="facility-title">
	<div class="g-container g-sec__grid">
		<?php $head( '06', 'FACILITY', 'facility-title', '体験できる設備', '約100坪に、マシン12種。' ); ?>
		<div class="g-sec__body">
			<ul class="g-gallery">
				<?php foreach ( $facilities as $i => $item ) : ?>
					<li>
						<img src="<?php echo geed_codex_asset( 'img/' . $item[0] ); ?>"<?php echo geed_codex_img_size( 'img/' . $item[0] ); ?> decoding="async" alt="<?php echo esc_attr( $item[1] ); ?>" loading="lazy" data-reveal="clip">
						<p class="g-gallery__cap"><span lang="en"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span><b><?php echo esc_html( $item[1] ); ?></b><?php echo esc_html( $item[2] ); ?></p>
					</li>
				<?php endforeach; ?>
			</ul>
			<a class="g-link" href="<?php echo esc_url( home_url( '/facility/' ) ); ?>">マシン一覧を見る</a>
		</div>
	</div>
</section>

<section class="g-sec" id="faq" aria-labelledby="faq-title">
	<div class="g-container g-sec__grid">
		<?php $head( '07', 'FAQ', 'faq-title', '体験前の<br>よくある質問' ); ?>
		<div class="g-sec__body">
			<div class="g-faq">
				<?php foreach ( $faqs as $faq ) : ?>
					<details>
						<summary><?php echo esc_html( $faq[0] ); ?></summary>
						<p><?php echo esc_html( $faq[1] ); ?></p>
					</details>
				<?php endforeach; ?>
			</div>
			<a class="g-link" href="<?php echo esc_url( home_url( '/faq/' ) ); ?>">質問をすべて見る</a>
		</div>
	</div>
</section>

<section class="g-final" aria-labelledby="final-title">
	<div class="g-container">
		<p class="g-head__en" aria-hidden="true">FREE TRIAL</p>
		<h2 id="final-title">まずは、約60分の<br>無料体験から。</h2>
		<p class="g-final__lead">勧誘はしません。体験のあと、その場で決める必要はありません。</p>
		<div class="g-final__cta"><?php get_template_part( 'template-parts/trial-actions', null, array( 'where' => 'faq', 'note' => '体験予約：日曜・毎月15日を除く。' ) ); ?></div>
	</div>
</section>

<?php
$top_column_q = new WP_Query( array(
	'post_type'           => 'post',
	'posts_per_page'      => 3,
	'category__not_in'    => array( (int) get_option( 'default_category' ) ),
	'ignore_sticky_posts' => true,
	'no_found_rows'       => true,
) );
if ( $top_column_q->have_posts() ) : ?>
<section class="g-sec g-sec--surface" aria-labelledby="column-title">
	<div class="g-container g-sec__grid">
		<?php $head( '08', 'COLUMN', 'column-title', 'コラム' ); ?>
		<div class="g-sec__body">
			<ul class="g-column">
				<?php while ( $top_column_q->have_posts() ) : $top_column_q->the_post(); ?>
					<li>
						<a class="g-column__link" href="<?php the_permalink(); ?>">
							<time datetime="<?php echo esc_attr( get_the_date( 'Y-m-d' ) ); ?>" lang="en"><?php echo esc_html( get_the_date( 'Y.m.d' ) ); ?></time>
							<span><?php the_title(); ?></span>
						</a>
					</li>
				<?php endwhile; wp_reset_postdata(); ?>
			</ul>
			<a class="g-link" href="<?php echo esc_url( get_permalink( (int) get_option( 'page_for_posts' ) ) ); ?>">コラムをすべて見る</a>
		</div>
	</div>
</section>
<?php endif; ?>

<?php
get_footer();
