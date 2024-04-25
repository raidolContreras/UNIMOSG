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