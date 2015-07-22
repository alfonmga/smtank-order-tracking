[![Build Status](https://travis-ci.org/alfonsomga/smtank-order-tracking.svg?branch=master)](https://travis-ci.org/alfonsomga/smtank-order-tracking) [![Code Climate](https://codeclimate.com/github/alfonsomga/smtank-order-tracking/badges/gpa.svg)](https://codeclimate.com/github/alfonsomga/smtank-order-tracking)
<p align="center"><img width="250" height="128" src="http://i.imgur.com/AtFS9Ie.png"/></p>
# Sistema de seguimiento de pedidos para SMTank.com
### Información
Este proyecto ha sido desarrollado con Symfony2 (Versión 2.6.10).


Ver en producción: **http://pedidos.smtank.com** (Utiliza el siguiente código de seguimiento para ver un ejemplo: **JOT6CN57664C**).
# Capturas de imagen
---------------------------------

![Homepage](http://i.imgur.com/cokxVgl.png)
![Back-end order management](http://i.imgur.com/4kFHL2a.png)
![Back-end logs](http://i.imgur.com/DaPUXkz.png)
![Order information](http://i.imgur.com/Jl6UF0N.png)
![404 Page](http://i.imgur.com/BevHFhK.png)




# Instrucciones para instalar la aplicación
----------------------
### Paso 1: Clonar el repositorio
```
$ git clone https://github.com/alfonsomga/smtank-order-tracking
```
### Paso 2: Crear el archivo parameters.yml.dist en el directorio app/config <sub>(No te olvides de incluir la información de tu base de datos, mailer..etc)</sub>
```
parameters:
    database_driver: pdo_mysql
    database_host: 127.0.0.1
    database_port: '3306'
    database_name: order_tracking
    database_user: ~
    database_password: ~
    mailer_transport: smtp
    mailer_host: 127.0.0.1
    mailer_user: ~
    mailer_password: ~
    locale: es
    secret: ThisTokenIsNotSoSecretChangeIt
    database_path: null
    apikey: miApiKeySecretaAqui
```
### Paso 3: Permisos
Los directorios **app/cache/** y **app/logs/** necesitan permisos de escritura.
Sigue estas instrucciones para dar los permisos de escritura según tu entorno de desarrollo: http://symfony.es/documentacion/como-solucionar-el-problema-de-los-permisos-de-symfony2/
### Paso 4: Ejecuta composer
*En este paso necesitas tener instalado [composer](https://getcomposer.org/download/) e instalado globalmente.
```
$ sudo composer install
```
### Paso 5: Construir esquemas de la base de datos
```
$ php app/console doctrine:database:create
$ php app/console doctrine:schema:create
```
### Paso 6: Crea un usuario Admin para el Back-End
```
$ php app/console fos:user:create Usuario prueba@ejemplo.com p@ssword
$ php app/console fos:user:promote Usuario --super
```
Acceso a la zona Back-end desde: http://127.0.0.1:8000/backend
### Paso 7: Añade un pedido al sistema
Existen dos formas para añadir pedidos al sistema:

1. **Primera opción**:

    Entra en el Back-end y haz click en el botón "**Añadir nuevo pedido**":
    
    ![Add order](https://i.imgur.com/Ef7Jvlg.png)
    
    Esta opción es manual.

2. **Segunda opción**:
    
    Añade pedidos desde una simple API.
    
    URL Para realizar el POST:
    ```
    http://127.0.0.1:8000/api/crear/{nombre}/{email}/{nombreproducto}/{precio}/{secretkey}
    ```
    - {nombre}: Nombre del cliente
    - {email}: E-mail del cliente
    - {nombreproducto}: Nombre del producto que ha comprado
    - {precio}: Precio del producto
    - {secretkey}: contraseña/key de acceso a la api (se encuentra en parameters.yml)
    
    Ejemplo:
    ```
    http://smtank.dev/smtank-order-tracking/web/api/crear/Alfonso M./hello@alfonsomga.com/1000 Seguidores de Twitter/9,99/miApiKeySecretaAqui
    ```
    ![API Response](https://i.imgur.com/cgJxve7.png)
    
    En caso de que el pedido haya sido añadido correctamente la respuesta en formato JSON debería ser siempre la siguiente:
    ```json
    {
      "estado": "success",
      "codigoSeguimiento": "JOT6CN57664C"
    }
    ```
    
    Si el api key con el cual has hecho POST es incorrecto la respuesta es la siguiente:
    
    ```json
    {
      "estado": "access denied"
    }
    ```

----------------------
Esto es todo, si tienes cualquier duda o problema házmelo saber 😉
