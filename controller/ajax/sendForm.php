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
$filesData = json_decode($_POST['filesData'], true);

// Manejar la subida de archivos
$uploadDir = '../../view/evidences/';
$uploadedFiles = [];

if (!empty($_FILES['files']['name'][0])) {
    foreach ($_FILES['files']['name'] as $key => $name) {
        $tmpName = $_FILES['files']['tmp_name'][$key];
        $fileName = basename($name);
        $targetFilePath = $uploadDir . $fileName;

        if (move_uploaded_file($tmpName, $targetFilePath)) {
            $uploadedFiles[] = [
                'name' => $fileName,
                'type' => mime_content_type($targetFilePath)
            ];
        }
    }
}

// Aquí guardamos la información de los archivos como JSON en la base de datos, archivo, etc.
$filesJson = json_encode($uploadedFiles);

// Puedes hacer algo con $filesJson, como guardarlo en la base de datos, enviarlo en una respuesta, etc.

$response = FormsController::ctrSendForm($idObject, $estado, $description, $importancia, $filesJson);
if ($response == 'ok') {
    $data = array(
        'title' => 'Nueva incidencia (' . mb_strtoupper($importancia) . ')',
        'body' => 'Hay una nueva incidencia en '. $schoolName,
        'url' => 'https://unimosg.contreras-flota.click/school&idSchool=' . $idSchool
    );
    FormsController::ctrNewNotify($data);
}
echo $response;
