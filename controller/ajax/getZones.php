<?php

    require_once "../forms.controller.php";
    require_once "../../model/forms.models.php";
    $response = FormsController::ctrSearchZones($_POST['idSchool']);
    echo json_encode($response);