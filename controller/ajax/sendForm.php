<?php

    require_once "../forms.controller.php";
    require_once "../../model/forms.models.php";
    $idObject = $_POST['idObject'];
    $estado = $_POST['estado'];
    $description = $_POST['description'];
    $importancia = $_POST['importancia'];
    $schoolName = $_POST['schoolName'];
    $zoneName = $_POST['zoneName'];
    $areaName = $_POST['areaName'];
    $idSchool = $_POST['idSchool'];
    $response = FormsController::ctrSendForm($idObject, $estado, $description, $importancia);
    if ($response == 'ok') {
        $data = array(
            'title' => 'Nueva incidencia (' . mb_strtoupper($importancia) . ')',
            'body' => 'Hay una nueva incidencia en '. $schoolName,
            'url' => 'https://unimosg.contreras-flota.click/school&idSchool=' . $idSchool
        );
        FormsController::ctrNewNotify($data);
    }
    echo $response;