<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/test/wp-load.php';
require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Вставьте ваши API-ключи для Telegram-бота
$botToken = get_option('telegram_bot_api_token');
$chatId = get_option('telegram_id');


// Проверяем, что данные были отправлены через POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получаем данные из массива POST
    $postData = $_POST;

    // Преобразуем данные в UTF-8
    foreach ($postData as $key => $value) {
        $postData[$key] = mb_convert_encoding($value, 'UTF-8', 'auto');
    }

    // Перевод названий полей на русский язык
    $translatedFields = [
        'name' => 'Имя',
        'message' => 'Сообщение',
    ];


    // Создаем экземпляр PHPMailer
    $mail = new PHPMailer(true);

    try {
        $admin_email = get_option('admin_email');
        // Настройка сервера
        $mail->isSMTP();
        $mail->Host = 'smtp.yandex.ru'; // SMTP сервер Яндекса
        $mail->SMTPAuth = true;
        $mail->Username = $admin_email; // Ваш логин Яндекс
        $mail->Password = 'ycqhdeqayvppffhg'; // Ваш пароль Яндекс
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587; // Порт SMTP сервера Яндекса

        // От кого и кому
        $mail->setFrom($admin_email, 'Test');
        $mail->addAddress($admin_email, 'Test');

        // Тема письма
        $mail->Subject = 'Форма данных';

        // Формируем тело письма из массива данных
        $body = "<h1>Данные формы</h1><ul>";
        foreach ($postData as $name => $value) {
            // Заменяем английское название поля на русское
            $translatedName = isset($translatedFields[$name]) ? $translatedFields[$name] : $name;

            $body .= "<li><strong>" . htmlspecialchars($translatedName, ENT_QUOTES, 'UTF-8') . ":</strong> " . htmlspecialchars($value, ENT_QUOTES, 'UTF-8') . "</li>";
        }
        $body .= "</ul>";

        // Устанавливаем тело письма
        $mail->isHTML(true); // Устанавливаем формат HTML
        $mail->Body = $body;
        $mail->CharSet = 'UTF-8'; // Устанавливаем кодировку

        // Отправляем письмо
        $mail->send();

        // Отправляем сообщение в Telegram
        $message = "Новая форма получена: \n\n";
        foreach ($postData as $name => $value) {
            // Заменяем английское название поля на русское
            $translatedName = isset($translatedFields[$name]) ? $translatedFields[$name] : $name;

            $message .= $translatedName . ": " . $value . "\n";
        }
        $sendMessage = file_get_contents("https://api.telegram.org/bot" . $botToken . "/sendMessage?chat_id=" . $chatId . "&text=" . urlencode($message));

        echo 'Письмо успешно отправлено';
    } catch (Exception $e) {
        echo "Ошибка отправки письма: {$mail->ErrorInfo}";
    }
} else {
    echo 'Данные не отправлены';
}
