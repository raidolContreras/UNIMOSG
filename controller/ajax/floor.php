<?php

switch ($_POST['action']) {
    
    case 'registerFloor':
        $floorName = $_POST['floorName'];
        $idEdificers = $_POST['idEdificers'];
        echo FormsController::ctrRegisterFloor($floorName, $idEdificers);
        break;

    case 'searchFloor':
        if (isset($_POST['searchFloor'])) {
            $value = $_POST['searchFloor'];
            $item = 'idFloor';
        } else {
            $value = $_POST['idEdificers'];
            $item = 'floor_idEdificer';
        }
        echo json_encode(FormsController::ctrSearchFloors($item, $value));
        break;
    
    case 'updateFloor':
        $data = [
            'nameFloor' => $_POST['floorName'],
            'idFloor' => $_POST['idFloor']
        ];
        echo FormsController::ctrUpdateFloor($data);
        break;
    
    case 'deleteFloor':
        echo FormsController::ctrDeleteFloor($_POST['idFloor']);
        break;

    case 'updateOrderFloor':
        if (isset($_POST['order'])) {
            $order = $_POST['order'];
            foreach ($order as $item) {

                $idFloor = $item['id'];
                $position = $item['position'];
                
                FormsController::ctrUpdateOrderFloor($position, $idFloor);
            }
        }
        echo 'ok';
        break;

}
