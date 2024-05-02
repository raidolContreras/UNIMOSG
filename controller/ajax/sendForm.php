<?php

    require_once "../forms.controller.php";
    require_once "../../model/forms.models.php";
    $idObject = $_POST['idObject'];
    $estado = $_POST['estado'];
    $description = $_POST['description'];
    $importancia = $_POST['importancia'];
    $response = FormsController::ctrSendForm($idObject, $estado, $description, $importancia);
    echo $response;