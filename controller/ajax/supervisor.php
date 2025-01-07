<?php

$userId = $_SESSION['idUser'];

switch ($_POST['action']) {
    case 'finalizarSupervision':
        $idSupervision = $_POST['idSupervision'];
        echo FormsController::ctrFinalizarSupervision($idSupervision);
        break;

    case 'uploadEvidence':
        // Verificar que se haya enviado un archivo
        if (isset($_FILES['evidence']) && $_FILES['evidence']['error'] == UPLOAD_ERR_OK) {
            // Configurar el directorio de destino
            $uploadDir = '../view/evidences/'; // Asegúrate de que esta carpeta exista y tenga permisos de escritura
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true); // Crear carpeta si no existe
            }
    
            // Obtener información del archivo
            $fileName = basename($_FILES['evidence']['name']);
            $fileTmpPath = $_FILES['evidence']['tmp_name'];
            $fileSize = $_FILES['evidence']['size'];
            $fileType = $_FILES['evidence']['type'];
    
            // Crear un nombre único para evitar conflictos
            $uniqueFileName = uniqid() . '_' . $fileName;
    
            // Ruta completa donde se guardará el archivo
            $destination = $uploadDir . $uniqueFileName;
    
            // Mover el archivo desde el directorio temporal al destino
            if (move_uploaded_file($fileTmpPath, $destination)) {
                $idObject = $_POST['idObject'];
                $urgency = $_POST['urgency'];
                $description = $_POST['description'];

                $data = array (
                    'idObject' => $idObject,
                    'idUser' => $userId,
                    'evidence' => $uniqueFileName,
                    'urgency' => $urgency,
                    'description' => $description,
                );

                $response = FormsController::ctrUploadEvidence($data);
                if ($response == 'ok') {
                    echo json_encode([
                        'status' => 'success',
                        'message' => 'Archivo subido correctamente.',
                    ]);
                } else {
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Error al subir el archivo.',
                    ]);
                }
            } else {
                // Error al mover el archivo
                echo json_encode([
                    'status' => 'error',
                    'message' => 'No se pudo mover el archivo al directorio de destino.',
                ]);
            }
        } else {
            // Error en la subida del archivo
            echo json_encode([
                'status' => 'error',
                'message' => 'Error al subir el archivo. Código de error: ' . $_FILES['evidence']['error'],
            ]);
        }
        break;
    
    default:
        echo json_encode(['error' => 'Acción de supervisor no válida']);
}
