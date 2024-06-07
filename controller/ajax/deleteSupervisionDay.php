<?php

    require_once "../forms.controller.php";
    require_once "../../model/forms.models.php";
    $idSupervisionDays = $_POST['deleteSupervisionDays'];
    $response = FormsController::ctrDeleteSupervisionDays($idSupervisionDays);
    echo json_encode($response);