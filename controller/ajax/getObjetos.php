<?php

    require_once "../forms.controller.php";
    require_once "../../model/forms.models.php";
    $idArea = (isset($_POST['idArea']) ? $_POST['idArea'] : null);
    $response = FormsController::ctrSearchObjects($idArea, null, null);
    echo json_encode($response);