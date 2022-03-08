
## About Serpository (Service Repository in Laravel Application)
Generate service, repositories and inject for scalable laravel application.

## Requirement
This package requires php version >=7.4 and Laravel version >=8

## Installation
 1. Install this packages via composer: 

```bash
composer require maamun7/serpository
```

## Features
1. Create Service
```bash
php artisan make:service User
```
If there is no `Services` directory inside `app`, firstly the above command will create that directory and then will make a `Service` class by the provided name with `Service` suffix inside this created `Services` directory. E.g: `UserService`.

2. Create Repository

```bash
php artisan make:repo User  OR  php artisan make:repositoy User
```
If there is no `Repositories` directory inside `app`, firstly the above command will create that directory and then will make a `Repository` class by the provided name with  `Repository` suffix inside that created `Repositories` directory. E.g: `UserRepository` .

Besides, the Repository will have an Injected `Interface` with a separate `Interfaces` directory inside the Repository.

Moreover, this command will search a model inside `app\Models` directory by Repository base name and adding some suffix (e.g: suppose the provided name is User then it will search by User, Users, UserModel & User_Model). If there is a model with those names it will inject into the Repository; otherwise it will inject a model with the provided base name.

3. Create Service & Repository with the same name
```bash
php artisan make:service User --r
```
Flag `--r` will give scope to create a Service as well as a Repository together with the same base name. In addition to this, this created repository will be injected into this service automatically.

4. Create Service & Repository with the different name
```bash
php artisan make:service User --r --repo=MyUser
```

The above command will create a Service with the name `UserService` & a Repository named `MyUserRepository`. This repository also will be injected into this service automatically.

## Using example with Controller
![](/var/www/serpository/images/Screenshot-Controller.png)


## License
Serpository is a free software distributed under the terms of the MIT license.
