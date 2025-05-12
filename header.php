<!DOCTYPE HTML>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>
	<div id="page" class="site">

		<header id="header" class="header">
			<div class="container">
				<div class="header__wrap flex-center-sb">
					<div class="logo flex-center-center">
						<?php if (has_custom_logo()):
							the_custom_logo(); ?>
						<?php else: ?>
							<a href="<?php echo home_url(); ?>"><?php bloginfo('name'); ?></a>
						<?php endif; ?>
						<?php
						$name = get_bloginfo('name');
						$first_three = mb_substr($name, 0, 3);
						$rest = mb_substr($name, 3);
						?>
						<span class="site-name"><span class="highlight"><?php echo $first_three; ?></span><?php echo $rest; ?></span>
					</div>
					<!-- .logo -->
					<?php wp_nav_menu(array(
						'theme_location' => 'menu-header',
						'menu_id' => '',
						'container' => false,
						'menu_class' => 'header__menu flex-center-center',
					)); ?>

					<div class="login">
						<?php $profile_url = get_edit_profile_url(get_current_user_id()); ?>

						<a href="<?php echo $profile_url; ?>" class="login__btn" id="login">Войти <object type="image/svg+xml" data="<?php echo get_template_directory_uri() . '/inc/img/next.svg' ?>">Your browser does not support SVG</object></a>
						<a href="#" class="header__menu--mobile">
							<object style="width: 28px;" type="image/svg+xml" data="<?php echo get_template_directory_uri() . '/inc/img/menu.svg' ?>">Your browser does not support SVG</object>
						</a>
					</div>
				</div>
		</header><!-- #header -->
		<div class="mobil_menu">
			<span class="close-button">X</span>
			<?php wp_nav_menu(array(
				'theme_location' => 'menu-header',
				'menu_id' => '',
				'container' => false,
				'menu_class' => 'header__menu--mobile',
			)); ?>
		</div>
		<main id="content" class="content">