<?php
session_start();

require_once "forms.controller.php";
require_once "../model/forms.models.php";
require_once "../model/logsModel.php";

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
    case 'getSupervitionDaysUser':
    case 'getSupervitionAreas':
    case 'addSupervitionAreas':
    case 'deleteSupervitionArea':
    case 'getSupervision':
    case 'editSupervision':
        require 'ajax/supervition.php';
        break;

    case 'uploadEvidence':
    case 'finalizarSupervision':
    case 'confirmCorrectObject':
    case 'getIncidents':
    case 'endIncident':
    case 'getObjectsBad':
    case 'sendMessageTelegram':
        require 'ajax/supervisor.php';
        break;

    case 'generateApiLink':
        
        require '../vendor/autoload.php';

        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../'); // Asegura la ruta correcta
        $dotenv->load(); // Cargar variables

        $TELEGRAM_BOT_TOKEN = $_ENV['TELEGRAM_BOT_TOKEN'] ?? null;

        if (!$TELEGRAM_BOT_TOKEN) {
            die(json_encode(['error' => 'TELEGRAM_BOT_TOKEN no está definido en el archivo .env']));
        }

        $apiLink = "https://api.telegram.org/bot{$TELEGRAM_BOT_TOKEN}/getUpdates";
        echo json_encode(['apiLink' => $apiLink]);
        break;

    default:
        echo json_encode(['error' => 'Acción no reconocida']);
}
