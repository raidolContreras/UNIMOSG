<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

function enviarCorreo($destinatario, $asunto, $responses) {
    // Configuración del correo HTML
    $email = '
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Informe de Pendientes - UNIMO</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
                background-color: #f4f4f4;
                color: #333;
            }
            .container {
                width: 100%;
                max-width: 600px;
                margin: 20px auto;
                background-color: #ffffff;
                border: 1px solid #ddd;
                border-radius: 8px;
                overflow: hidden;
                box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            }
            .header {
                background-color: #01643d;
                color: #ffffff;
                padding: 20px;
                text-align: center;
            }
            .header img {
                width: 120px;
                max-width: 100%;
                height: auto;
                margin-bottom: 10px;
            }
            .header h1 {
                margin: 0;
                font-size: 24px;
                font-weight: bold;
            }
            .header p {
                margin: 0;
                font-size: 16px;
            }
            .content {
                padding: 20px;
            }
            .section {
                margin-bottom: 20px;
            }
            .section h2 {
                color: #01643d;
                font-size: 20px;
                margin-bottom: 10px;
                border-bottom: 2px solid #01643d;
                padding-bottom: 5px;
            }
            .task-list {
                list-style: none;
                padding: 0;
                margin: 0;
            }
            .task-list li {
                padding: 10px 0;
                border-bottom: 1px solid #ddd;
                display: flex;
                flex-direction: column;
            }
            .task-list li:last-child {
                border-bottom: none;
            }
            .task-details {
                display: flex;
                justify-content: space-between;
                align-items: center;
                font-size: 14px;
                margin-top: 5px;
            }
            .task-details span {
                display: block;
            }
            .importance {
                font-weight: bold;
                color: #01643d;
            }
            .footer {
                background-color: #01643d;
                color: #ffffff;
                text-align: center;
                padding: 15px;
                font-size: 14px;
            }
            @media screen and (max-width: 600px) {
                .header, .content, .footer {
                    padding: 15px;
                }
                .section h2 {
                    margin: 0 -15px 10px -15px;
                    padding: 10px 15px;
                    border-bottom-width: 1px;
                }
                .task-list li {
                    padding: 10px 0;
                }
                .task-details {
                    flex-direction: column;
                    align-items: flex-start;
                }
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header">
                <img src="http://127.0.0.1/UNIMOSG/view/assets/images/logo.png" alt="UNIMO Logo">
                <h1>Informe de Pendientes</h1>
                <p>Universidad Montrer (UNIMO)</p>
            </div>
            <div class="content">
                <div class="section">';

    foreach ($responses as $response) {
        $email .= '
        <h2>' . htmlspecialchars($response['area']) . '</h2>
        <ul class="task-list">';
        foreach ($response['tasks'] as $task) {
            $email .= '
            <li>
                ' . htmlspecialchars($task['descripcion']) . '
                <div class="task-details">
                    <span>Fecha solicitada: ' . htmlspecialchars($task['fecha']) . '</span>
                    <span class="importance">' . htmlspecialchars($task['importancia']) . '</span>
                </div>
            </li>';
        }
        $email .= '
        </ul>';
    }

    $email .= '
                </div>
            </div>
            <div class="footer">
                <p>&copy; 2024 Universidad Montrer. Todos los derechos reservados.</p>
            </div>
        </div>
    </body>
    </html>';

    // Configuración del correo
    $mail = new PHPMailer(true);

    try {
        // Configuración del servidor SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.example.com'; // Cambia esto al servidor SMTP que estés usando
        $mail->SMTPAuth = true;
        $mail->Username = 'tu-email@example.com'; // Cambia esto a tu dirección de correo electrónico
        $mail->Password = 'tu-contraseña'; // Cambia esto a tu contraseña de correo electrónico
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Configuración del remitente y destinatario
        $mail->setFrom('tu-email@example.com', 'UNIMO');
        $mail->addAddress($destinatario);

        // Contenido del correo
        $mail->isHTML(true);
        $mail->Subject = $asunto;
        $mail->Body    = $email;
        $mail->AltBody = 'Este es el contenido del correo electrónico en texto plano para los clientes de correo que no soportan HTML.';

        $mail->send();
        echo 'El correo ha sido enviado correctamente';
    } catch (Exception $e) {
        echo "El correo no pudo ser enviado. Mailer Error: {$mail->ErrorInfo}";
    }
}

// Ejemplo de uso
$responses = [
    [
        'area' => 'Edificio 1',
        'tasks' => [
            [
                'descripcion' => 'Recepción: la mesa está rota',
                'fecha' => '15/05/2024',
                'importancia' => 'Urgente'
            ],
            [
                'descripcion' => 'Aula 101: el proyector no funciona',
                'fecha' => '14/05/2024',
                'importancia' => 'Inmediato'
            ],
            [
                'descripcion' => 'Baños: falta de papel higiénico',
                'fecha' => '16/05/2024',
                'importancia' => 'Pendiente'
            ]
        ]
    ],
    // Añadir más áreas y tareas según sea necesario
];

enviarCorreo('oscarcontrerasf91@gmail.com', 'Informe de Pendientes - UNIMO', $responses);