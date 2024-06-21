<?php
require __DIR__ . '/view/assets/vendor/autoload.php';

use Minishlink\WebPush\VAPID;

$keys = VAPID::createVapidKeys();

echo "Clave Pública: " . $keys['publicKey'] . "\n";
echo "Clave Privada: " . $keys['privateKey'] . "\n";
