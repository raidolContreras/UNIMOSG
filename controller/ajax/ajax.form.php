<?php 

require_once "../forms.controller.php";
require_once "../../model/forms.models.php";

session_start();

if(isset($_POST['nameSchool'])) {
    $nameSchool = $_POST['nameSchool'];
    echo FormsController::ctrRegisterSchool($nameSchool);
} 

if(isset($_POST['uploadZones'])) {
    $result = '';
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK && $_FILES['file']['type'] === 'text/csv') {
        $idSchool = $_POST['uploadZones'];
        $fileTmpPath = $_FILES['file']['tmp_name'];
        $csvData = file_get_contents($fileTmpPath);
        $lines = explode("\n", $csvData);
        $init = false;
        foreach ($lines as $line) {
            if (!empty($line)) {
                if ($init) {
                    $fields = str_getcsv($line);
                    $nameZone = $fields[0];
                    $nameArea = $fields[1];
                    // Verificar si la zona ya existe
                    $school = FormsController::ctrSearchZones($idSchool, 'nameZone', $nameZone);
                    if (empty($school)) {
                        $idZone = FormsController::ctrRegisterZone($nameZone, $idSchool);
                        // Verificar si el área ya existe
                        $area = FormsController::ctrSearchArea($idZone, 'nameArea', $nameArea);
                        if (empty($area)) {
                            $result = FormsController::ctrRegisterArea($nameArea, $idZone);
                        } else {
                            $result = 'ok';
                        }
                    } else {
                        // Verificar si el área ya existe
                        $area = FormsController::ctrSearchArea($school['idZone'], 'nameArea', $nameArea);
                        if (!empty($area)) {
                            $result = 'ok';
                        } else {
                            $result = FormsController::ctrRegisterArea($nameArea, $school['idZone']);
                        }
                    }
                } else {
                    $init = true;
                }
            }
        }
        echo $result;
    } else {
        echo 'Error al cargar el archivo CSV';
    }
}

if(isset($_POST['uploadAreas'])) {
    $result = '';
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK && $_FILES['file']['type'] === 'text/csv') {
        $idZone = $_POST['uploadAreas'];
        $fileTmpPath = $_FILES['file']['tmp_name'];
        $csvData = file_get_contents($fileTmpPath);
        $lines = explode("\n", $csvData);
        $init = false;
        foreach ($lines as $line) {
            if (!empty($line)) {
                if ($init) {
                    $fields = str_getcsv($line);
                    $nameArea = $fields[0];
                        $area = FormsController::ctrSearchArea($idZone, 'nameArea', $nameArea);
                        if (empty($area)) {
                            $result = FormsController::ctrRegisterArea($nameArea, $idZone);
                        } else {
                            $result = 'ok';
                        }
                } else {
                    $init = true;
                }
            }
        }
        echo $result;
    } else {
        echo 'Error al cargar el archivo CSV';
    }
}

if (isset($_POST['uploadObjects'])) {
    $result = '';
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK && $_FILES['file']['type'] === 'text/csv') {
        $idArea = $_POST['uploadObjects'];
        $fileTmpPath = $_FILES['file']['tmp_name'];
        $csvData = file_get_contents($fileTmpPath);
        $lines = explode("\n", $csvData);
        $init = false;
        $data = array();

        $objectsInArea =  FormsController::ctrSelectObjectsbyAreas($idArea);
        
        foreach ($lines as $line) {
            if (!empty($line)) {
                if ($init) {
                    $fields = str_getcsv($line);
                    $nameObject = $fields[0];
                    $cantidad = $fields[1];
                    $isDuplicate = false;

                    if ($objectsInArea) {
                        foreach ($objectsInArea as $object) {
                            if ($object['nameObject'] == $nameObject) {
                                $isDuplicate = true; // Marca como duplicado
                                break; // Sale del loop
                            }
                        }
                    }
                    
                    // Si no es duplicado, lo agregamos al arreglo
                    if (!$isDuplicate) {
                        $data[] = array(
                            "nameObject" => $nameObject,
                            "cantidad" => $cantidad,
                            "objects_idArea" => $idArea
                        );
                    }
                } else {
                    $init = true;
                }
            }
        }
        
        // Envía los datos a la base de datos solo si hay algo que insertar
        if (!empty($data)) {
            $result = FormsController::ctrRegisterObjects($data);
            echo $result;
        } else {
            echo 'No hay nuevos objetos para insertar.';
        }
    } else {
        echo 'Error al cargar el archivo CSV';
    }
}

if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['level'])) {
	$name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $phone = $_POST['phone'];
    $level = $_POST['level'];

    $cryptPassword = crypt($password, '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$');

	$data = array(
		"name" => $name,
        "email" => $email,
        "password" => $cryptPassword,
        "phone" => $phone,
		"level" => $level
	);

    echo FormsController::ctrRegisterUser($data);
}

