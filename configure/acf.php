<?php

function add_custom_meta_box()
{
	global $post;
	$slug = 'home';

	if ($post && $post->post_name === $slug) {
		add_meta_box(
			'custom_title_box',
			'Заголовок страницы',
			'render_custom_title_meta_box',
			'page',
			'normal',
			'high'
		);
		add_meta_box(
			'custom_title_quest',
			'Заголовок блока с вопросами',
			'render_custom_title_meta_quest',
			'page',
			'normal',
			'high'
		);
	}
}
add_action('add_meta_boxes', 'add_custom_meta_box');

function render_custom_title_meta_box($post)
{
	$value = get_post_meta($post->ID, '_custom_title', true);
	wp_nonce_field('save_custom_title', 'custom_title_nonce');
	echo '<textarea style="width:100%;height:100px;" name="custom_title">' . esc_textarea($value) . '</textarea>';
}
function render_custom_title_meta_quest($post)
{
	$value = get_post_meta($post->ID, '_custom_title_quest', true);
	wp_nonce_field('save_custom_title_quest', 'custom_title_quest_nonce');
	echo '<textarea style="width:100%;height:100px;" name="custom_title_quest">' . esc_textarea($value) . '</textarea>';
}

function save_custom_title_meta_box($post_id)
{
	if (!isset($_POST['custom_title_nonce']) || !wp_verify_nonce($_POST['custom_title_nonce'], 'save_custom_title')) {
		return;
	}
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return;
	}
	if (isset($_POST['custom_title'])) {
		update_post_meta($post_id, '_custom_title', wp_unslash($_POST['custom_title']));
	}
	if (isset($_POST['custom_title_quest'])) {
		update_post_meta($post_id, '_custom_title_quest', wp_unslash($_POST['custom_title_quest']));
	}
}
add_action('save_post', 'save_custom_title_meta_box');

/***
 * multi-fields
 */
add_action('add_meta_boxes', function () {
	global $post;
	$slug = 'home';

	if ($post && $post->post_type === 'page' && $post->post_name === $slug) {
		add_meta_box(
			'image_text_block',
			__('Блоки с преимуществами'),
			'render_image_text_block',
			'page',
			'normal',
			'high'
		);
	}
});

function render_image_text_block($post)
{
	wp_nonce_field(basename(__FILE__), 'image_text_block_nonce');

	$fields = get_post_meta($post->ID, '_image_text_blocks', true);
	if (!$fields || !is_array($fields)) {
		$fields = [];
	}

	echo '<div id="image-text-container">';
	foreach ($fields as $index => $field) {
		$img_id = isset($field['image']) ? intval($field['image']) : '';
		$img_url = wp_get_attachment_image_src($img_id, 'thumbnail');
?>
		<div class="image-text-block">
			<h4>Блок №<?= $index + 1 ?></h4>
			<label for="_image_<?= $index ?>">Изображение:</label><br />
			<?php if (!empty($img_url)) : ?>
				<img src="<?= esc_url($img_url[0]); ?>" style="max-width:100px; background: darkgray;" alt="Превью изображения" /><br />
			<?php else : ?>
				<p>Изображение не выбрано.</p>
			<?php endif; ?>
			<input type="hidden" name="_image[]" value="<?= esc_attr($img_id); ?>" />
			<button type="button" onclick="openMediaUploader(event, <?= $index ?>)">Выбрать изображение</button><br /><br />

			<label for="_text_<?= $index ?>">Текст:</label><br />
			<textarea rows="4" cols="50" name="_text[]"><?= isset($field['text']) ? htmlspecialchars($field['text'], ENT_QUOTES) : ''; ?></textarea>

			<button type="button" class="delete-block-button" onclick="deleteBlock(this)">Удалить блок</button>
			<hr />
		</div>
	<?php
	}
	?>
	<button type="button" onclick="addNewBlock()">Добавить блок</button>
	</div>
<?php
}

