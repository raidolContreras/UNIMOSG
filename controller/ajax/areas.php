<?php

switch ($_POST['action']) {
    
    case 'getAreasForZone':
        $zone = $_POST['zone'];
        $idFloor = $_POST['idFloor'];
        echo json_encode(FormsController::ctrGetAreasForZone($zone, $idFloor));
        break;
    case 'searchArea':
            $value = $_POST['searchArea'];
            $item = 'idArea';
        echo json_encode(FormsController::ctrSearchAreas($item, $value));
        break;

    case 'registerArea':
        $zone = $_POST['zone'];
        $areaName = $_POST['areaName'];
        $idFloor = $_POST['idFloor'];
        echo FormsController::ctrRegisterArea($zone, $areaName, $idFloor);
        break;

    case 'updateArea':
        $data = [
            'nameArea' => $_POST['areaName'],
            'idArea' => $_POST['idArea']
        ];
        echo FormsController::ctrUpdateArea($data);
        break;
    
    case 'deleteArea':
        echo FormsController::ctrDeleteArea($_POST['idArea']);
        break;
        
    case 'updateOrderArea':
        if (isset($_POST['order'])) {
            $order = $_POST['order'];
            foreach ($order as $item) {

                $idArea = $item['id'];
                $position = $item['position'];
                
                FormsController::ctrUpdateOrderArea($position, $idArea);
            }
        }
        echo 'ok';
        break;
}