<p align="center"><img width="250" height="128" src="http://i.imgur.com/AtFS9Ie.png"/></p>
# Order tracking system for SMTank.com
### Information
Project done with PHP Symfony2 2.6.5 framework.
### Front-end and Back-end images


In progress..


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
