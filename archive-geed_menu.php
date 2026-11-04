<?php
/**
 * トレーニングメニュー一覧（/menu/）: 目的別ハブへの入口＋ユーザー像ごとの一覧
 */
get_header();
geed_codex_page_hero( array(
	'en'  => 'TRAINING MENU',
	'ja'  => 'トレーニングメニュー',
	'sub' => 'GEEDのマシンを使って、目的とタイプ別に「何を・どの順番で」やるかをまとめました。<br>トレーナー監修のメニューです。',
) );

$purposes = get_terms( array( 'taxonomy' => 'menu_purpose', 'hide_empty' => true ) );
$by       = array();
while ( have_posts() ) {
	the_post();
	$by[ get_post_meta( get_the_ID(), 'geed_persona', true ) ?: 'other' ][] = get_post();
}
?>
<?php
// メニュー一覧（g-column: 罫線区切りの縦リスト）。geed_cs_menu_list は旧クラス出力のためここでは使わない
$geed_menu_group = function ( $posts, $label ) {
	if ( ! $posts ) {
		return;
	}
	$personas = geed_cs_personas();
	?>
	<h3 class="g-note"><?php echo esc_html( $label ); ?></h3>
	<ul class="g-column">
		<?php foreach ( $posts as $p ) :
			$persona = get_post_meta( $p->ID, 'geed_persona', true );
			$meta    = geed_cs_menu_duration_text( $p->ID );
			?>
			<li><a class="g-column__link" href="<?php echo esc_url( get_permalink( $p ) ); ?>"><time lang="en"><?php echo esc_html( $meta ); ?></time><span><?php echo esc_html( get_the_title( $p ) ); ?></span></a></li>
		<?php endforeach; ?>
	</ul>
	<?php
};
?>
<?php if ( $purposes && ! is_wp_error( $purposes ) ) : ?>
<section class="g-sec" aria-labelledby="purpose-title">
	<div class="g-container g-sec__grid">
		<header class="g-head">
			<p class="g-head__en" aria-hidden="true"><span>01</span>PURPOSE</p>
			<h2 id="purpose-title">目的から探す</h2>
		</header>
		<div class="g-sec__body">
			<ul class="g-column">
				<?php foreach ( $purposes as $t ) : ?>
					<li><a class="g-column__link" href="<?php echo esc_url( get_term_link( $t ) ); ?>"><time lang="en"><?php echo (int) $t->count; ?>本</time><span><?php echo esc_html( $t->name ); ?></span></a></li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
</section>
<?php endif; ?>

<section class="g-sec g-sec--surface" aria-labelledby="type-title">
	<div class="g-container g-sec__grid">
		<header class="g-head">
			<p class="g-head__en" aria-hidden="true"><span>02</span>TYPE</p>
			<h2 id="type-title">タイプから探す</h2>
		</header>
		<div class="g-sec__body">
			<?php if ( $by ) : ?>
				<?php
				foreach ( geed_cs_personas() as $key => $label ) {
					if ( ! empty( $by[ $key ] ) ) {
						$geed_menu_group( $by[ $key ], $label );
					}
				}
				if ( ! empty( $by['other'] ) ) {
					$geed_menu_group( $by['other'], 'そのほかのメニュー' );
				}
				?>
			<?php else : ?>
				<p>メニューは準備中です。</p>
			<?php endif; ?>
		</div>
	</div>
</section>

<?php geed_codex_page_cta(); ?>
<?php get_footer();
