<?php
// Definir la ruta del archivo .env
$envFilePath = __DIR__ . '/.env';

// Función para leer las variables del archivo .env
function getEnvVars($filePath) {
    if (!file_exists($filePath)) {
        return [];
    }

    $envContent = file_get_contents($filePath);
    $envLines = explode("\n", $envContent);
    $envVars = [];

    foreach ($envLines as $line) {
        $line = trim($line);
        if (!empty($line) && strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $envVars[$key] = $value;
        }
    }
    return $envVars;
}

// Función para actualizar el archivo .env
function updateEnvFile($filePath, $data) {
    $content = '';
    foreach ($data as $key => $value) {
        $content .= "$key=$value\n";
    }
    if (file_put_contents($filePath, $content) === false) {
        throw new Exception("Error al escribir el archivo .env.");
    }
}

// Manejar las peticiones AJAX para actualizar el archivo .env
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax'])) {
    $response = [];
    
    // Leer las variables actuales
    $currentEnv = getEnvVars($envFilePath);

    // Procesar los datos enviados
    $updatedEnv = [];
    foreach ($_POST as $key => $value) {
        if ($key !== 'ajax') {
            $updatedEnv[$key] = trim($value);
        }
    }

    // Validar campos obligatorios
    $requiredFields = ['TOKEN', 'INSTANCE_ID', 'HOST', 'DATABASE', 'USER_NAME'];
    $missingFields = array_diff($requiredFields, array_keys($updatedEnv));

    if (!empty($missingFields)) {
        $response['error'] = "Faltan los siguientes campos: " . implode(', ', $missingFields);
    } else {
        // Mezclar con las variables existentes
        $finalEnv = array_merge($currentEnv, $updatedEnv);

        // Actualizar el archivo .env
        try {
            updateEnvFile($envFilePath, $finalEnv);
            $response['success'] = "Datos actualizados correctamente.";
        } catch (Exception $e) {
            $response['error'] = $e->getMessage();
        }
    }

    // Retornar respuesta en formato JSON
    echo json_encode($response);
    exit;
}

// Leer las variables actuales del archivo .env
$envVars = getEnvVars($envFilePath);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar .env</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .form-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 30px;
            background-color: #ffffff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            transition: all 0.3s ease-in-out;
        }
        .form-container:hover {
            transform: translateY(-5px);
        }
        .btn {
            transition: background-color 0.3s ease;
        }
        .btn:hover {
            background-color: #01643d;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="form-container">
        <h2 class="mb-4 text-center">Editar archivo .env</h2>
        <div id="message"></div>

        <form id="envForm">
            <div class="mb-3">
                <label for="token" class="form-label">TOKEN</label>
                <input type="text" class="form-control" id="token" name="TOKEN" value="<?php echo $envVars['TOKEN'] ?? ''; ?>" placeholder="Ingresa el nuevo TOKEN">
            </div>
            <div class="mb-3">
                <label for="instance_id" class="form-label">INSTANCE_ID</label>
                <input type="text" class="form-control" id="instance_id" name="INSTANCE_ID" value="<?php echo $envVars['INSTANCE_ID'] ?? ''; ?>" placeholder="Ingresa el nuevo INSTANCE_ID">
            </div>
            <div class="mb-3">
                <label for="host" class="form-label">HOST</label>
                <input type="text" class="form-control" id="host" name="HOST" value="<?php echo $envVars['HOST'] ?? ''; ?>" placeholder="Ingresa el HOST">
            </div>
            <div class="mb-3">
                <label for="database" class="form-label">DATABASE</label>
                <input type="text" class="form-control" id="database" name="DATABASE" value="<?php echo $envVars['DATABASE'] ?? ''; ?>" placeholder="Ingresa la base de datos">
            </div>
            <div class="mb-3">
                <label for="username" class="form-label">USER_NAME</label>
                <input type="text" class="form-control" id="username" name="USER_NAME" value="<?php echo $envVars['USER_NAME'] ?? ''; ?>" placeholder="Ingresa el nombre de usuario">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">PASSWORD</label>
                <input type="password" class="form-control" id="password" name="PASSWORD" value="<?php echo $envVars['PASSWORD'] ?? ''; ?>" placeholder="Ingresa la contraseña">
            </div>
            <div class="mb-3">
                <label for="telegram_bot_token" class="form-label">TELEGRAM_BOT_TOKEN</label>
                <input type="text" class="form-control" id="telegram_bot_token" name="TELEGRAM_BOT_TOKEN" value="<?php echo $envVars['TELEGRAM_BOT_TOKEN'] ?? ''; ?>" placeholder="Ingresa el TOKEN del bot de Telegram">
            </div>
            <button type="submit" class="btn btn-success w-100">Guardar</button>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#envForm').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: '',
                type: 'POST',
                data: $(this).serialize() + '&ajax=1',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#message').html('<div class="alert alert-success">' + response.success + '</div>');
                    } else if (response.error) {
                        $('#message').html('<div class="alert alert-danger">' + response.error + '</div>');
                    }
                },
                error: function() {
                    $('#message').html('<div class="alert alert-danger">Error en la petición AJAX.</div>');
                }
            });
        });
    });
</script>
</body>
</html>
