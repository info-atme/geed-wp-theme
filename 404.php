<?php
/**
 * 404（v2）
 */
get_header();
geed_codex_page_hero( array(
	'en' => 'NOT FOUND',
	'ja' => 'ページが見つかりません',
	'sub' => 'お探しのページは移動したか、削除された可能性があります。',
	'bg' => 'interior-main.jpg',
) );
?>
<section class="g-sec">
	<div class="g-container g-sec__grid">
		<header class="g-head">
			<p class="g-head__en" aria-hidden="true">LINKS</p>
			<h2>よく見られているページ</h2>
		</header>
		<div class="g-sec__body">
			<ul class="g-list">
				<li><a class="g-link" href="<?php echo esc_url( home_url( '/' ) ); ?>">トップページ</a></li>
				<li><a class="g-link" href="<?php echo esc_url( home_url( '/first/' ) ); ?>">初めての方へ</a></li>
				<li><a class="g-link" href="<?php echo esc_url( home_url( '/price/' ) ); ?>">料金プラン</a></li>
				<li><a class="g-link" href="<?php echo esc_url( home_url( '/facility/' ) ); ?>">施設紹介</a></li>
				<li><a class="g-link" href="<?php echo esc_url( home_url( '/faq/' ) ); ?>">よくあるご質問</a></li>
			</ul>
		</div>
	</div>
</section>
<?php geed_codex_page_cta(); ?>
<?php get_footer();
