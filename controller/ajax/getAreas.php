<?php

    require_once "../forms.controller.php";
    require_once "../../model/forms.models.php";
    $idZone = (isset($_POST['idZone']) ? $_POST['idZone'] : null);
    $response = FormsController::ctrSearchAreas($idZone, null, null);
    echo json_encode($response);