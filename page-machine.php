<?php
/**
 * Template Name: マシン別ページ
 * Template Post Type: page
 *
 * /facility/<machine>/ 。スラッグ = マシンキー（inc/content-structure.php geed_cs_machines()）
 */
get_header();
while ( have_posts() ) : the_post();
	$key      = get_post_field( 'post_name', get_the_ID() );
	$machines = geed_cs_machines();
	geed_codex_page_hero( array(
		'en' => 'MACHINE',
		'ja' => get_the_title(),
	) );
	?>
	<article class="g-sec">
		<div class="g-container">
			<?php if ( has_post_thumbnail() ) : ?><div class="p-machine-eyecatch"><?php the_post_thumbnail( 'large' ); ?><?php geed_cs_eyecatch_caption(); ?></div><?php endif; ?>
			<div class="g-prose g-narrow"><?php the_content(); ?></div>

			<?php
			if ( isset( $machines[ $key ] ) ) {
				geed_cs_menu_list( geed_cs_menus_by_machine( $key ), $machines[ $key ] . 'を使うトレーニングメニュー' );
			}
			$others = array_diff_key( geed_cs_machine_pages(), array( $key => true ) );
			if ( $others ) {
				geed_cs_machine_links( array_keys( $others ), 'ほかのマシン' );
			}
			?>
			<p class="p-machine-back"><a class="g-link" href="<?php echo esc_url( home_url( '/facility/' ) ); ?>">施設紹介に戻る</a></p>
		</div>
	</article>
<?php endwhile; ?>
<?php geed_codex_page_cta(); ?>
<?php get_footer();
