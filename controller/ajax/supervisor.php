<?php

$userId = $_SESSION['idUser'];

switch ($_POST['action']) {
    case 'finalizarSupervision':
        $idSupervision = $_POST['idSupervision'];
        echo FormsController::ctrFinalizarSupervision($idSupervision);
        break;

    case 'getIncidents':
        echo json_encode(FormsController::ctrGetIncidents());
        break;

    case 'endIncident':
        $idEvidence = $_POST['idEvidence'];
        $endDate = $_POST['endDate'];
        $purchaseMade = ($_POST['purchaseMade'] == 'yes') ? 1 : 0;
        $purchaseAmount = $_POST['purchaseAmount'];
        $invoiceFile = $_FILES['invoiceFile'];
        $evidenceFile = $_FILES['evidenceFile'];
        $reason = $_POST['reason'];

        //files name 
        $invoiceFileName = $invoiceFile['name'];
        $evidenceFileName = $evidenceFile['name'];
        $response = FormsController::ctrEndIncident($idEvidence, $endDate, $purchaseMade, $purchaseAmount, $invoiceFileName, $evidenceFileName, $reason);
        if ($response == 'ok') {
            // Guardar los archivos en el servidor en la carpeta view/evidences conn un uniq id
            $uploadDir = '../view/evidences/'; // Asegúrate de que esta carpeta exista y tenga permisos de escritura
            $uniqueInvoiceFileName = uniqid(). '_'. $invoiceFileName;
            $uniqueEvidenceFileName = uniqid(). '_'. $evidenceFileName;
            move_uploaded_file($invoiceFile['tmp_name'], $uploadDir . $uniqueInvoiceFileName);
            move_uploaded_file($evidenceFile['tmp_name'], $uploadDir . $uniqueEvidenceFileName);
            
            Logs::createLogs($userId, 'endIncident', 'Cierre de incidente: '.json_encode($_POST));
        }
        echo $response;
        break;

    case 'confirmCorrectObject':
        $idObject = $_POST['idObject'];
        $isCorrect = $_POST['isCorrect'];
        echo FormsController::ctrConfirmCorrectObject($idObject, $isCorrect);
        if (isset($_POST['idEvidence'])) {
            $idEvidence = $_POST['idEvidence'];
            FormsController::ctrEndEvidence($idEvidence);
            Logs::createLogs($userId, 'confirmCorrectObject', 'Envio de evidencia de correccion del objeto: '.json_encode($_POST));
        }
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
    
    case 'getObjectsBad':
        $idArea = $_POST['idArea'];
        $objects = array();
        $objects['objets'] = FormsController::ctrGetObjectsBad($idArea);
        $objects['data'] = FormsController::ctrGetDataObjects($idArea);
        echo json_encode($objects);
        break;

    case 'sendMessageTelegram':
        $idObject = $_POST['idObject'];
        $chatId = $_POST['chatId'];
    
        // Obtener los datos del objeto con su ubicación completa
        $object = FormsController::ctrGetObject($idObject);
        // si $object es falso, no se encontró el objeto o esta vacío
        if (!$object) {
            echo json_encode(['status' => 'error', 'message' => 'No se encontró el objeto.']);
        } else {

            // Crear objeto DateTime con la hora actual
            $date = new DateTime();

            // Restar 6 horas
            $date->modify('-7 hours');

            // Crear el mensaje con la ubicación en mayúsculas
            $message = $_POST['message'];
            $message .= "\n🛠 <b>Objeto:</b> " . strtoupper($object['nameObject']) . "\n";
            $message .= "📍 <b>Ubicación:</b> " . $object['ubicacion_completa'] . "\n";

            // Formatear la fecha al formato solicitado
            $message .= "📅 <b>Fecha:</b> " . $date->format('d/m/Y H:i:s');
    
            // Verificar si se envió una imagen
            $file = $_FILES['file']['tmp_name'] ?? null;
    
            // Configurar los datos para enviar a Telegram
            $postData = [
                'message' => $message,
                'chatId' => $chatId
            ];
    
            // Si hay archivo, lo incluimos
            if ($file) {
                $postData['image'] = new CURLFile($file);
            }
    
            // Enviar el mensaje a Telegram
            $ch = curl_init();
            // Usando una URL relativa desde el servidor
            curl_setopt($ch, CURLOPT_URL, $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/UNIMOSG/telegram/sendMessage.php');
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);
            echo $response;
        }
        break;
    
    default:
        echo json_encode(['error' => 'Acción de supervisor no válida']);
}