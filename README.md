<p align="center"><img width="250" height="128" src="http://i.imgur.com/AtFS9Ie.png"/></p>
# Order tracking system for SMTank.com
### Information
Project done with PHP Symfony2 version 2.6.5 framework.


Live demo: http://pedidos.smtank.com (Use demo order tracking code: JOT6CN57664C).
### Screenshots (click to enlarge)
---------------------------------

### Front-end
![Homepage](http://i.imgur.com/iyUsbiI.png)
![404 Page](http://i.imgur.com/382o0Lu.png)
![Order information](http://i.imgur.com/y7SXdEV.png)
### Back-end
![Back-end order management](http://i.imgur.com/sKXNWTT.png)
![Back-end edit order](http://i.imgur.com/Bt9hOUD.png)




### Installation guide

### Step 1: Clone the repository
```
$ git clone https://github.com/alfonsomga/smtank-order-tracking
```
### Step 2: Create parameters.yml and parameters.yml.dist files in app/config folder <sub>(Do not forget to add you database..etc info)</sub>
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
    locale: en
    secret: ThisTokenIsNotSoSecretChangeIt
    database_path: null
```
### Step 3: Permissions
Folder app/cache/ and app/logs/ needs write permissions.

Please follow this instructions: http://symfony.com/doc/current/book/installation.html#book-installation-permissions
### Step 4: Run composer
*This step requires have downloaded and installed [composer](https://getcomposer.org/download/) globally.
```
$ sudo composer install
```
### Step 5: Build database schemas
```
$ php app/console doctrine:database:create
$ php app/console doctrine:schema:create
```
### Step 6: Add a new order through HTTP POST <sub>(You can find/edit your secret key in src/OrderTracking/BackendBundle/DefaultController.php)</sub>
```
http://mydomain.com/api/crear/{client_name}/{client_email}/{product_name}/{product_price}/{secretkey}
```
Example successful response:
```
{
"estado": "success",
"codigoSeguimiento": "JOT6CN57664C"
}
```
### Step 7: Create an admin user for back-end zone
```
$ php app/console fos:user:create testuser test@example.com p@ssword
$ php app/console fos:user:promote testuser --super
```

Access to back-end admin panel: http://mydomain.com/backend

That's all, if you have any problem let me know and I'll be happy to help you. 😉
