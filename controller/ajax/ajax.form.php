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
	if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $idSchool = $_POST['uploadZones'];
		// Ruta donde se almacenará el archivo temporalmente
		$fileTmpPath = $_FILES['file']['tmp_name'];
		// Obtener el contenido del archivo CSV
		$csvData = file_get_contents($fileTmpPath);
		// Parsear el contenido del archivo CSV
		$lines = explode("\n", $csvData);
		$init = false;
		foreach ($lines as $line) {
			// Verificar que la línea no esté vacía
			if (!empty($line)) {
				if ($init) {
					$fields = str_getcsv($line);
                    $nameZone = $fields[0];
                    $result = FormsController::ctrRegisterZone($nameZone, $idSchool);
				} else {
					$init = true;
				}
            }
        }
        echo $result;
    }
}

if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['level'])) {
	$name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $level = $_POST['level'];

    $cryptPassword = crypt($password, '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$');

	$data = array(
		"name" => $name,
        "email" => $email,
        "password" => $cryptPassword,
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
    $level = $_POST['levelEdit'];
	$idUser = $_POST['idUser'];

    $data = array(
        "name" => $name,
        "email" => $email,
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