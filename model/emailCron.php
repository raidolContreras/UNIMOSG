<?php 

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
                <div class="section">
                    <h2>Edificio 1</h2>
                    <ul class="task-list">
                        <li>
                            Recepción: la mesa está rota
                            <div class="task-details">
                                <span>Fecha solicitada: 15/05/2024</span>
                                <span class="importance">Urgente</span>
                            </div>
                        </li>
                        <li>
                            Aula 101: el proyector no funciona
                            <div class="task-details">
                                <span>Fecha solicitada: 14/05/2024</span>
                                <span class="importance">Inmediato</span>
                            </div>
                        </li>
                        <li>
                            Baños: falta de papel higiénico
                            <div class="task-details">
                                <span>Fecha solicitada: 16/05/2024</span>
                                <span class="importance">Pendiente</span>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="section">
                    <h2>Edificio 2</h2>
                    <ul class="task-list">
                        <li>
                            Biblioteca: se requiere actualización de libros
                            <div class="task-details">
                                <span>Fecha solicitada: 13/05/2024</span>
                                <span class="importance">Pendiente</span>
                            </div>
                        </li>
                        <li>
                            Aula 202: la calefacción no funciona
                            <div class="task-details">
                                <span>Fecha solicitada: 12/05/2024</span>
                                <span class="importance">Urgente</span>
                            </div>
                        </li>
                        <li>
                            Pasillo: luces parpadeantes
                            <div class="task-details">
                                <span>Fecha solicitada: 11/05/2024</span>
                                <span class="importance">Inmediato</span>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="section">
                    <h2>Edificio 3</h2>
                    <ul class="task-list">
                        <li>
                            Laboratorio de computación: varias PCs no encienden
                            <div class="task-details">
                                <span>Fecha solicitada: 10/05/2024</span>
                                <span class="importance">Urgente</span>
                            </div>
                        </li>
                        <li>
                            Aula 303: ventanas con problemas de cierre
                            <div class="task-details">
                                <span>Fecha solicitada: 09/05/2024</span>
                                <span class="importance">Inmediato</span>
                            </div>
                        </li>
                        <li>
                            Escaleras: pasamanos sueltos
                            <div class="task-details">
                                <span>Fecha solicitada: 08/05/2024</span>
                                <span class="importance">Pendiente</span>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="footer">
                <p>&copy; 2024 Universidad Montrer. Todos los derechos reservados.</p>
            </div>
        </div>
    </body>
    </html>
    
                ';