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

// Leer las variables actuales del archivo .env
$envVars = getEnvVars($envFilePath);

// Procesar el formulario cuando se envía
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = trim($_POST['TOKEN'] ?? '');
    $instance_id = trim($_POST['INSTANCE_ID'] ?? '');

    // Validación básica de los campos
    if (empty($token) || empty($instance_id)) {
        $error = "Ambos campos son obligatorios.";
    } else {
        // Actualizar el archivo .env
        try {
            updateEnvFile($envFilePath, ['TOKEN' => $token, 'INSTANCE_ID' => $instance_id]);
            $success = "Datos actualizados correctamente.";
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    }
}
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
            max-width: 500px;
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
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"><i class="bi bi-gear-fill"></i> Configuración</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>

    <!-- Formulario de edición -->
    <div class="container">
        <div class="form-container">
            <h2 class="mb-4 text-center">Editar archivo .env</h2>

            <!-- Mostrar mensajes de éxito o error -->
            <?php if (!empty($success)): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php elseif (!empty($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="mb-3">
                    <label for="token" class="form-label">TOKEN</label>
                    <input type="text" class="form-control" id="token" name="TOKEN" placeholder="Ingresa el nuevo TOKEN">
                </div>
                <div class="mb-3">
                    <label for="instance_id" class="form-label">INSTANCE_ID</label>
                    <input type="text" class="form-control" id="instance_id" name="INSTANCE_ID" placeholder="Ingresa el nuevo INSTANCE_ID">
                </div>
                <button type="submit" class="btn btn-success w-100">Guardar</button>
            </form>
        </div>
    </div>

    <!-- Cargar Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
