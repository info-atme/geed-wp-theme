<?php get_header(); ?>
<?php while ( have_posts() ) : the_post(); ?>
	<article <?php post_class( 'g-sec' ); ?>>
		<div class="g-container">
		<div class="g-narrow">
			<h1 class="g-head"><?php the_title(); ?></h1>
			<div class="g-prose">
				<?php the_content(); ?>
			</div>
		</div>
		</div>
	</article>
<?php endwhile; ?>
<?php get_footer(); ?>
