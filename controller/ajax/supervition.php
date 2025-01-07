<?php

$userId = $_SESSION['idUser'];

switch ($_POST['action']) {
    case 'getSupervitionDays':
        $getSupervitionDays = FormsController::ctrGetSupervitionDays(null);
        echo json_encode($getSupervitionDays);
        break;

    case 'registerRoute':
        // Recibir los datos desde el POST
        $supervisor = $_POST['recorridoSupervisor'];
        $plantel = $_POST['recorridoPlantel'];
        $edificio = $_POST['recorridoEdificio'];
        $piso = $_POST['recorridoPiso'];
        $zona = $_POST['recorridoZona'];
        $area = $_POST['recorridoArea'];
        $day = $_POST['recorridoDia'];
        $time = $_POST['recorridoHora'];
    
        // Construir el array de datos según las columnas de tu tabla en la BD
        $data = array(
            'idSupervisor' => $supervisor,
            'idSchool' => $plantel,
            'idEdificers' => $edificio,
            'idFloor' => $piso,
            'zone' => $zona,
            'idArea' => $area,
            'day' => $day,
            'supervisionTime' => $time
        );
    
        // Realizar el registro a través del controlador
        $response = FormsController::ctrAddSupervition($data);
    
        // Devolver la respuesta al AJAX
        if ($response == 'ok') {
            echo 'ok';
        } else {
            echo 'error';
        }
    
        break;
        
    case 'deleteSupervisionDays':
        $idSupervisionDays = $_POST['idSupervisionDays'];
        $response = FormsController::ctrDeleteSupervisionDays($idSupervisionDays);
        if ($response == 'ok') {
            Logs::createLogs($userId, 'deleteSupervisionDays', 'Eliminación de un día de supervisión: '.$idSupervisionDays);
        }
        echo $response;
        break;

    case 'getSupervitionDaysUser':
        $userId = $_SESSION['idUser'];
        $response = FormsController::ctrGetSupervitionDaysUser($userId);
        echo json_encode($response);
        break;

    case 'getSupervitionAreas':
        $responce = FormsController::ctrGetSupervitionAreas();
        echo json_encode($responce);
        break;

    case 'addSupervitionAreas':
        $title = $_POST['title'];
        $supervisor = $_POST['supervisor'];
        $plantel = $_POST['plantel'];
        $edificio = $_POST['edificio'];
        $piso = $_POST['piso'];
        $zona = $_POST['zona'];
        $area = $_POST['area'];
        $day = $_POST['day'];
        $time = $_POST['time'];

        $data = array(
            'title' => $title,
            'idSupervisor' => $supervisor,
            'idSchool' => $plantel,
            'idEdificers' => $edificio,
            'idFloor' => $piso,
            'zone' => $zona,
            'idArea' => $area,
            'day' => $day,
            'time' => $time
        );

        $responce = FormsController::ctrAddSupervitionAreas($data);
        echo $responce;
        break;

    case 'deleteSupervitionArea':
        $idSupervisionAreas = $_POST['eventId'];
        $responce = FormsController::ctrDeleteSupervitionArea($idSupervisionAreas);
        echo $responce;
        break;

    default:
        echo json_encode(['error' => 'Acción de supervición no válida']);
}
