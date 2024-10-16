## Aplicación para la gestión y control de gastos mensuales

La siguiente aplicación fue desarrollada para poner en práctica los conocimientos adquiridos en el tutorial de creación 
de una Api rest con Slim. La idea es complementar estos conceptos con un desarrollo de una pequeña App que permita llevar 
el control de gastos realizados en el mes. 

La aplicación en sí aplica tantos ideas del Framework Slim como metodología de trabajo usada actualmente en mis proyectos 
los cuales aplican técnicas de DDD entre otras. 

La aplicación se divide en 2 partes

- **Backend**: PHP + Slim framework y Mysql como base de datos
- **Frontend**: Html + CSS + Vanilla Javascript.

## Requisitos

### Backend
- PHP 8.0 o superior
- Base de datos Mysql o Mariadb
- Opcional Apache Web server

### Frontend
- Ejecutarlo en un entorno web, como por ejemplo el plugin live-server de VScode

## Instalación del proyecto

- 1 - Clonar repositorio
- 2 - Configurar Backend (BE)
    - 2.1 - Para instalar el backend, situarse en la carpeta y ejecutar: 
    - - ```composer install```
    - 2.2 - Una vez instalada las dependencias, migrar la base de datos (el script se encuentra en la carpeta db) y configurar
credenciales de acceso. 
    - 2.3 - Renombrar el archivo **.env_example** a **.env** y editarlo con los datos de conexión a la base de datos. En el caso de usar live-server de VScode, editar el valor de ***ORIGIN***.
    - 2.4 - Para probar el BE sin Apache, correr el servidor built-in de php con el siguiente commando dentro del directorio backend: 
    - - ```php -S localhost:8080 -t public/```
    - 5.5 - En caso de ejecutarlo en un servidor web Apache, renombrar y editar los archivos .httaccess_example de las carpetas backend y backend/public
- 3 - Configurar FrontEnd (FE)
  - 3.1 - Renombrar el archivo frontend/assets/js/config_example.js a config.js
  - 3.2 - Editar el archivo config.js con la dirección de la url del BE.
  - 3.3 - Ejecutar el plugin live-server y disfrutar

### Nota

Para probar el correcto funcionamiento del BE, se puede utilizar postman u otra herramienta similar.

Si bien cuenta con middleware para evitar el problema de CORS, en caso de contar con un servidor web como apache, se puede 
colocar todo mismo en un subdirectorio. Tener en cuenta también de editar la variable ***APP_PATH*** del archivo .env del BE.

