<?php
require __DIR__ . '/view/assets/vendor/autoload.php';

use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

// Claves VAPID
$publicVapidKey = 'BO6gY4P7chV23gMCiYcSI5d_jSbrfDHL_Ol5DmAZAv7LDYSbWxbKKbthyP3Sren1C_64SzUCzz9Du8STarG1CaI';
$privateVapidKey = 'nvP6CpfetIJuqpYFFOLcRGZz7cGJgZ6-e1o53r59A6s';

// Obtener la suscripción del cliente
$subscription = file_get_contents('php://input');
$subscription = json_decode($subscription, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(['error' => 'Invalid JSON received']);
    exit;
}

// Guardar la suscripción en un archivo JSON (en producción, usa una base de datos)
$file = 'subscriptions.json';
$subscriptions = [];
if (file_exists($file)) {
    $subscriptions = json_decode(file_get_contents($file), true);
}
$subscriptions[] = $subscription;
file_put_contents($file, json_encode($subscriptions));

echo json_encode(['success' => true]);