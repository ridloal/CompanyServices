# Lumen PHP Framework

[![Build Status](https://travis-ci.org/laravel/lumen-framework.svg)](https://travis-ci.org/laravel/lumen-framework)
[![Total Downloads](https://img.shields.io/packagist/dt/laravel/framework)](https://packagist.org/packages/laravel/lumen-framework)
[![Latest Stable Version](https://img.shields.io/packagist/v/laravel/framework)](https://packagist.org/packages/laravel/lumen-framework)
[![License](https://img.shields.io/packagist/l/laravel/framework)](https://packagist.org/packages/laravel/lumen-framework)

Laravel Lumen is a stunningly fast PHP micro-framework for building web applications with expressive, elegant syntax. We believe development must be an enjoyable, creative experience to be truly fulfilling. Lumen attempts to take the pain out of development by easing common tasks used in the majority of web projects, such as routing, database abstraction, queueing, and caching.

## License

The Lumen framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## How To Run This Project

### Prerequisite
- Composer
- PHP >= 7.3
- OpenSSL PHP Extension
- PDO PHP Extension
- Mbstring PHP Extension

### Step by Step
1. Clone this repository
2. Open terminal and goto project directory
3. run "composer install" and wait until successful.
4. In project folder duplicate file ".env.example" and rename to ".env".
5. open ".env" file and fill the database credential.
6. run "php artisan key:generate" in the terminal.
7. run "php artisan migrate" to create database migration.
8. run "php artisan db:seed --class=TestingDataSeeder" to create seeder for testing data in table customers and purchase_transactions.
9. run "php artisan db:seed --class=VoucherSeeder" to generate 10 voucher to table vouchers
10. run "php -S 127.0.0.1:8000 -t public" to running the project.
11. Done. The API service is ready.

### API Documentation
Attached on documentation.
