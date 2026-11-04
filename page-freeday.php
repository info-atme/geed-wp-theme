<?php
/**
 * Template Name: 無料開放DAY
 * Template Post Type: page
 */
get_header();
geed_codex_page_hero( array(
	'slug' => 'freeday',
	'en'   => 'FREE DAY',
	'ja'   => '無料開放DAY',
	'sub'  => '「いきなり入会は…」という方に。GEEDをまるごと体験できる日。',
	'bg'   => 'top/gym-floor-wide.jpg', // 仮: 旧チラシ(campaign-freeday)は日付が古いので差し替え
) );
$cta = geed_codex_cta();
?>

<section class="g-sec">
	<div class="g-container">
		<p class="g-lead g-narrow">
			無料開放DAYは、月に数回、<strong>GEEDのすべての施設を無料で体験できる日</strong>です。<br>
			見学だけでも、お友達同士でも、女性ひとりでも歓迎。勧誘は一切ありません。
		</p>
	</div>
</section>

<section class="g-sec g-sec--surface" aria-labelledby="fd-feat-title">
	<div class="g-container g-sec__grid">
		<header class="g-head">
			<p class="g-head__en" aria-hidden="true"><span>01</span>FEATURES</p>
			<h2 id="fd-feat-title">無料開放DAYの特徴</h2>
		</header>
		<div class="g-sec__body">
			<ul class="g-list p-freeday-feat">
				<li>初心者歓迎</li>
				<li>見学だけOK</li>
				<li>無理な勧誘なし</li>
				<li>友達同士OK</li>
				<li>女性ひとりでもOK</li>
				<li>マシン全て使い放題</li>
			</ul>
		</div>
	</div>
</section>

<section class="g-sec" aria-labelledby="fd-flow-title">
	<div class="g-container g-sec__grid g-sec__grid--stack">
		<header class="g-head">
			<p class="g-head__en" aria-hidden="true"><span>02</span>FLOW</p>
			<h2 id="fd-flow-title">参加の流れ</h2>
		</header>
		<div class="g-sec__body">
			<ol class="g-steps p-freeday-steps">
				<li><span class="g-steps__no" lang="en" aria-hidden="true">01</span><h3>LINEで日程確認</h3><p>公式LINEに「無料開放DAY参加したいです」とメッセージ。スタッフが最新の開催日をご案内します。</p></li>
				<li><span class="g-steps__no" lang="en" aria-hidden="true">02</span><h3>参加日の予約</h3><p>ご希望の日にちを予約。所要時間は60〜90分程度を想定してお越しください。</p></li>
				<li><span class="g-steps__no" lang="en" aria-hidden="true">03</span><h3>ご来店・館内ご案内</h3><p>動きやすい服装でご来店。受付後、館内のご案内とマシンの基本説明を行います。</p></li>
				<li><span class="g-steps__no" lang="en" aria-hidden="true">04</span><h3>自由に体験</h3><p>気になるマシンを自由にお試しください。スタッフは近くにいますので、わからないことはすぐに聞けます。</p></li>
				<li><span class="g-steps__no" lang="en" aria-hidden="true">05</span><h3>そのままご帰宅 or 入会相談</h3><p>気に入ったらキャンペーンを利用してご入会OK。「持ち帰って検討」も自由です。</p></li>
			</ol>
		</div>
	</div>
</section>

<section class="g-sec g-sec--surface" aria-labelledby="fd-sched-title">
	<div class="g-container g-sec__grid">
		<header class="g-head">
			<p class="g-head__en" aria-hidden="true"><span>03</span>SCHEDULE</p>
			<h2 id="fd-sched-title">次回開催日</h2>
		</header>
		<div class="g-sec__body">
			<?php
			$q = new WP_Query( array(
				'post_type'      => 'news',
				'posts_per_page' => 3,
				'tax_query'      => array(
					array( 'taxonomy' => 'news_cat', 'field' => 'slug', 'terms' => 'freeday' ),
				),
			) );
			if ( $q->have_posts() ) : ?>
				<ul class="g-column">
					<?php while ( $q->have_posts() ) : $q->the_post(); ?>
						<li>
							<a class="g-column__link" href="<?php the_permalink(); ?>">
								<time lang="en" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date( 'Y.m.d' ) ); ?></time>
								<span><?php the_title(); ?></span>
							</a>
						</li>
					<?php endwhile; ?>
				</ul>
				<?php wp_reset_postdata(); ?>
			<?php else : ?>
				<p>開催日は不定期で更新しています。最新情報は公式LINEからご確認ください。</p>
			<?php endif; ?>

			<p class="p-freeday-line"><a class="g-link" href="<?php echo esc_url( $cta['line'] ); ?>" target="_blank" rel="noopener">LINEで開放DAYの日程を聞く</a></p>
		</div>
	</div>
</section>

<section class="g-sec" aria-labelledby="fd-notes-title">
	<div class="g-container g-sec__grid">
		<header class="g-head">
			<p class="g-head__en" aria-hidden="true"><span>04</span>NOTES</p>
			<h2 id="fd-notes-title">当日のご案内</h2>
		</header>
		<div class="g-sec__body">
			<dl class="g-table">
				<div><dt>持ち物</dt><dd>動きやすい服装とお飲み物だけでOK。ウェア・シューズ・タオルは無料レンタルをご用意しています。</dd></div>
				<div><dt>所要時間</dt><dd>60〜90分目安。短時間の見学だけでもOKです。</dd></div>
				<div><dt>定員</dt><dd>混雑時は入場制限を行う場合があります。事前のご予約がスムーズです。</dd></div>
				<div><dt>勧誘</dt><dd>一切ありません。気に入った方のみ、当日キャンペーン適用でのご入会が可能です。</dd></div>
			</dl>
		</div>
	</div>
</section>

<?php geed_codex_page_cta(); ?>
<?php get_footer();
