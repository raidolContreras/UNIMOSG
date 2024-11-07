<?php

    
switch ($_POST['action']) {
    
    case 'getObjets':
        $idArea = $_POST['idArea'];
        echo json_encode(FormsController::ctrGetObjects($idArea));
        break;
    
    case 'addObject':
        $data = [
            'idArea' => $_POST['idArea'],
            'nameObject' => $_POST['nameObject'],
            'quantity' => $_POST['quantity']
        ];
        echo FormsController::ctrAddObject($data);
        break;
    
    case 'searchObject':
        $idObject = $_POST['idObject'];
        echo json_encode(FormsController::ctrSearchObject($idObject));
        break;
    
    case 'updateObject':
        $data = [
            'idObject' => $_POST['idObject'],
            'nameObject' => $_POST['nameObject'],
            'quantity' => $_POST['quantity']
        ];
        echo FormsController::ctrUpdateObject($data);
        break;
    
    case 'deleteObject':
        $idObject = $_POST['idObject'];
        echo FormsController::ctrDeleteObject($idObject);
        break;
    
    case 'updateOrderObject':
        if (isset($_POST['order'])) {
            $order = $_POST['order'];
            foreach ($order as $item) {
                $idObject = $item['id'];
                $position = $item['position'];
                
                FormsController::ctrUpdateOrderObject($position, $idObject);
            }
        }
        echo 'ok';
        break;
}