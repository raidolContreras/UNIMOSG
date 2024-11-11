<?php

require './../model/phpmailer/vendor/autoload.php'; // Asegúrate de cargar PhpSpreadsheet si es necesario
use PhpOffice\PhpSpreadsheet\IOFactory;
    
switch ($_POST['action']) {
    
    case 'getObjets':
        $idArea = $_POST['idArea'];
        $objects = array();
        $objects['objets'] = FormsController::ctrGetObjects($idArea);
        $objects['data'] = FormsController::ctrGetDataObjects($idArea);
        echo json_encode($objects);
        break;
    
    case 'addObject':
        $data = [
            'idArea' => $_POST['idArea'],
            'nameObject' => $_POST['nameObject'],
            'quantity' => $_POST['quantity']
        ];
        echo json_encode(FormsController::ctrAddObject($data));
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

    case 'uploadObjects':
        $idArea = $_POST['idArea'];
        $file = $_FILES['file']['tmp_name'];
        $fileType = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
    
        if ($fileType === 'csv') {
            // Procesar archivo CSV
            $handle = fopen($file, "r");
    
            // Saltar la primera fila si contiene encabezados
            fgetcsv($handle, 1000, ",");
    
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $nombre = $data[0];
                $cantidad = $data[1];
                
                $data = [
                    'idArea' => $idArea,
                    'nameObject' => $nombre,
                    'quantity' => $cantidad
                ];

                FormsController::ctrAddObject($data);
            }
    
            fclose($handle);
            echo 'ok';
        } elseif ($fileType === 'xlsx' || $fileType === 'xls') {
            // Procesar archivo Excel
            $spreadsheet = IOFactory::load($file);
            $sheet = $spreadsheet->getActiveSheet();
            
            foreach ($sheet->getRowIterator(2) as $row) { // Comenzamos en la fila 2 si la fila 1 es el encabezado
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false);
    
                $nombre = $cellIterator->current()->getValue(); // Columna A
                $cellIterator->next();
                $cantidad = $cellIterator->current()->getValue(); // Columna B
                
                $data = [
                    'idArea' => $idArea,
                    'nameObject' => $nombre,
                    'quantity' => $cantidad
                ];

                FormsController::ctrAddObject($data);
                
            }
    
            echo 'ok';
        } else {
            echo json_encode(["status" => "error", "message" => "Formato de archivo no soportado."]);
        }
        break;
}