<?php

    require_once "../forms.controller.php";
    require_once "../../model/forms.models.php";
    $idSchool = $_POST['idSchool'];
    $importancia = $_POST['importancia'];
    $response = FormsController::ctrSearchSolicitudes($idSchool,$importancia);
    echo json_encode($response);