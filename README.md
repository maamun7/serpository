
## Serpository (Service Repository Pattern in Laravel)
<!--[![Build Status](https://travis-ci.org/maamun7/serpository.svg)](https://travis-ci.org/maamun7/serpository)
[![Version](https://img.shields.io/packagist/v/maamun7/serpository.svg)](https://packagist.org/packages/zizaco/entrust)-->
[![License](https://poser.pugx.org/maamun7/serpository/license.svg)](https://packagist.org/packages/maamun7/serpository)
[![Total Downloads](https://img.shields.io/packagist/dt/maamun7/serpository.svg)](https://packagist.org/packages/maamun7/serpository)

Many of us _Laravel_ developers like to use **Service-Repository** pattern in _Laravel_ applications to develop scalable application. 
So creating these **Service**, **Repository** classes and inject them manually is very time-consuming as well as annoying at times.

I faced this issue several times, that is why I developed this package to make my task easier. I strongly believe that many of us having this issue. For them, I decided to make it public.

This package provides the facility to make **service & repository** together and inject them by an artisan command.
In addition to this, people also will be able to make **service** or **repository** individually. 
More details are given in features section.

### Requirement
This package requires php version >=7.4 and Laravel version >=8

### Installation
1. Install this packages via composer:
```
composer require maamun7/serpository
```
2. Add the **Serpository** Service Provider to the providers array in config/app.php
```
Maamun7\Serpository\SerpositoryServiceProvider::class,
```

### Features
1. Create Service
```
php artisan make:service User
```
If there is no `Services` directory inside `app`, firstly the above command will create that directory and then will make a `Service` class by the provided name with `Service` suffix inside this created `Services` directory. E.g: `UserService`.

2. Create Repository

```
php artisan make:repo User  OR  php artisan make:repository User
```
If there is no `Repositories` directory inside `app`, firstly the above command will create that directory and then will make a `Repository` class by the provided name with  `Repository` suffix inside that created `Repositories/Eloquents` directory. E.g: `UserRepository` .

Besides, the Repository will have an Injected `Interface` with a separate `Interfaces` directory inside the Repository.

Moreover, this command will search a model inside `app\Models` directory by provided repository name and adding some suffix (e.g: suppose the provided name is User then it will search by User, Users, UserModel & User_Model). If there is a model with those names it will inject into the Repository; otherwise it will inject a model with the provided base name.

3. Create Service & Repository with the same name
```
php artisan make:service User --r
```
Flag `--r` will give scope to create a Service as well as a Repository together with the same base name. In addition to this, this created repository will be injected into this service automatically.

4. Create Service & Repository with the different name
```
php artisan make:service User --r --repo=MyUser
```

The above command will create a Service with the name `UserService` & a Repository named `MyUserRepository`. This repository also will be injected into this service automatically.

### How does it work with Controller?
#### The following example shows how it works with controller.
```

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserService;

class UserController extends Controller
{
    protected $userService;

    /**
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        $users = $this->userService->getAll();
    }
}

```


### License
**Serpository** is a free software distributed under the terms of the MIT license.
