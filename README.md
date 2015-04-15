<p align="center"><img width="250" height="128" src="http://i.imgur.com/AtFS9Ie.png"/></p>
# Order tracking system for SMTank.com
### Information
Project done with PHP Symfony2 version 2.6.5 framework.


Demo live: http://pedidos.smtank.com (Use demo order tracking code: JOT6CN57664C).
### Front-end and Back-end images
### <sub>- Homepage</sub>
<img src="http://i.imgur.com/iyUsbiI.png">
### <sub>- 404 Order tracking code not found</sub>
<img src="http://i.imgur.com/382o0Lu.png">
### <sub>- Order information</sub>
<img src="http://i.imgur.com/y7SXdEV.png">
### <sub>- Back-end orders management</sub>
<img src="http://i.imgur.com/u89JUL9.png">
### <sub>- Back-end view/edit order details</sub>
<img src="http://i.imgur.com/lDS5Ntk.png">




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
    database_port: '8889'
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
### Step 3: Run composer
```
$ composer update
```
### Step 4: Build database schemas
```
$ php app/console doctrine:schema:create
```
### Step 5: Add a new order through HTTP POST <sub>(You can find/edit your secret key in src/OrderTracking/BackendBundle/DefaultController.php)</sub>
```
http://mydomain.com/api/crear/{client_name}/{client_email}/{product_name}/{product_price}/{secretkey}
```
### Step 6: Create an admin user for back-end zone
```
$ php app/console fos:user:create testuser test@example.com p@ssword
$ php app/console fos:user:promote testuser --super
```
