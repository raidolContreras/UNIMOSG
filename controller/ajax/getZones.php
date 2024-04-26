<?php

    require_once "../forms.controller.php";
    require_once "../../model/forms.models.php";
    $idSchool = (isset($_POST['idSchool']) ? $_POST['idSchool'] : null);
    $response = FormsController::ctrSearchZones($idSchool, null, null);
    echo json_encode($response);