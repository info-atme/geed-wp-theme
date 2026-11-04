<?php
/**
 * コラム記事（v2 の枠）。本文 .content-page__body の中身は旧 main.css のスタイルのまま（63本の記事が依存しているため）。
 * 枠: 見出し（パンくず・カテゴリ・H1・日付と監修）→ アイキャッチ → この記事の答え → 目次 → 本文
 *     → 監修者 → 関連メニュー → 申込（geed_codex_page_cta）→ 関連記事
 * 本文の加工（監修行の除去・結論の切り出し・H2 の id・中盤 CTA）は inc/article.php
 */
get_header();
?>
<?php while ( have_posts() ) : the_post(); ?>
	<?php
	// 目次と結論ボックスは本文の解析結果から作るので、先に本文を組み立てる
	$geed_body = apply_filters( 'the_content', get_the_content() );
	$geed_body = str_replace( ']]>', ']]&gt;', $geed_body );
	$geed_sup  = geed_article_supervisor();
	?>
	<article <?php post_class( 'p-article' ); ?>>
		<header class="p-article__head g-container g-narrow">
			<?php geed_codex_breadcrumb(); ?>
			<p class="p-article__meta">
				<?php foreach ( get_the_category() as $c ) : ?>
					<a href="<?php echo esc_url( get_category_link( $c->term_id ) ); ?>"><?php echo esc_html( $c->name ); ?></a>
				<?php endforeach; ?>
			</p>
			<h1><?php the_title(); ?></h1>
			<p class="p-article__byline">
				<span>公開 <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>" lang="en"><?php echo esc_html( get_the_date( 'Y.m.d' ) ); ?></time></span>
				<?php if ( get_the_modified_date( 'Ymd' ) > get_the_date( 'Ymd' ) ) : // 情報の鮮度を読み手と検索エンジンに示す ?>
					<span>最終更新 <time datetime="<?php echo esc_attr( get_the_modified_date( 'c' ) ); ?>" lang="en"><?php echo esc_html( get_the_modified_date( 'Y.m.d' ) ); ?></time></span>
				<?php endif; ?>
				<span>監修 <a href="#supervisor"><?php echo esc_html( $geed_sup['name'] ); ?></a>（<?php echo esc_html( $geed_sup['job'] ); ?>）</span>
			</p>
		</header>
		<?php $geed_video = get_post_meta( get_the_ID(), 'geed_video_url', true ); ?>
		<?php if ( $geed_video ) : ?>
			<div class="p-article__media content-page__video g-container g-narrow"><?php echo wp_oembed_get( $geed_video ) ?: '<a href="' . esc_url( $geed_video ) . '" target="_blank" rel="noopener">動画を見る</a>'; ?></div>
		<?php elseif ( has_post_thumbnail() ) : ?>
			<figure class="p-article__media content-page__eyecatch g-container g-narrow"><?php the_post_thumbnail( 'large' ); ?><?php geed_cs_eyecatch_caption(); ?></figure>
		<?php endif; ?>
		<div class="p-article__lead g-container g-narrow">
			<?php geed_article_answer(); ?>
			<?php geed_article_toc(); ?>
		</div>
		<div class="p-article__body content-page__body g-container g-narrow">
			<?php echo $geed_body; // the_content フィルタ済み ?>
		</div>

		<div class="p-article__extra g-container g-narrow">
			<?php geed_article_supervisor_box(); ?>
			<?php geed_cs_post_related( get_the_ID() ); ?>
		</div>

		<?php geed_codex_page_cta( 'post_end', 'この記事の内容を、<br>スタッフと一緒に試せます。' ); ?>

		<?php
		/* 関連記事3件 */
		$related_cats = wp_list_pluck( get_the_category(), 'term_id' );
		$related = new WP_Query( array(
			'post_type'      => 'post',
			'posts_per_page' => 3,
			'post__not_in'   => array( get_the_ID() ),
			'category__in'   => $related_cats ?: array(),
			'category__not_in' => array( (int) get_option( 'default_category' ) ),
			'orderby'        => 'date', // ランダムだとキャッシュと内部リンクが安定しない
			'order'          => 'DESC',
		) );
		if ( $related->have_posts() ) : ?>
			<section class="g-sec g-sec--surface" aria-labelledby="related-title">
				<div class="g-container g-sec__grid">
					<header class="g-head">
						<p class="g-head__en" aria-hidden="true">RELATED</p>
						<h2 id="related-title">関連する記事</h2>
					</header>
					<div class="g-sec__body">
						<ul class="p-related">
							<?php while ( $related->have_posts() ) : $related->the_post(); ?>
								<li>
									<a href="<?php the_permalink(); ?>">
										<?php if ( has_post_thumbnail() ) : ?>
											<?php the_post_thumbnail( 'medium', array( 'loading' => 'lazy' ) ); ?>
										<?php else : ?>
											<span class="p-related__noimg" aria-hidden="true"></span>
										<?php endif; ?>
										<span class="p-related__text">
											<time datetime="<?php echo esc_attr( get_the_date( 'Y-m-d' ) ); ?>" lang="en"><?php echo esc_html( get_the_date( 'Y.m.d' ) ); ?></time>
											<span><?php the_title(); ?></span>
										</span>
									</a>
								</li>
							<?php endwhile; ?>
						</ul>
					</div>
				</div>
			</section>
		<?php endif; wp_reset_postdata(); ?>
	</article>
<?php endwhile; ?>
<?php get_footer();
