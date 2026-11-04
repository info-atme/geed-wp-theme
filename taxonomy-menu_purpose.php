<?php
/**
 * 目的別ハブ（/menu/purpose/<目的>/）: 導入文（タームの説明）＋メニュー一覧＋関連コラム
 */
get_header();
$term = get_queried_object();
geed_codex_page_hero( array(
	'en' => 'TRAINING MENU',
	'ja' => $term->name . 'のメニュー',
) );
?>
<section class="g-sec">
	<div class="g-container">
		<div class="g-narrow">
		<?php if ( $term->description ) : ?>
			<div class="g-prose"><?php echo wp_kses_post( wpautop( $term->description ) ); ?></div>
		<?php endif; ?>
		<?php
		$menu_posts = $GLOBALS['wp_query']->posts;
		if ( $menu_posts ) : ?>
			<h2 class="g-note"><?php echo esc_html( $term->name . 'のトレーニングメニュー' ); ?></h2>
			<ul class="g-column">
				<?php foreach ( $menu_posts as $p ) :
					$meta = geed_cs_menu_duration_text( $p->ID ); ?>
					<li><a class="g-column__link" href="<?php echo esc_url( get_permalink( $p ) ); ?>"><time lang="en"><?php echo esc_html( $meta ); ?></time><span><?php echo esc_html( get_the_title( $p ) ); ?></span></a></li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>

		<?php
		$cats = array_keys( array_filter( geed_cs_category_purpose(), function ( $p ) use ( $term ) { return $p === $term->slug; } ) );
		if ( $cats ) {
			$posts = get_posts( array( 'posts_per_page' => 6, 'category_name' => implode( ',', $cats ) ) );
			if ( $posts ) : ?>
				<h2 class="g-note">関連するコラム</h2>
				<ul class="g-column">
					<?php foreach ( $posts as $p ) : ?><li><a class="g-column__link" href="<?php echo esc_url( get_permalink( $p ) ); ?>"><span><?php echo esc_html( get_the_title( $p ) ); ?></span></a></li><?php endforeach; ?>
				</ul>
			<?php endif;
		}
		?>
		<a class="g-link" href="<?php echo esc_url( get_post_type_archive_link( 'geed_menu' ) ); ?>">すべてのメニューを見る</a>
		</div>
	</div>
</section>

<?php geed_codex_page_cta(); ?>
<?php get_footer();
