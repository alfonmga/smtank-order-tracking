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
    
2. **Segunda opción**:

    Lee la sección <a href="#API-REST">API REST</a> para ver cómo añadir un pedido.
    
    Esta opción es perfecta para integrar el sistema con PayPal, Stripe..etc y dar de altas pedidos nuevos al recibir
    un pago.
    
# API REST
Para utilizar la API necesitas autentificarte en cada solicitud que realices.

Necesitarás enviar en el header de cada solicitud lo siguiente: **'api_key' = tuapikeysecreta**

Puedes encontrar tu api key secreta en la base de datos (columna **api_key** de la tabla **fos_users**).
## Añadiendo un pedido al sistema
Envía una solicitud **POST** a **/api/v1/pedido** con el siguiente contenido (ejemplo):
```json
{
  "pedido": {
    "nombreCliente": "Alfonso M.",
    "emailCliente": "hello@alfonsomga.com",
    "nombreProducto": "Posicionamiento web",
    "precioProducto": "129,99"
  }
}
```
La respuesta que deberías recibir es la siguiente:
```json
{
  "id": 1,
  "fecha_inicio": "2015-08-01T00:05:55+0200",
  "nombre_cliente": "Alfonso M.",
  "email_cliente": "hello@alfonsomga.com",
  "nombre_producto": "Posicionamiento web",
  "precio_producto": "129,99",
  "estado_pedido": "pendiente",
  "codigo_seguimiento": "B3Y8XVVG28M8"
}
```
También podrás encontrar en el header el parámetro 'Location' el cual contiene URL desde la cual 
podrás acceder al recurso que has creado ( http://127.0.0.1:8000/api/v1/pedido/B3Y8XVVG28M8) en este caso.
## Editando el estado de un pedido
Para editar el estado de un pedido necesitaremos enviar una solicitud **PUT** a 
**/api/v1/pedido/K8QIV1KM6KYN** con el siguiente contenido:
```json
{
  "pedido": {
   "estadoPedido": "en progreso"
  }
}
```
En este caso hemos cambiado el estado del pedido a "en progreso".
## Eliminando un pedido
Envía una solicitud **DELETE** a **/api/v1/pedido/K8QIV1KM6KYN**

Si el pedido ha sido eliminado la respuesta debería ser la siguiente:
```json
{
  "response": "eliminado"
}
```
# Comandos
### Añadir pedidos demo
```command
app/console pedidos:add:demo
```
![Command añadir pedido](https://i.imgur.com/LfLyu8f.png)
### Eliminar todos los pedidos de la BBDD (no funciona en producción)
```command
app/console pedidos:remove:all
```
![Command borrar pedido](https://i.imgur.com/NVIJAGw.png)