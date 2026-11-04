<?php
/**
 * トレーニングメニュー詳細（content/サイト設計.md §3-2）
 */
get_header();
$personas = geed_cs_personas();
$sups     = geed_cs_supervisors();
?>
<?php while ( have_posts() ) : the_post();
	$id       = get_the_ID();
	$persona  = get_post_meta( $id, 'geed_persona', true );
	$goal     = get_post_meta( $id, 'geed_goal', true );
	$machines = geed_cs_split( get_post_meta( $id, 'geed_machines', true ) );
	$cautions = geed_cs_split( str_replace( array( ',', '、' ), '', get_post_meta( $id, 'geed_cautions', true ) ) );
	$sup      = $sups[ get_post_meta( $id, 'geed_supervisor', true ) ] ?? null;
	$purposes = get_the_terms( $id, 'menu_purpose' ) ?: array();
	?>
	<article <?php post_class( 'p-article p-article--menu' ); ?>>
		<header class="p-article__head g-container g-narrow">
			<?php geed_codex_breadcrumb(); ?>
			<p class="g-head__en" lang="en" aria-hidden="true">TRAINING MENU</p>
			<h1><?php the_title(); ?></h1>
			<?php if ( $purposes ) : ?>
				<p class="p-article__meta">
					<?php foreach ( $purposes as $t ) : ?>
						<a href="<?php echo esc_url( get_term_link( $t ) ); ?>"><?php echo esc_html( $t->name ); ?></a>
					<?php endforeach; ?>
				</p>
			<?php endif; ?>
		</header>

		<div class="g-container g-narrow">
			<dl class="g-table p-article__spec">
				<?php if ( isset( $personas[ $persona ] ) ) : ?><div><dt>対象</dt><dd><?php echo esc_html( $personas[ $persona ] ); ?></dd></div><?php endif; ?>
				<?php if ( $goal ) : ?><div><dt>ゴール</dt><dd><?php echo esc_html( $goal ); ?></dd></div><?php endif; ?>
				<?php if ( $t = geed_cs_menu_duration_text( $id ) ) : ?><div><dt>時間・頻度</dt><dd><?php echo esc_html( $t ); ?></dd></div><?php endif; ?>
				<?php if ( $machines ) : ?>
					<div>
						<dt>使う実機</dt>
						<dd class="menu-spec__machines">
							<?php
							$out = array();
							foreach ( $machines as $m ) {
								list( $label, $url ) = geed_cs_machine( $m );
								$out[] = $url ? '<a href="' . esc_url( $url ) . '">' . esc_html( $label ) . '</a>' : esc_html( $label );
							}
							echo implode( ' <span aria-hidden="true">→</span> ', $out );
							?>
						</dd>
					</div>
				<?php endif; ?>
				<?php if ( $plan = geed_cs_plan( get_post_meta( $id, 'geed_plan', true ) ) ) : ?><div><dt>合うプラン</dt><dd><?php echo esc_html( $plan[0] ); ?></dd></div><?php endif; ?>
				<?php if ( $sup ) : ?><div><dt>監修</dt><dd><?php echo esc_html( $sup['name'] . '（' . $sup['job'] . '）' ); ?></dd></div><?php endif; ?>
			</dl>
		</div>

		<div class="p-article__body content-page__body g-container g-narrow">
			<?php the_content(); ?>
		</div>

		<div class="p-article__extra g-container g-narrow">
		<?php if ( $cautions ) : ?>
			<section class="p-article__cautions" aria-labelledby="cautions-title">
				<h2 id="cautions-title">注意すること</h2>
				<ul class="g-list">
					<?php foreach ( $cautions as $c ) : ?><li><?php echo esc_html( $c ); ?></li><?php endforeach; ?>
				</ul>
				<p class="g-note">持病・ケガ・妊娠中・産後まもない方は、始める前に医師に相談し、来館時にスタッフへお伝えください。</p>
			</section>
		<?php endif; ?>

		<?php geed_cs_line_offer(); ?>
		<?php geed_cs_plan_box( get_post_meta( $id, 'geed_plan', true ), get_post_meta( $id, 'geed_plan_reason', true ) ); ?>

		<?php if ( $sup ) : ?>
			<aside class="p-article__sup g-callout">
				<p class="g-note">監修</p>
				<p class="p-article__sup-name"><?php echo esc_html( $sup['name'] ); ?> <small><?php echo esc_html( $sup['job'] ); ?></small></p>
				<p class="g-note"><?php echo esc_html( implode( '／', $sup['creds'] ) ); ?></p>
				<a class="g-link" href="<?php echo esc_url( home_url( $sup['url'] ) ); ?>">トレーナー紹介を見る</a>
			</aside>
		<?php endif; ?>

		<?php
		if ( $persona ) {
			geed_cs_menu_list( get_posts( array(
				'post_type'      => 'geed_menu',
				'posts_per_page' => 4,
				'post__not_in'   => array( $id ),
				'meta_key'       => 'geed_persona',
				'meta_value'     => $persona,
			) ), '同じタイプの方へのメニュー', 'menu-list--related' );
		}
		?>
		</div>
	</article>
<?php endwhile; ?>
<?php geed_codex_page_cta(); ?>
<?php get_footer();
