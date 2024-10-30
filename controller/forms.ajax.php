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

    default:
        echo json_encode(['error' => 'Acción no reconocida']);
}
