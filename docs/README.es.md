# BIBLIOTECA Backend
![Framework](https://img.shields.io/badge/Framework-Laravel%2011-red)
![Version](https://img.shields.io/badge/version-1.0.0-green)
![Database](https://img.shields.io/badge/Database-MySQL-orange)
![Language](https://img.shields.io/badge/Language-PHP%208-blue)
![API Documentation](https://img.shields.io/badge/API%20Docs-Swagger-green)

El backend de **BIBLIOTECA** es un sistema de gestión de API RESTful diseñado para una biblioteca personal. Proporciona funcionalidades clave para la autenticación de usuarios y la gestión de libros, incluyendo inicio de sesión seguro, gestión de colecciones y carga de imágenes de portadas. Construido con Laravel y MySQL, este sistema facilita la organización y el control de los libros en una biblioteca privada.

## Características

El backend ofrece las siguientes funcionalidades clave:

### 1. **Autenticación de Usuarios**
   - **Inicio de Sesión:** Maneja el inicio de sesión del usuario, verificando credenciales y creando sesiones de usuario.
   - **Recuperar Datos del Usuario:** Proporciona acceso a los detalles del usuario autenticado.

### 2. **Gestión de Libros**
   - **Agregar Nuevos Libros:** Permite la adición de nuevos libros a la colección de la biblioteca.
   - **Actualizar Información del Libro:** Permite actualizar los detalles de libros existentes.
   - **Eliminar Libros:** Soporta la eliminación de libros de la colección de la biblioteca.
   - **Obtener Todos los Libros:** Recupera una lista de todos los libros en la biblioteca, opcionalmente ordenada por un campo específico (por ejemplo, título, autor, editorial).
   - **Obtener Conteo Total de Libros:** Proporciona el número total de libros en la colección.
   - **Obtener Libros Aleatorios:** Recupera una selección aleatoria de libros basada en un conteo especificado.
   - **Obtener Libros por Ubicación:** Recupera libros organizados por su ubicación dentro de la biblioteca.
   - **Buscar Libros:** Busca libros usando palabras clave en títulos, autores, editoriales, año de publicación y ISBN.
   - **Obtener Libro por ISBN:** Recupera información detallada para un libro específico usando su ISBN.

### 3. **Carga de Portadas de Libros**
   - **Carga de Archivos:** La aplicación soporta la carga de archivos para portadas de libros utilizando el sistema de archivos de Laravel. Esto permite a los usuarios subir imágenes con las siguientes restricciones:
     - **Tipo de Archivo:** Solo se permiten imágenes `.jpg`.
     - **Límite de Tamaño de Archivo:** Los archivos deben ser de 5MB o menores.
   - **Cómo Funciona:**
     - **Configuración:** El sistema de archivos está configurado para usar almacenamiento público para el manejo permanente de archivos.
     - **Validación de Archivos:** Solo se aceptan archivos `.jpg` con el tipo MIME `image/jpeg`.
     - **Manejo de Errores:** Si un archivo subido excede el límite de tamaño o tiene un tipo inválido, la aplicación responde con un mensaje de error específico.
     - **Nombre del Campo:** El nombre del campo para la imagen de portada subida en el formulario debe ser `cover`.

## Tecnologías

El backend de **BIBLIOTECA** está construido utilizando las siguientes tecnologías modernas:

- **Laravel**: Un poderoso framework PHP para construir aplicaciones web, que proporciona una sintaxis elegante y características robustas.
- **MySQL**: Una base de datos relacional utilizada para almacenar y gestionar datos de libros y usuarios.
- **Eloquent ORM**: La herramienta de Mapeo Objeto-Relacional (ORM) incorporada de Laravel para interacciones con la base de datos.
- **bcrypt**: Una biblioteca para el hashing de contraseñas, asegurando la autenticación segura de usuarios.
- **Laravel Sanctum**: Proporciona autenticación API utilizando tokens de acceso personal y autenticación simple basada en sesiones.
- **CORS**: Middleware para habilitar el intercambio de recursos de origen cruzado (CORS) para la comunicación entre frontend y backend.
- **dotenv**: Carga variables de entorno desde un archivo `.env` para gestionar configuraciones de manera efectiva.
- **L5-Swagger**: Un paquete para generar automáticamente documentación API interactiva usando Swagger en Laravel.

## Requisitos Previos

Antes de comenzar, asegúrate de haber cumplido con los siguientes requisitos:

- [PHP](https://www.php.net/downloads.php) (Recomendado: Última versión compatible con Laravel)
- [Composer](https://getcomposer.org/download/) (Gestor de paquetes PHP)
- [MySQL](https://dev.mysql.com/downloads/installer/) (Instalado y en funcionamiento localmente o usando una instancia en la nube)

## Instalación

1. **Clona el repositorio:**

    ```bash
    git clone https://github.com/william-medina/biblioteca-backend-laravel.git
    ```

2. Navega al directorio del proyecto:

    ```bash
    cd biblioteca-backend-laravel
    ```

3. **Instala dependencias:**

    ```bash
    composer install
    ```

4. **Crea un archivo `.env`:**

    Crea un archivo `.env` en la raíz del directorio del proyecto y rellénalo con las variables de entorno necesarias. Aquí tienes una plantilla que puedes usar:

    ```dotenv
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=your_database_name
    DB_USERNAME=your_username
    DB_PASSWORD=your_password

    ALLOWED_ORIGINS=http://localhost:5173
    ALLOW_ALL_ORIGINS=true
    ```

    ### Explicación de Variables Adicionales:

    - **ALLOWED_ORIGINS**: Esta variable especifica qué orígenes están permitidos para interactuar con el backend de tu aplicación. Por ejemplo, en este caso, `http://localhost:5173` está permitido para hacer solicitudes al backend. Puedes añadir múltiples orígenes separándolos con comas si deseas habilitar el acceso desde varios dominios diferentes.

    - **ALLOW_ALL_ORIGINS**: Configurar esta variable en `true` permite que todos los orígenes accedan al backend, permitiendo esencialmente solicitudes desde cualquier dominio. Esto es útil durante el desarrollo, pero debe manejarse con cuidado en producción para evitar problemas de seguridad.

    Reemplaza los valores de marcador de posición con tus detalles de configuración reales.

5. **Ejecuta las migraciones de la base de datos:**

    ```bash
    php artisan migrate
    ```

6. **Inicia el servidor de Laravel:**

    ```bash
    php artisan serve
    ```

## Configuración de Carga de Archivos

Para cargar imágenes de portadas de libros:

1. Asegúrate de que tu almacenamiento esté vinculado al directorio público:
   
    ```bash
    php artisan storage:link
    ```

2. Las imágenes se almacenarán en `storage/app/public/covers`, y puedes acceder a ellas usando las siguientes rutas:

    - `http://localhost:8000/api/covers/cover.jpg`

## Arquitectura

El backend de **BIBLIOTECA** sigue la arquitectura **Modelo-Vista-Controlador (MVC)**:

### 1. **Modelo**

- **Ubicación:** `app/Models`
- **Responsabilidades:** Define la estructura de datos para la aplicación (por ejemplo, Libros, Usuarios), maneja interacciones con la base de datos usando Eloquent ORM e implementa la lógica de negocio relacionada con los datos.

### 2. **Vista**

- **Ubicación:** No aplicable directamente; las APIs de Laravel típicamente devuelven respuestas JSON, que sirven como "vista."
- **Responsabilidades:** Proporciona respuestas JSON formateadas para solicitudes API, que son consumidas por el frontend u otros servicios.

### 3. **Controlador**

- **Ubicación:** `app/Http/Controllers`
- **Responsabilidades:** Procesa las solicitudes entrantes, se comunica con los modelos para manejar los datos y devuelve respuestas al cliente (generalmente en formato JSON).

## Documentación API

La documentación API Swagger para **BIBLIOTECA** está disponible en [Swagger UI](http://localhost:8000/api/docs).

**Importante:** Para acceder a la documentación Swagger, la variable de entorno `ALLOW_ALL_ORIGINS=true` debe estar configurada. Esto permite el acceso sin restricciones al backend durante el desarrollo, lo que hace posible ver la documentación sin problemas de CORS.

### Dominio/Port Personalizado:

Si tu servidor está ejecutándose en un dominio o puerto diferente a `localhost:8000`, necesitarás actualizar la URL de Swagger UI en consecuencia. Por ejemplo:

- Si tu servidor está ejecutándose en `http://192.168.1.100:8000`, deberías acceder a la documentación en `http://192.168.1.100:8000/api/docs`.
- Si tu servidor está ejecutándose en `http://mydomain.com:5000`, la documentación estará accesible en `http://mydomain.com:5000/api/docs`.

### Cómo Acceder:

1. Asegúrate de que la variable de entorno `ALLOW_ALL_ORIGINS=true` esté configurada en tu archivo `.env`.
2. Determina el dominio y puerto donde se está ejecutando tu servidor Laravel.
3. Reemplaza `localhost:8000` en la URL de Swagger UI con el dominio y puerto de tu servidor.
4. Abre tu navegador y navega a la URL actualizada para ver la documentación API.

Ten cuidado al usar `ALLOW_ALL_ORIGINS=true` en entornos de producción para evitar riesgos potenciales de seguridad.

## API Endpoints

### Rutas de Libros

| **Endpoint**                   | **Método** | **Descripción**                                                  |
|--------------------------------|------------|------------------------------------------------------------------|
| `/api/books/count`             | `GET`      | Obtiene el conteo total de libros en la biblioteca.              |
| `/api/books/random/{count}`    | `GET`      | Obtiene una selección aleatoria de libros según el conteo especificado. |
| `/api/books/location`          | `GET`      | Obtiene libros organizados por su ubicación en la biblioteca.     |
| `/api/books/{sortBy}`          | `GET`      | Obtiene todos los libros ordenados por un campo específico (por ejemplo, título, autor, editorial). |
| `/api/books/search/{keyword}`  | `GET`      | Busca libros por una palabra clave en títulos, autores, editoriales, año de publicación e ISBN. |
| `/api/books/isbn/{isbn}`       | `GET`      | Obtiene información detallada de un libro específico por su ISBN. |
| `/api/books`                  | `POST`     | Añade un nuevo libro a la biblioteca.                            |
| `/api/books/{isbn}`            | `PUT`      | Actualiza la información de un libro específico por su ISBN.     |
| `/api/books/{isbn}`            | `DELETE`   | Elimina un libro específico de la biblioteca.                    |

### Rutas de Autenticación

| **Endpoint**                   | **Método** | **Descripción**                                                  |
|--------------------------------|------------|------------------------------------------------------------------|
| `/api/auth/login`              | `POST`     | Autentica a un usuario y devuelve un token JWT.                  |
| `/api/auth/me`                 | `GET`      | Obtiene los detalles del usuario autenticado actualmente.        |

### Middleware

**Laravel Sanctum** proporciona un sistema de autenticación simple basado en tokens para APIs. Asegúrate de incluir el middleware de Sanctum en tus rutas o controladores que requieran autenticación para asegurar el acceso a tu API.

El **CorsMiddleware** es responsable de manejar solicitudes de Cross-Origin Resource Sharing (CORS). Lee la configuración desde las variables de entorno:

- **ALLOW_ALL_ORIGINS**: Configura esto en `true` para permitir solicitudes de cualquier origen durante el desarrollo.
- **ALLOWED_ORIGINS**: Esta variable debe contener una lista separada por comas de los orígenes permitidos que pueden hacer solicitudes a tu API.

## Autor

Esta aplicación backend para **BIBLIOTECA** ha sido desarrollada y es mantenida por:

**William Medina**

¡Gracias por revisar **BIBLIOTECA**!