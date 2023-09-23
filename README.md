<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

## About Pet Shop

It's a laravel pseudo-project

## Installation

- Clone the repository
    ```bash
    git clone https://github.com/sohag-pro/pet-shop.git
    ```
- Install composer dependencies
    ```bash
    composer install
    ```
- Create a copy of your .env file
    ```bash
    cp .env.example .env
    ```
- Generate an app encryption key
    ```bash
    php artisan key:generate
    ```
- Create an empty database for our application
- In the .env file, add database information to allow Laravel to connect to the database
    ```bash
    DB_CONNECTION=mysql
    DB_HOST=
    DB_PORT=
    DB_DATABASE=
    DB_USERNAME=
    DB_PASSWORD=
    ```
- Migrate the database
    ```bash
    php artisan migrate
    ```
- Seed the database
    ```bash
    php artisan db:seed
    ```
- Run the application
    ```bash
    php artisan serve
    ```