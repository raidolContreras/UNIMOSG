<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error 404 - Página no encontrada</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #01643D, #008080); /* Fondo degradado */
            color: #ffffff;
            text-align: center;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            max-width: 90%;
            padding: 20px;
        }
        .logo {
            width: 100px;
            margin-bottom: 20px;
            filter: drop-shadow(2px 2px 4px rgba(0, 0, 0, 0.5)); /* Sombra en el logo */
        }
        .error-message {
            font-size: 3em;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5); /* Sombra en el texto */
        }
        .return-home {
            background-color: #ffffff;
            color: #01643D;
            border: none;
            padding: 15px 30px;
            font-size: 1.2em;
            border-radius: 30px; /* Bordes redondeados */
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s, box-shadow 0.3s; /* Transiciones suaves */
            box-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3); /* Sombra suave */
            text-decoration: none;
            display: inline-block;
        }
        .return-home:hover {
            background-color: #f2f2f2;
            transform: translateY(-2px); /* Levantar ligeramente al pasar el cursor */
            box-shadow: 4px 4px 6px rgba(0, 0, 0, 0.3); /* Sombra más pronunciada */
        }
        .description {
            font-size: 1.5em;
            margin-bottom: 40px;
        }
        .illustration-container {
            margin-bottom: 50px;
            position: relative;
            overflow: hidden;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.4);
        }
        .illustration {
            max-width: 30%;
            height: auto;
            border-radius: 10px; /* Bordes redondeados */
            transition: transform 0.5s;
        }
        .illustration-container:hover .illustration {
            transform: scale(1.1); /* Efecto de escala al pasar el cursor */
        }
        .illustration-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* Fondo semitransparente */
            display: flex;
            justify-content: center;
            align-items: center;
            opacity: 0;
            transition: opacity 0.5s;
        }
        .illustration-container:hover .illustration-overlay {
            opacity: 1;
        }
        .illustration-text {
            color: #fff;
            font-size: 1.5em;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="view/assets/images/logo.png" alt="Logo" class="logo">
        <h1 class="error-message">¡Oops! Error 404</h1>
        <p class="description">
            Lo sentimos, la página que estás buscando no se pudo encontrar.
        </p>
        <div class="illustration-container" onclick="home()">
            <img src="view/assets/images/error404/placeholder.png" alt="Ilustración 404" class="illustration" id="errorImage">
            <div class="illustration-overlay">
                <div class="illustration-text">Haz clic para volver al inicio</div>
            </div>
        </div>
    </div>
</body>
</html>


    <script>
        var images = ['01.png', '02.png', '03.png', '04.png', '05.png'];
        var randomNumber = Math.floor(Math.random() * images.length);
        var errorImage = document.getElementById('errorImage');
        errorImage.src = 'view/assets/images/error404/' + images[randomNumber];
        function home(){
            window.location.href = 'inicio';
        }
    </script>