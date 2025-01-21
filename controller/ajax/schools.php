<?php

$userId = $_SESSION['idUser'];

switch ($_POST['action']) {
    case 'registerSchool':
        $nameSchool = $_POST['nameSchool'];
        $chatId = $_POST['chatId'];
        $response = FormsController::ctrRegisterSchool($nameSchool, $chatId);
        echo $response;
        if ($response == 'ok') {
            Logs::createLogs($userId, 'registerSchool', 'Registro de un nuevo plantel: '.$nameSchool);
        }
        break;

    case 'searchSchool':
        $value = isset($_POST[$_POST['action']]) ? $_POST[$_POST['action']] : null;
        $item =isset($_POST[$_POST['action']]) ? 'idSchool' : null;
        echo json_encode(FormsController::ctrSearchSchools($item, $value));
        break;

    case 'editSchool':
        $data = [
            "nameSchool" => $_POST['nameSchool'],
            "chatId" => $_POST['chatId'],
            "idSchool" => $_POST['idSchool']
        ];
        $response = FormsController::ctrEditSchool($data);
        echo $response;
        if ($response == 'ok') {
            Logs::createLogs($userId, 'editSchool', 'Edición de un plantel: '.$_POST['nameSchool']);
        }
        break;

    case 'deleteSchool':
        $idSchool = $_POST[$_POST['action']];
        $response = FormsController::ctrDeleteSchool($idSchool);
        if ($response == 'ok') {
            Logs::createLogs($userId, 'deleteSchool', 'Eliminación de un plantel: '.$idSchool);
        }
        echo $response;
        break;

    case 'updateOrderSchool':
        if (isset($_POST['order'])) {
            $order = $_POST['order'];
            foreach ($order as $item) {
                $idSchool = $item['id'];
                $position = $item['position'];
                
                FormsController::ctrUpdateOrderSchool($position, $idSchool);
            }
        }
        echo 'ok';
        break;

    default:
        echo json_encode(['error' => 'Acción de escuela no válida']);
}
