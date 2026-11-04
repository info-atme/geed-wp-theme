<?php
/**
 * コラム一覧（/column/ = 投稿ページ）。旧キャンペーン投稿（未分類）は pre_get_posts で除外
 */
get_header();
geed_codex_page_hero( array(
	'slug' => 'column',
	'en'   => 'COLUMN',
	'ja'   => 'コラム',
	'sub'  => 'ジムが初めての方・女性・ダイエット中の方へ。<br>通い方とトレーニングのコツをまとめています。',
) );
$cats = get_categories( array( 'exclude' => array( (int) get_option( 'default_category' ) ), 'hide_empty' => true ) );
?>
<section class="g-sec">
	<div class="g-container">
		<div class="g-narrow">
		<?php if ( $cats ) : ?>
			<ul class="g-list">
				<?php foreach ( $cats as $c ) : ?>
					<li><a href="<?php echo esc_url( get_category_link( $c ) ); ?>"><?php echo esc_html( $c->name ); ?></a></li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
		<?php if ( have_posts() ) : ?>
			<ul class="g-column">
				<?php while ( have_posts() ) : the_post(); ?>
				<li>
					<a class="g-column__link" href="<?php the_permalink(); ?>">
						<time lang="en" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date( 'Y.m.d' ) ); ?></time>
						<span><?php the_title(); ?><br><small><?php echo esc_html( wp_trim_words( get_the_excerpt(), 70 ) ); ?></small></span>
					</a>
				</li>
				<?php endwhile; ?>
			</ul>
		<?php else : ?>
			<p>記事は準備中です。</p>
		<?php endif; ?>
		<?php the_posts_pagination(); ?>
		</div>
	</div>
</section>
<?php geed_codex_page_cta(); ?>
<?php get_footer();
