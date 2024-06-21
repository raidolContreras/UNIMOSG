<?php
require __DIR__ . '/view/assets/vendor/autoload.php';

use Minishlink\WebPush\VAPID;

$keys = VAPID::createVapidKeys();

$publicKey = $keys['publicKey'];
$privateKey = $keys['privateKey'];

$fileContent = "<?php\n";
$fileContent .= "\$publicKey = '" . $publicKey . "';\n";
$fileContent .= "\$privateKey = '" . $privateKey . "';\n";

file_put_contents('vapid_keys.php', $fileContent);

echo "Archivo vapid_keys.php creado con las claves.\n";
