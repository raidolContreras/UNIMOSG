<?php
session_start();

require_once "forms.controller.php";
require_once "../model/forms.models.php";

if (!isset($_POST['action'])) {
    echo json_encode(['error' => 'Acción no especificada']);
    exit;
}

$action = $_POST['action'];

switch ($action) {
    case 'getSchools':
        $item = (isset($_POST['idSchool']) && $_POST['idSchool'] != 0) ? 'idSchool' : null;
        //si es 0 tambien poner null
        $value =(isset($_POST['idSchool']) && $_POST['idSchool'] != 0) ? $_POST['idSchool'] : null; 
        echo json_encode(FormsController::ctrSearchSchools($item, $value));
        break;
}