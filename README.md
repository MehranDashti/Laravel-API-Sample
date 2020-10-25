## About Project

This sample project is implemented using [Laravel](https://laravel.com/) framework and includes these features:

- [JWT](https://jwt.io/) Authentication
- Documented using [Swagger](https://swagger.io/)
- [Docker](https://www.docker.com/)
- [PHPUnit](https://phpunit.de/)

## Installation
```
$ cp .env.docker .env
$ docker-compose up -d
$ docker-compose exec app composer install
$ docker-compose exec app php artisan migrate --seed
$ docker-compose exec app php artisan passport:install
```
