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
    
    // Procesar los datos del formulario
    $token = trim($_POST['TOKEN'] ?? '');
    $instance_id = trim($_POST['INSTANCE_ID'] ?? '');
    $host = trim($_POST['HOST'] ?? '');
    $database = trim($_POST['DATABASE'] ?? '');
    $username = trim($_POST['USER_NAME'] ?? '');
    $password = trim($_POST['PASSWORD'] ?? '');

    // Validación básica de los campos
    if (empty($token) || empty($instance_id) || empty($host) || empty($database) || empty($username)) {
        $response['error'] = "Todos los campos son obligatorios.";
    } else {
        // Actualizar el archivo .env
        try {
            updateEnvFile($envFilePath, [
                'TOKEN' => $token,
                'INSTANCE_ID' => $instance_id,
                'HOST' => $host,
                'DATABASE' => $database,
                'USER_NAME' => $username,
                'PASSWORD' => $password
            ]);
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
    <!-- Cargar Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Iconos Bootstrap -->
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
        .navbar {
            margin-bottom: 50px;
        }
        .section-title {
            margin-top: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #dee2e6;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <!-- Formulario de edición -->
    <div class="container">
        <div class="form-container">
            <h2 class="mb-4 text-center">Editar archivo .env</h2>

            <!-- Mostrar mensajes de éxito o error -->
            <div id="message"></div>

            <form id="envForm">
                <!-- Sección para conectar WhatsApp -->
                <div class="section-title"><h4>Conexión a WhatsApp</h4></div>
                <div class="mb-3">
                    <label for="token" class="form-label">TOKEN</label>
                    <input type="text" class="form-control" id="token" name="TOKEN" value="<?php echo $envVars['TOKEN'] ?? ''; ?>" placeholder="Ingresa el nuevo TOKEN">
                </div>
                <div class="mb-3">
                    <label for="instance_id" class="form-label">INSTANCE_ID</label>
                    <input type="text" class="form-control" id="instance_id" name="INSTANCE_ID" value="<?php echo $envVars['INSTANCE_ID'] ?? ''; ?>" placeholder="Ingresa el nuevo INSTANCE_ID">
                </div>

                <!-- Sección para conexión al servidor -->
                <div class="section-title"><h4>Conexión al Servidor</h4></div>
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
                <button type="submit" class="btn btn-success w-100">Guardar</button>
            </form>
        </div>
    </div>

    <!-- Cargar Bootstrap JS y AJAX -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#envForm').on('submit', function(e) {
                e.preventDefault(); // Prevenir el envío del formulario por defecto

                $.ajax({
                    url: '', // La misma página PHP maneja el POST
                    type: 'POST',
                    data: $(this).serialize() + '&ajax=1', // Enviar los datos del formulario junto con la marca de ajax
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