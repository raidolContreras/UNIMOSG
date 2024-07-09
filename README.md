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

# Instrucciones para Convertir `.htaccess` a `web.config` en un Servidor de Windows

Este documento proporciona instrucciones detalladas para convertir las reglas de un archivo `.htaccess` a un archivo `web.config` en un servidor de Windows utilizando la característica de importación del módulo IIS URL Rewrite.

## Requisitos Previos

1. **IIS (Internet Information Services)**: Debe estar instalado y configurado en su servidor de Windows.
2. **IIS URL Rewrite Module**: Asegúrese de que el módulo de reescritura de URL de IIS esté instalado. Si no lo está, puede descargarlo desde [aquí](https://www.iis.net/downloads/microsoft/url-rewrite).

## Pasos para Convertir las Reglas de `.htaccess` a `web.config`

### Paso 1: Acceder al Administrador de IIS

1. Abra el **Administrador de IIS** en su servidor de Windows.
2. En el panel de la izquierda, expanda el nodo del servidor para ver los sitios web configurados.

### Paso 2: Seleccionar su Sitio Web

1. Haga clic en el nombre de su sitio web en el árbol de navegación en el panel de la izquierda.

### Paso 3: Abrir la Característica URL Rewrite

1. En la vista de características (Feature View) en el panel central, busque y haga doble clic en **URL Rewrite**.

### Paso 4: Importar las Reglas de `.htaccess`

1. En el panel de acciones (Actions) a la derecha, haga clic en **Import Rules**.
2. Pegue sus reglas de `.htaccess` en el cuadro de texto **Rewrite rules** y verá sus reglas convertidas a continuación.

#### Reglas en `.htaccess`

```apache
Options All -Indexes

RewriteEngine On
RewriteRule ^([-a-zA-Z0-9/_&=]+)$ index.php?pagina=$1
