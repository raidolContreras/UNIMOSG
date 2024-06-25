### Proyecto UNIMOSG - Readme

## Descripción
UNIMOSG es un sistema de servicios generales para UNIMO, desarrollado para facilitar la gestión y organización de tareas y servicios ofrecidos por la entidad. Este proyecto está construido utilizando una combinación de tecnologías web como HTML, CSS, SCSS, JavaScript, y PHP.

## Estructura del Proyecto
El repositorio está organizado en las siguientes carpetas y archivos clave:

- **controller/**: Contiene los controladores que manejan la lógica de la aplicación.
- **model/**: Incluye los modelos que interactúan con la base de datos.
- **view/**: Contiene las vistas que se encargan de la presentación de la información al usuario.

## Pantallas Principales
### 1. Página Principal
- **index.php**: Página de inicio que muestra una visión general de los servicios disponibles.

### 2. Gestión de Subscripciones
- **subscribe.php**: Permite a los usuarios suscribirse a los servicios.
- **subscribe_notify.html**: Interfaz para notificar a los usuarios sobre nuevas suscripciones.

### 3. Administración de Tareas
- **cron.php**: Ejecuta tareas programadas para mantener el sistema actualizado.

### 4. Errores y Manejo de Excepciones
- **error404.php**: Página que se muestra cuando un recurso no se encuentra disponible.

### 5. Seguridad y Accesos
- **whiteList.php**: Administra la lista blanca de accesos permitidos.
- **generate_vapid_keys.php**: Genera claves VAPID para la autenticación de notificaciones push.

## Instalación
1. Clonar el repositorio:
   ```bash
   git clone https://github.com/raidolContreras/UNIMOSG.git
   ```
2. Configurar el entorno:
   - Asegurarse de tener instalado PHP, un servidor web (como Apache) y una base de datos (como MySQL).
   - Configurar las conexiones a la base de datos en los archivos de configuración correspondientes.

3. Ejecutar el sistema en un servidor local o en un entorno de desarrollo.

## Tecnologías Utilizadas
- **HTML**: 56.4%
- **CSS**: 16.9%
- **SCSS**: 11.7%
- **JavaScript**: 8.9%
- **PHP**: 4.6%
- **Hack**: 1.5%

## Contribuciones
Se aceptan contribuciones mediante pull requests. Asegúrese de seguir las pautas de contribución y de realizar pruebas exhaustivas antes de enviar su solicitud.

## Contacto
Para más información o reportar problemas, puede contactar al desarrollador principal a través de su perfil de GitHub.

**For more GPTs by Backloger AI, visit [Backloger AI](https://lp.backloger.ai). Toghether we will win**
