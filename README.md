## About Serpository
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
If there is no `Services` directory inside `app`, firstly the above command will create that directory and then inside this created `Services` directory will make a `Service` class with provided name, e.g: `UserService`

## License
Repository-generator is a free software distributed under the terms of the MIT license.
