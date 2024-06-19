<?php
require __DIR__ . '/../view/assets/vendor/autoload.php';

use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

class Notify {

    static public function sendNotify($notify) {
        
        // Claves VAPID
        $publicVapidKey = 'BO6gY4P7chV23gMCiYcSI5d_jSbrfDHL_Ol5DmAZAv7LDYSbWxbKKbthyP3Sren1C_64SzUCzz9Du8STarG1CaI';
        $privateVapidKey = 'nvP6CpfetIJuqpYFFOLcRGZz7cGJgZ6-e1o53r59A6s';

        // Leer las suscripciones almacenadas
        $file = '../../subscriptions.json';
        $subscriptions = [];
        if (file_exists($file)) {
            $subscriptions = json_decode(file_get_contents($file), true);
        }

        $webPush = new WebPush([
            'VAPID' => [
                'subject' => 'mailto:ocontreras@unimontrer.edu.mx',
                'publicKey' => $publicVapidKey,
                'privateKey' => $privateVapidKey,
            ],
        ]);

        $payload = json_encode([
            'title' => $notify['title'],
            'body' => $notify['body'],
            'url' => $notify['url']
        ]);

        foreach ($subscriptions as $sub) {
            $subscription = Subscription::create([
                'endpoint' => $sub['endpoint'],
                'publicKey' => $sub['keys']['p256dh'],
                'authToken' => $sub['keys']['auth'],
            ]);

            $webPush->queueNotification(
                $subscription,
                $payload
            );
        }

        // Enviar las notificaciones
        foreach ($webPush->flush() as $report) {
            $endpoint = $report->getRequest()->getUri()->__toString();
            if ($report->isSuccess()) {
                return 'ok';
            } else {
                return "[x] Notificación fallida para {$endpoint}: {$report->getReason()}\n";
            }
        }

    }
}