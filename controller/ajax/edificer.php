<?php

switch ($_POST['action']) {
    
    case 'registerEdificer':
        $edificeName = $_POST['edificeName'];
        $idSchool = $_POST['idSchool'];
        echo FormsController::ctrRegisterEdificer($edificeName, $idSchool);
        break;

    case 'searchEdificer':
        if (isset($_POST['searchEdificer'])) {
            $value = $_POST['searchEdificer'];
            $item = 'idEdificers';
        } else {
            $value = $_POST['idSchool'];
            $item = 'edificer_idSchool';
        }
        echo json_encode(FormsController::ctrSearchEdificers($item, $value));
        break;
    
    case 'updateEdificer':
        $data = [
            'nameEdificer' => $_POST['edificeName'],
            'idEdificers' => $_POST['idEdificers']
        ];
        echo FormsController::ctrUpdateEdificer($data);
        break;
    
    case 'deleteEdificer':
        echo FormsController::ctrDeleteEdificer($_POST['idEdificers']);
        break;

    case 'updateOrderEdificer':
        if (isset($_POST['order'])) {
            $order = $_POST['order'];
            foreach ($order as $item) {

                $idEdificers = $item['id'];
                $position = $item['position'];
                
                FormsController::ctrUpdateOrderEdificer($position, $idEdificers);
            }
        }
        echo 'ok';
        break;

}