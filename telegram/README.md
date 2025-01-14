# API para Enviar Mensajes a Telegram

## 📦 Instalación

1. Clona el repositorio o descarga los archivos.
2. Instala las dependencias con Composer:
   ```bash
   composer install
3. Crea el archivo .env y agrega tu token de bot:
makefile
Copiar código
TELEGRAM_BOT_TOKEN=TU_TOKEN_DE_BOT
🚀 Uso
Envía una solicitud POST a sendMessage.php con el siguiente formato:

Ejemplo en CURL:
bash
Copiar código
curl -X POST http://localhost/sendMessage.php \
-H \"Content-Type: application/json\" \
-d '{\"chat_id\":\"123456789\", \"message\":\"Hola desde mi API de Telegram!\"}'
✅ Respuesta
200 OK: {"status":"success","message":"Mensaje enviado correctamente."}
400 Error: {"status":"error","message":"Faltan parámetros: chat_id o message."}