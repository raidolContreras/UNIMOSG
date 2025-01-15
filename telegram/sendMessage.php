<?php
require_once __DIR__ . '../vendor/autoload.php';

use Dotenv\Dotenv;

// Cargar las variables de entorno
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$botToken = $_ENV['TELEGRAM_BOT_TOKEN'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $chatId = $_ENV['ChatID'];

    // Validar si se envió un mensaje
    $message = $_POST['message'] ?? null;

    // Validar si se subió una imagen
    $image = $_FILES['image']['tmp_name'] ?? null;

    if ($chatId && ($message || $image)) {
        if ($image) {
            sendPhoto($botToken, $chatId, $image, $message);
        } else {
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
        'parse_mode' => 'HTML', // O 'MarkdownV2' para mayor control
        'disable_web_page_preview' => false // Permite la vista previa de enlaces
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode === 200) {
        echo json_encode(['status' => 'success', 'message' => 'Mensaje enviado correctamente.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error al enviar el mensaje.', 'response' => $response]);
    }
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
        'parse_mode' => 'HTML' // Permite formato en la descripción
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode === 200) {
        echo json_encode(['status' => 'success', 'message' => 'Imagen enviada correctamente.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error al enviar la imagen.', 'response' => $response]);
    }
}
?>
