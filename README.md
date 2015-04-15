# Order tracking system for SMTank.com
### Front-end and Back-end images


In progress..


### Installation guide

1. Clone the repository
```
$ git clone https://github.com/alfonsomga/smtank-order-tracking
```
2. Create parameters.yml and parameters.yml.dist files in app/config folder (Do not forget to add you database..etc info)
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
3. Run composer
```
$ composer update
```
4. Build database schemas
```
...
```
5. Add a new order through HTTP POST (You can find/edit your secret key in src/OrderTracking/BackendBundle/DefaultController.php)
```
http://mydomain.com/api/crear/{client_name}/{client_email}/{product_name}/{product_price}/{secretkey}
```
6. Create an admin use for back-end zone
```
$ php app/console fos:user:create testuser test@example.com p@ssword
$ php app/console fos:user:promote testuser --super
```
