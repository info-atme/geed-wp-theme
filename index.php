<?php get_header(); ?>
<section class="g-sec">
	<div class="g-container">
		<div class="g-narrow">
		<header class="g-head">
			<h1><?php single_post_title(); ?></h1>
		</header>
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
			<?php the_posts_pagination(); ?>
		<?php else : ?>
			<p>投稿がありません。</p>
		<?php endif; ?>
		</div>
	</div>
</section>
<?php get_footer(); ?>
