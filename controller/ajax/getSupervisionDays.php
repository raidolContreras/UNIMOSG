<?php

    require_once "../forms.controller.php";
    require_once "../../model/forms.models.php";
    $item = (isset($_POST['idSupervisionDays'])) ? $_POST['idSupervisionDays'] : null;
    $user = (isset($_POST['user'])) ? $_POST['user'] : null;
    $response = FormsController::ctrGetDaySupervision($item, $user);
    echo json_encode($response);