<?php

    require_once "../sendNotify.php";
    require_once "../forms.controller.php";
    require_once "../../model/forms.models.php";

    $notifys = FormsController::ctrSendNotify();
    foreach ($notifys as $notify) {
        $response = Notify::sendNotify($notify);
        if ($response == 'ok') {
            FormsController::ctrUpdateNotify($notify['idNotify']);
        }
    }