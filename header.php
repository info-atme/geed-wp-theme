<?php
$cta = geed_codex_cta();
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<header class="g-header" id="top">
	<div class="g-header__inner g-container">
		<a class="g-header__logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
			<img src="<?php echo esc_url( geed_codex_mod_image( 'geed_logo_image', 'img/logo.png' ) ); ?>" alt="GEED Body Make Up Gym" width="389" height="128">
		</a>
		<nav class="g-nav" aria-label="メインメニュー">
			<?php geed_codex_default_menu( 'g-nav__list' ); ?>
		</nav>
		<div class="g-header__cta">
			<a class="g-btn g-btn--sm g-btn--form" href="<?php echo esc_url( $cta['trial'] ); ?>" data-cta="header-form">
				<?php echo esc_html( geed_codex_mod( 'geed_header_cta1', '無料体験' ) ); ?><small><?php echo esc_html( geed_codex_mod( 'geed_header_cta1_sub', 'Webで予約' ) ); ?></small>
			</a>
			<a class="g-btn g-btn--sm g-btn--line" href="<?php echo esc_url( $cta['line'] ); ?>" data-cta="header">
				<?php echo esc_html( geed_codex_mod( 'geed_header_cta2', '無料体験' ) ); ?><small><?php echo esc_html( geed_codex_mod( 'geed_header_cta2_sub', 'LINEで予約' ) ); ?></small>
			</a>
		</div>
		<button class="g-menu-btn" type="button" aria-controls="g-drawer" aria-expanded="false" data-menu-toggle>
			<span class="g-menu-btn__bars" aria-hidden="true"></span>
			<span class="g-menu-btn__label">メニュー</span>
		</button>
	</div>
</header>
<div class="g-drawer" id="g-drawer" data-menu-drawer aria-hidden="true" inert>
	<nav aria-label="メニュー">
		<?php geed_codex_default_menu( 'g-drawer__list' ); ?>
	</nav>
	<?php get_template_part( 'template-parts/trial-actions', null, array( 'where' => 'drawer' ) ); ?>
</div>
<main class="site-main">