if (isset($_POST['searchUser'])) {
	$searchUser = $_POST['searchUser'];

    echo json_encode(FormsController::ctrSearchUsers('idUsers', $searchUser));
}

if (isset($_POST['nameEdit']) && isset($_POST['emailEdit']) && isset($_POST['levelEdit'])) {
	$name = $_POST['nameEdit'];
    $email = $_POST['emailEdit'];
    $phone = $_POST['phoneEdit'];
    $level = $_POST['levelEdit'];
	$idUser = $_POST['idUser'];

    $data = array(
        "name" => $name,
        "email" => $email,
        "phone" => $phone,
        "level" => $level,
		"idUsers" => $idUser
    );

    echo FormsController::ctrEditUser($data);
}

if (isset($_POST['suspendUsers'])) {
	$idUser = $_POST['suspendUsers'];
    echo FormsController::ctrSuspendUser($idUser);
}

if (isset($_POST['activateUsers'])) {
	$idUser = $_POST['activateUsers'];
    echo FormsController::ctrActivateUser($idUser);
}

if (isset($_POST['deleteUsers'])) {
    $idUser = $_POST['deleteUsers'];
    echo FormsController::ctrDeleteUser($idUser);
}

if (isset($_POST['searchSchool'])) {
    $searchSchool = $_POST['searchSchool'];
    echo json_encode(FormsController::ctrSearchSchools('idSchool', $searchSchool));
}

if (isset($_POST['nameSchoolEdit']) && isset($_POST['idSchoolEdit'])) {
    $nameSchool = $_POST['nameSchoolEdit'];
    $idSchool = $_POST['idSchoolEdit'];
    $data = array(
        "nameSchool" => $nameSchool,
        "idSchool" => $idSchool
    );
    echo FormsController::ctrEditSchool($data);
}

if (isset($_POST['searchZone'])) {
    $searchZone = $_POST['searchZone'];
    echo json_encode(FormsController::ctrSearchZones(null,'idZone', $searchZone));
}

if (isset($_POST['searchArea'])) {
    $searchArea = $_POST['searchArea'];
    echo json_encode(FormsController::ctrGetArea('idArea', $searchArea));
}

if (isset($_POST['nameZoneEdit'])) {
    $nameZone = $_POST['nameZoneEdit'];
    $idZone = $_POST['idZoneEdit'];
    $data = array(
        "nameZone" => $nameZone,
        "idZone" => $idZone
    );
    echo FormsController::ctrEditZone($data);
}

if (isset($_POST['deleteSchool'])) {
    $idSchool = $_POST['deleteSchool'];
    echo FormsController::ctrDeleteSchool($idSchool);
}

if (isset($_POST['detailsCorrect']) && isset($_POST['idIncidente']) && isset($_POST['specificShopping']) && isset($_POST['shoppingOption'])) {
    $detailsCorrect = $_POST['detailsCorrect'];
    $idIncidente = $_POST['idIncidente'];
    $specificShopping = ($_POST['specificShopping'] != '') ? $_POST['specificShopping'] : null;
    $shoppingOption = ($_POST['shoppingOption'] == 'Si') ? 1 : 0;
    $data = array(
        "detallesCorregidos" => $detailsCorrect,
        "idIncidente" => $idIncidente,
        "detalleCompra" => $specificShopping,
        "compra" => $shoppingOption
    );
    echo FormsController::ctrDetailsCorrect($data);
}

if (isset($_POST['deleteZone'])) {
    $idZone = $_POST['deleteZone'];
    echo FormsController::ctrDeleteZone($idZone);
}

if (isset($_POST['deleteArea'])) {
    $idArea = $_POST['deleteArea'];
    echo FormsController::ctrDeleteArea($idArea);
}

if (isset($_POST['idAreaEdit'])) {
    $nameAreaEdit = $_POST['nameAreaEdit'];
    $idArea = $_POST['idAreaEdit'];
    $data = array(
        "nameArea" => $nameAreaEdit,
        "idArea" => $idArea
    );
    echo FormsController::ctrEditArea($data);
}

if (isset($_POST['fechaAsignada']) && isset($_POST['idIncidente']) && isset($_POST['razon'])) {
    $fechaAsignada = $_POST['fechaAsignada'];
    $idIncidente = $_POST['idIncidente'];
    $razon = $_POST['razon'];
    $data = array(
        "fechaAsignada" => $fechaAsignada,
        "idIncidente" => $idIncidente,
        "razon" => $razon
    );
    echo FormsController::ctrAsignarFecha($data);
}