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

#### [Traditional](https://laravel.com/docs/10.x#your-first-laravel-project) way
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
#### [Sail](https://laravel.com/docs/10.x/sail) way (Docker)
- Install [Docker](https://docs.docker.com/get-docker/)
- If you want to use `sail` command, other than `./vendor/bin/sail`, then add the following line to your ~/.bashrc or ~/.zshrc file:
    ```bash
    alias sail='[ -f sail ] && sh sail || sh vendor/bin/sail'
    ```
- Run the application
    ```bash
    sail up
    ```
- Migrate the database
    ```bash
    sail artisan migrate
    ```
- Seed the database
    ```bash
    sail artisan db:seed
    ```

## Challenges
### SQL Queries and Helpers

#### Improve the Pet Shop web-based application's ER diagram
![Improve the ER Diagram](https://raw.githubusercontent.com/sohag-pro/pet-shop/main/er_product_feature_update.jpg)
##### How this is gonna work?
- To have multiple sub-categories for a product, I created a new table named `product_category` with `product_id` and `category_id` columns. So, a product can have multiple categories. it's a many-to-many relationship.

- To have multiple sub-category for a category, I added a new column named `parent_id` in the `categories` table. So, a category can have multiple sub-categories. it's a one-to-many relationship.    
    -  how to know if a category is a sub-category or not? 
        - if `parent_id` is null, then it's a parent category
        - if `parent_id` is not null, then it's a sub-category