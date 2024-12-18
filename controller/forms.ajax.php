<?php
session_start();

require_once "forms.controller.php";
require_once "../model/forms.models.php";

if (!isset($_POST['action'])) {
    echo json_encode(['error' => 'Acción no especificada']);
    exit;
}

$action = $_POST['action'];

// Redirigir la solicitud al archivo adecuado basado en la acción
switch ($action) {
    case 'registerSchool':
    case 'searchSchool':
    case 'editSchool':
    case 'deleteSchool':
    case 'updateOrderSchool':
        require 'ajax/schools.php';
        break;

    case 'searchEdificer':
    case 'registerEdificer':
    case 'updateEdificer':
    case 'deleteEdificer':
    case 'updateOrderEdificer':
        require 'ajax/edificer.php';
        break;

    case 'searchFloor':
    case 'registerFloor':
    case 'updateFloor':
    case 'deleteFloor':
    case 'updateOrderFloor':
        require 'ajax/floor.php';
        break;

    case 'getAreasForZone':
    case 'searchArea':
    case 'registerArea':
    case 'updateArea':
    case 'deleteArea':
    case 'updateOrderArea':
        require 'ajax/areas.php';
        break;

    case 'getObjets':
    case 'searchObject':
    case 'addObject':
    case 'updateObject':
    case 'deleteObject':
    case 'updateOrderObject':
    case 'uploadObjects':
        require 'ajax/objects.php';
        break;

    case 'registerUser':
    case 'searchUser':
    case 'editUser':
    case 'suspendUser':
    case 'activateUser':
    case 'deleteUser':
        require 'ajax/users.php';
        break;

    case 'detailsCorrect':
    case 'asignarFecha':
        require 'ajax/incidents.php';
        break;

    case 'getSupervitionDays':
    case 'registerRoute':
    case 'deleteSupervisionDays':
    case 'getSupervitionAreas':
    case 'addSupervitionAreas':
    case 'deleteSupervitionArea':
        require 'ajax/supervition.php';
        break;

    default:
        echo json_encode(['error' => 'Acción no reconocida']);
}
