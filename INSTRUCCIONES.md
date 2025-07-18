Requisitos:
- XAMPP (incluye Apache, MySQL y phpMyAdmin)
- PHP >= 8.0
- Composer
- Node.js y npm

---

Instrucciones para ejecutar el proyecto

1. Clonar el repositorio

2. Instalar dependencias PHP:
   composer install

3. Instalar dependencias JavaScript:
   npm install

4. Copiar el archivo .env en la raíz del proyecto.

5. Configurar la base de datos en el archivo .env si no aparece como debe,
(usuario, contraseña, nombre de la base de datos).

6. Importar la base de datos:
En phpMyAdmin, crea una base de datos con el nombre: spotify, Ve a la pestaña Importar y selecciona el archivo spotify.sql

7. Genera la clave de la aplicación (si la pide):
   php artisan key:generate

8. Ejecuta migraciones y seeders (si es necesario):
   php artisan migrate --seed

9. Inicia el servidor de desarrollo:
   php artisan serve

10. Accede a la aplicación:
    - Abre http://127.0.0.1:8000.

**Configuración de la API de Spotify**

1. Registrar aplicación en el portal de desarrolladores de Spotify:
   - entrar a https://developer.spotify.com/dashboard
   - Crea una nueva aplicación, copia el Client ID y Client Secret.
   - Configura el Redirect URI en el panel de Spotify exactamente igual que en tu archivo .env (por ejemplo, http://127.0.0.1:8000/spotify/callback).

2. Verifica que las siguientes variables estén correctamente configuradas en tu archivo .env:
   env
   SPOTIFY_CLIENT_ID=tu_client_id
   SPOTIFY_CLIENT_SECRET=tu_client_secret
   SPOTIFY_REDIRECT_URI=http://127.0.0.1:8000/spotify/callback

3. La primera vez que se accede a la funcionalidad de Spotify, deberás autorizar con alguna cuenta de Spotify.