add_action('save_post', function ($post_id) {
	if (
		!isset($_POST['image_text_block_nonce']) ||
		!wp_verify_nonce($_POST['image_text_block_nonce'], basename(__FILE__))
	) {
		return;
	}

	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return;
	}

	if (!current_user_can('edit_post', $post_id)) {
		return;
	}

	if (!isset($_POST['_image']) || !is_array($_POST['_image'])) {
		return;
	}

	$images = $_POST['_image'];
	$texts = $_POST['_text'] ?? [];
	$blocks = [];

	$allowed_tags = [
		'b' => [],
		'strong' => [],
		'i' => [],
		'em' => [],
		'u' => [],
		'span' => [
			'class' => [],
			'style' => []
		],
		'a' => [
			'href' => [],
			'title' => [],
			'class' => []
		],
		'br' => []
	];

	foreach ($images as $index => $image_id) {
		$image_id = sanitize_text_field($image_id);
		$text_value = isset($texts[$index]) ? wp_kses($texts[$index], $allowed_tags) : '';

		if (!empty($image_id) || !empty($text_value)) {
			$blocks[] = [
				'image' => $image_id,
				'text' => $text_value
			];
		}
	}

	if (!empty($blocks)) {
		update_post_meta($post_id, '_image_text_blocks', $blocks);
	} else {
		delete_post_meta($post_id, '_image_text_blocks');
	}
}, 10, 2);


/**
 * Telegram
 */
// Хук для инициализации страницы настроек
add_action('admin_menu', 'telegram_settings_page');
add_action('admin_init', 'telegram_settings_init');

// Создание страницы настроек
function telegram_settings_page()
{
	add_options_page(
		'Telegram Settings',               // Название страницы
		'Telegram Settings',               // Пункт меню
		'manage_options',                  // Уровень доступа
		'telegram-settings',               // Слаг страницы
		'telegram_settings_page_html'      // Функция вывода HTML
	);
}

// Регистрация настроек
function telegram_settings_init()
{
	// Регистрируем настройки
	register_setting('telegram_settings_group', 'telegram_id');
	register_setting('telegram_settings_group', 'telegram_bot_api_token');

	// Добавляем секцию
	add_settings_section(
		'telegram_settings_section',       // ID секции
		'Telegram Integration Settings',   // Заголовок секции
		null,                              // Описание (опционально)
		'telegram-settings'                // Слаг страницы
	);

	// Поле для Telegram ID
	add_settings_field(
		'telegram_id',                     // ID поля
		'Telegram ID',                     // Название поля
		'telegram_id_field_html',          // Функция вывода HTML
		'telegram-settings',               // Слаг страницы
		'telegram_settings_section'        // ID секции
	);

	// Поле для Telegram Bot API Token
	add_settings_field(
		'telegram_bot_api_token',          // ID поля
		'Telegram Bot API Token',          // Название поля
		'telegram_bot_api_token_field_html', // Функция вывода HTML
		'telegram-settings',               // Слаг страницы
		'telegram_settings_section'        // ID секции
	);
}

// Вывод поля для Telegram ID
function telegram_id_field_html()
{
	$telegram_id = get_option('telegram_id', ''); // Получаем сохранённое значение
	echo '<input type="text" name="telegram_id" value="' . esc_attr($telegram_id) . '" style="width: 400px;">';
}

// Вывод поля для Telegram Bot API Token
function telegram_bot_api_token_field_html()
{
	$telegram_bot_api_token = get_option('telegram_bot_api_token', ''); // Получаем сохранённое значение
	echo '<input type="text" name="telegram_bot_api_token" value="' . esc_attr($telegram_bot_api_token) . '" style="width: 400px;">';
}

// HTML для страницы настроек
function telegram_settings_page_html()
{
	if (!current_user_can('manage_options')) {
		return;
	}
?>
	<div class="wrap">
		<h1>Telegram Integration Settings</h1>
		<form action="options.php" method="post">
			<?php
			// Вывод полей настроек
			settings_fields('telegram_settings_group');
			do_settings_sections('telegram-settings');
			submit_button('Save Settings');
			?>
		</form>
	</div>
<?php
}
