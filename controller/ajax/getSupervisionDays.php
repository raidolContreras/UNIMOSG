<?php

    require_once "../forms.controller.php";
    require_once "../../model/forms.models.php";
    $item = (isset($_POST['idSupervisionDays'])) ? $_POST['idSupervisionDays'] : null;
    $response = FormsController::ctrGetDaySupervision($item);
    echo json_encode($response);