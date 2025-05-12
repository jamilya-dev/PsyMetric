<?php

// MENUS
function _custom_theme_register_menu()
{
	register_nav_menus(
		array(
			'menu-header' => __('Меню в шапке'),
		)
	);
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action('init', '_custom_theme_register_menu');

function custom_setup()
{
	// Images
	add_theme_support('post-thumbnails');

	// Title tags
	add_theme_support('title-tag');

	// Languages
	load_theme_textdomain('textdomaintomodify', get_template_directory() . '/languages');

	// HTML 5 - Example : deletes type="*" in scripts and style tags
	add_theme_support('html5', ['script', 'style']);

	// Remove SVG and global styles
	remove_action('wp_enqueue_scripts', 'wp_enqueue_global_styles');
	remove_action('wp_body_open', 'wp_global_styles_render_svg_filters');

	// Remove wp_footer actions which add's global inline styles
	remove_action('wp_footer', 'wp_enqueue_global_styles', 1);

	// Remove render_block filters which adds unnecessary stuff
	remove_filter('render_block', 'wp_render_duotone_support');
	remove_filter('render_block', 'wp_restore_group_inner_container');
	remove_filter('render_block', 'wp_render_layout_support_flag');

	// Remove useless WP image sizes
	remove_image_size('1536x1536');
	remove_image_size('2048x2048');
}
add_action('after_setup_theme', 'custom_setup');

// remove default image sizes to avoid overcharging server - comment line if you need size
function remove_default_image_sizes($sizes)
{
	unset($sizes['large']);
	unset($sizes['medium']);
	unset($sizes['medium_large']);
	return $sizes;
}
add_filter('intermediate_image_sizes_advanced', 'remove_default_image_sizes');

// disabling big image sizes scaled
add_filter('big_image_size_threshold', '__return_false');


// Move Yoast to bottom
function yoasttobottom()
{
	return 'low';
}
add_filter('wpseo_metabox_prio', 'yoasttobottom');

// Remove WP Emoji
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action('admin_print_scripts', 'print_emoji_detection_script');
remove_action('admin_print_styles', 'print_emoji_styles');

// delete wp-embed.js from footer
function my_deregister_scripts()
{
	wp_deregister_script('wp-embed');
}
add_action('wp_footer', 'my_deregister_scripts');

// delete jquery migrate
function dequeue_jquery_migrate(&$scripts)
{
	if (!is_admin()) {
		$scripts->remove('jquery');
		$scripts->add('jquery', 'https://code.jquery.com/jquery-3.6.1.min.js', null, null, true);
	}
}
add_filter('wp_default_scripts', 'dequeue_jquery_migrate');

// svg
add_filter('upload_mimes', 'svg_upload_allow');

# Добавляет SVG в список разрешенных для загрузки файлов.
function svg_upload_allow($mimes)
{
	$mimes['svg'] = 'image/svg+xml';

	return $mimes;
}

add_filter('wp_check_filetype_and_ext', 'fix_svg_mime_type', 10, 5);

# Исправление MIME типа для SVG файлов.
function fix_svg_mime_type($data, $file, $filename, $mimes, $real_mime = '')
{

	$dosvg = ('.svg' === strtolower(substr($filename, -4)));

	// mime тип был обнулен, поправим его
	// а также проверим право пользователя
	if ($dosvg) {
		// разрешим
		if (current_user_can('manage_options')) {

			$data['ext'] = 'svg';
			$data['type'] = 'image/svg+xml';
		}
		// запретим
		else {
			$data['ext'] = false;
			$data['type'] = false;
		}
	}
	return $data;
}
// svg end
