<?php
$cta = geed_codex_cta();
// TOP は FAQ の最終CTAと重複、/contact/ はフォームそのものなので申込帯と固定バーを出さない（renewal_spec.md §4）
$is_contact = is_page_template( 'page-contact.php' ) || is_page( 'contact' );
$show_apply = ! is_front_page() && ! $is_contact && empty( $GLOBALS['geed_has_page_cta'] ); // 下層は geed_codex_page_cta() があればそちらを使う
$posts_page = (int) get_option( 'page_for_posts' );
?>
</main>
<footer class="g-footer" id="footer">
	<?php if ( $show_apply ) : ?>
		<div class="g-footer__apply">
			<div class="g-container">
				<h2>無料体験のお申し込み</h2>
				<p>約60分。WebかLINEで希望の日時を送ってください。見学だけでも大丈夫です。</p>
				<?php get_template_part( 'template-parts/trial-actions', null, array( 'where' => 'footer' ) ); ?>
			</div>
		</div>
	<?php endif; ?>
	<div class="g-footer__main g-container">
		<div class="g-footer__brand">
			<img src="<?php echo geed_codex_asset( 'img/logo-white.png' ); ?>" alt="GEED Body Make Up Gym" width="569" height="187" loading="lazy">
			<address><?php echo esc_html( $cta['address'] ); ?></address>
			<p>TEL：<a href="<?php echo esc_url( $cta['tel_link'] ); ?>"><?php echo esc_html( $cta['tel'] ); ?></a></p>
			<p>営業時間：<?php echo esc_html( $cta['hours'] ); ?><br><?php echo esc_html( $cta['closed'] ); ?></p>
		</div>
		<nav class="g-footer__menu" aria-label="フッターメニュー">
			<h2 class="g-footer__label">メニュー</h2>
			<?php geed_codex_default_menu( 'g-footer__list' ); ?>
		</nav>
		<div class="g-footer__menu">
			<h2 class="g-footer__label">その他</h2>
			<ul>
				<?php if ( $posts_page ) : ?><li><a href="<?php echo esc_url( get_permalink( $posts_page ) ); ?>">コラム</a></li><?php endif; ?>
				<?php if ( post_type_exists( 'news' ) && wp_count_posts( 'news' )->publish ) : ?><li><a href="<?php echo esc_url( get_post_type_archive_link( 'news' ) ); ?>">お知らせ</a></li><?php endif; ?>
				<li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">お問い合わせ</a></li>
				<li><a href="<?php echo esc_url( home_url( '/privacypolicy/' ) ); ?>">プライバシーポリシー</a></li>
				<li><a href="<?php echo esc_url( $cta['instagram'] ); ?>" target="_blank" rel="noopener">Instagram</a></li>
			</ul>
		</div>
	</div>
	<div class="g-footer__bottom g-container">
		<p>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php echo esc_html( geed_codex_mod( 'geed_footer_text', 'GEED Body Make Up Gym. All Rights Reserved.' ) ); ?></p>
		<a href="#top">ページトップへ</a>
	</div>
</footer>
<?php if ( ! $is_contact ) : ?>
	<div class="g-fixbar">
		<a class="g-btn g-btn--form" href="<?php echo esc_url( $cta['trial'] ); ?>" data-cta="fixed-form"><?php echo esc_html( geed_codex_mod( 'geed_fixed_cta1', 'Webで体験予約' ) ); ?></a>
		<a class="g-btn g-btn--line" href="<?php echo esc_url( $cta['line'] ); ?>" data-cta="fixed"><?php echo esc_html( geed_codex_mod( 'geed_fixed_cta2', 'LINEで体験予約' ) ); ?></a>
	</div>
<?php endif; ?>
<?php wp_footer(); ?>
</body>
</html>
