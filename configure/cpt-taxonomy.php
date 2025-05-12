<?php

// Custom Post types & Taxonomies here
function register_custom_post_type()
{
	register_post_type(
		'questions',
		array(
			'labels' => array(
				'name'               => _x('Топ популярных вопросов сегодня', 'Post type general name', 'test'),
				'singular_name'      => _x('Пункт', 'Post type singular name', 'test'),
				'add_new'            => __('Добавить пункт', 'test'),
				'add_new_item'       => __('Добавление нового пункта', 'test'),
				'edit_item'          => __('Редактировать пункт', 'test'),
				'new_item'           => __('Новый пункт', 'test'),
				'view_item'          => __('Просмотреть пункт', 'test'),
				'search_items'       => __('Искать пункты', 'test'),
				'not_found'          => __('Пункты не найдены', 'test'),
				'not_found_in_trash' => __('Пункты в корзине не найдены', 'test'),
				'parent_item_colon'  => __('Родительский пункт:', 'test'),
				'all_items'          => __('Все пункты', 'test'),
				'archives'           => __('Архивы пунктов', 'test'),
				'attributes'         => __('Атрибуты пунктов', 'test'),
			),
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array('slug' => 'questions'),
			'capability_type'    => 'post',
			'has_archive'        => false,
			'hierarchical'       => false,
			'menu_position'      => null,
			'menu_icon'          => 'dashicons-format-gallery',
			'supports'           => array('title', 'thumbnail'),
			'show_in_rest'       => true,
		)
	);
	register_post_type(
		'vantage',
		array(
			'labels' => array(
				'name'               => _x('Преимущества', 'Post type general name', 'test'),
				'singular_name'      => _x('Пункт', 'Post type singular name', 'test'),
				'add_new'            => __('Добавить пункт', 'test'),
				'add_new_item'       => __('Добавление нового пункта', 'test'),
				'edit_item'          => __('Редактировать пункт', 'test'),
				'new_item'           => __('Новый пункт', 'test'),
				'view_item'          => __('Просмотреть пункт', 'test'),
				'search_items'       => __('Искать пункты', 'test'),
				'not_found'          => __('Пункты не найдены', 'test'),
				'not_found_in_trash' => __('Пункты в корзине не найдены', 'test'),
				'all_items'          => __('Все пункты', 'test'),
			),
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array('slug' => 'vantage'),
			'capability_type'    => 'post',
			'has_archive'        => false,
			'hierarchical'       => false,
			'menu_position'      => null,
			'menu_icon'          => 'dashicons-format-gallery',
			'supports'           => array('title', 'thumbnail'),
			'show_in_rest'       => true,
		)
	);
}
add_action('init', 'register_custom_post_type');
