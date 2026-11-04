<?php
/**
 * オンライン（/online/）: 中身が決まるまで CTA は「LINEで相談」（サイト設計 §8）
 */
get_header();
$cta = geed_codex_cta();
geed_codex_page_hero( array(
	'slug' => 'online',
	'en'   => 'ONLINE',
	'ja'   => get_the_title(),
) );
?>
<?php while ( have_posts() ) : the_post(); ?>
	<section class="g-sec">
		<div class="g-container"><div class="g-prose g-narrow"><?php the_content(); ?></div></div>
	</section>
<?php endwhile; ?>

<?php
$home = get_category_by_slug( 'home' );
if ( $home ) {
	$posts = get_posts( array( 'posts_per_page' => 6, 'cat' => $home->term_id ) );
	if ( $posts ) : ?>
		<section class="g-sec g-sec--surface" aria-labelledby="home-training-title">
			<div class="g-container g-sec__grid">
				<header class="g-head">
					<p class="g-head__en" aria-hidden="true"><span>01</span>HOME TRAINING</p>
					<h2 id="home-training-title">自宅でできるトレーニング</h2>
				</header>
				<div class="g-sec__body">
					<ul class="g-column">
						<?php foreach ( $posts as $p ) : ?><li><a class="g-column__link" href="<?php echo esc_url( get_permalink( $p ) ); ?>"><span><?php echo esc_html( get_the_title( $p ) ); ?></span></a></li><?php endforeach; ?>
					</ul>
				</div>
			</div>
		</section>
	<?php endif;
}
?>

<section class="g-final" aria-labelledby="final-title">
	<div class="g-container">
		<p class="g-head__en" lang="en" aria-hidden="true">ONLINE</p>
		<h2 id="final-title">内容・料金はLINEでご相談ください。</h2>
		<p class="g-final__lead">ご希望の目的や回数をお聞きして、合う進め方をご案内します。</p>
		<div class="g-final__cta">
			<div class="g-actions">
				<a class="g-btn g-btn--line" href="<?php echo esc_url( $cta['line'] ); ?>" target="_blank" rel="noopener" data-cta="online">LINEで相談する</a>
			</div>
		</div>
	</div>
</section>
<?php get_footer();
