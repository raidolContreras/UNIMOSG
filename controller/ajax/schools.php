<?php

switch ($_POST['action']) {
    case 'registerSchool':
        $nameSchool = $_POST['nameSchool'];
        echo FormsController::ctrRegisterSchool($nameSchool);
        break;

    case 'searchSchool':
        $value = isset($_POST[$_POST['action']]) ? $_POST[$_POST['action']] : null;
        $item =isset($_POST[$_POST['action']]) ? 'idSchool' : null;
        echo json_encode(FormsController::ctrSearchSchools($item, $value));
        break;

    case 'editSchool':
        $data = [
            "nameSchool" => $_POST['nameSchool'],
            "idSchool" => $_POST['idSchool']
        ];
        echo FormsController::ctrEditSchool($data);
        break;

    case 'deleteSchool':
        $idSchool = $_POST[$_POST['action']];
        echo FormsController::ctrDeleteSchool($idSchool);
        break;

    case 'updateOrderSchool':
        $order = $_POST['order'];
        foreach ($order as $item) {
            $idSchool = $item['id'];
            $position = $item['position'];
            
            FormsController::ctrUpdateOrderSchool($position, $idSchool);
        }
        echo 'ok';
        break;

    default:
        echo json_encode(['error' => 'Acción de escuela no válida']);
}
