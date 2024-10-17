<?php
require_once ('../vendor/autoload.php'); // if you use Composer
// require_once('ultramsg.class.php'); // if you download ultramsg.class.php

// Cargar las variables del archivo .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../');
$dotenv->load();

// Obtener las variables de entorno con $_ENV
$ultramsg_token = $_ENV['TOKEN'];
$instance_id = $_ENV['INSTANCE_ID'];

// Inicializar la API de UltraMsg
$client = new UltraMsg\WhatsAppApi($ultramsg_token, $instance_id);

$to = "+5214435398291"; 
$body = "Pokemon";

// Enviar el mensaje
$api = $client->sendChatMessage($to, $body);

if (isset($api['sent'])) {
    echo "Message sent successfully!";
} else {
    echo "Error sending message: ". $api['Error'];
}
