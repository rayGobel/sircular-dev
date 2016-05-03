# Sircular - Magazine sirculation manager

## Introduction

This application was made to replace an older app with
same functionalities. To some people, this won't worth much,
but for us, this will help a lot.

The idea is to manage magazine distribution, deliveries, returns,
invoices and then create report.

## Installation

### Requirements

Server requirements of this application must support for Laravel 5.0 PHP Framework

- PHP >=5.4, PHP <7
- Mcrypt PHP Extension
- OpenSSL PHP Extension
- Mbstring PHP Extension
- Tokenizer PHP Extension

Optional:

- MySQL 5.5
- Apache 2.4

### Composer

If you didn't know composer yet, see: [Composer](https://getcomposer.org)

### Step-by-step guide

Copy this project to your server document/web root

If you have git, simply use:

    $ cd /your/webserver/documentroot/
    $ git clone https://github.com/rayGobel/sircular-dev

Use composer to install dependency.

    $ composer install

Try to run `php artisan` to determine if its working properly

    $ php artisan list

### Troubleshoot

Typical problem that may exist are:

1. Mcrypt is not installed
2. Permission denied for Monolog to write file

### .env setup

Environment setup are necessary for database access and administration.
By default, I use MySQL as database. You can change this setting in
`config/database.php`. Just copy `.env.example` and rename it to `.env`

Find the value of these variable and fill it according to your
database setup

    DB_DATABASE=<database_name>
    DB_USERNAME=<database_root_user>
    DB_PASSWORD=<database_root_user_password>

## License

This application is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
