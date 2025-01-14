<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enviar Mensaje a Telegram</title>
</head>
<body>
    <form action="sendMessage.php" method="post" enctype="multipart/form-data">
        <textarea name="message" placeholder="Escribe tu mensaje con formato..." rows="4" cols="50"></textarea><br><br>
        <input type="file" name="image" accept="image/*"><br><br>
        <input type="submit" value="Enviar">
    </form>

    <p>Ejemplos de formato en HTML:<br>
        <b>&lt;b&gt;Texto en negrita&lt;/b&gt;</b><br>
        <i>&lt;i&gt;Texto en cursiva&lt;/i&gt;</i><br>
        Enlace: <code>&lt;a href="https://example.com"&gt;Visitar sitio&lt;/a&gt;</code>
    </p>
</body>
</html>
