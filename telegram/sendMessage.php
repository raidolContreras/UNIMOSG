<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

// Cargar las variables de entorno
$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$botToken = $_ENV['TELEGRAM_BOT_TOKEN'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $chatId = $_POST['chatId'];
    $message = $_POST['message'] ?? null;
    $image = $_FILES['image']['tmp_name'] ?? null;

    if ($chatId && ($message || $image)) {
        if ($image) {
            // Comprimir imagen antes de enviarla
            $compressedImage = compressImage($image, $image . '_compressed.jpg', 70);
            // fastcgi_finish_request(); // Permite continuar en segundo plano
            sendPhoto($botToken, $chatId, $compressedImage, $message);
        } else {
            // fastcgi_finish_request();
            sendMessage($botToken, $chatId, $message);
        }
    } else {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Faltan parámetros: chat_id, mensaje o imagen.']);
    }
}

/**
 * Envía un mensaje de texto con formato enriquecido.
 */
function sendMessage($token, $chatId, $message) {
    $url = "https://api.telegram.org/bot{$token}/sendMessage";

    $postData = [
        'chat_id' => $chatId,
        'text' => $message,
        'parse_mode' => 'HTML',
        'disable_web_page_preview' => false
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Connection: Keep-Alive']);

    $response = curl_exec($ch);
    curl_close($ch);

    echo json_encode(['status' => 'success', 'message' => 'Mensaje enviado correctamente.']);
}

/**
 * Envía una imagen con un mensaje opcional.
 */
function sendPhoto($token, $chatId, $imagePath, $caption = '') {
    $url = "https://api.telegram.org/bot{$token}/sendPhoto";

    $postData = [
        'chat_id' => $chatId,
        'photo' => new CURLFile($imagePath),
        'caption' => $caption,
        'parse_mode' => 'HTML'
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Connection: Keep-Alive']);
    curl_setopt($ch, CURLOPT_TCP_FASTOPEN, true);

    $response = curl_exec($ch);
    curl_close($ch);

    echo json_encode(['status' => 'success', 'message' => 'Imagen enviada correctamente.']);
}

/**
 * Comprime una imagen antes de enviarla a Telegram.
 */
function compressImage($source, $destination, $quality) {
    $info = getimagesize($source);

    if ($info['mime'] == 'image/jpeg') {
        $image = imagecreatefromjpeg($source);
        imagejpeg($image, $destination, $quality);
    } elseif ($info['mime'] == 'image/png') {
        $image = imagecreatefrompng($source);
        imagepng($image, $destination, round(9 * ($quality / 100)));
    }
    
    imagedestroy($image);
    return $destination;
}
