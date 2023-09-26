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


#### Write an SQL statement to create a view that will be updated daily showing all the orders for the current week including products, clients, payments and status details.

![Order Summery](https://raw.githubusercontent.com/sohag-pro/pet-shop/main/order_summery.jpg)

Note: I assumed that the current week starts from Monday and ends on Sunday.

```sql
    CREATE VIEW order_summery_view AS
    SELECT
        o.id AS order_id,
        o.amount,
        o.shipped_at,
        o.created_at,
        o.updated_at,
        os.title AS order_status,
        p.type AS payment_method,
        concat(u.first_name, " ", u.last_name) AS user_name,
        u.email AS user_email,
        u.phone_number AS user_phone,
        o.products AS order_products 
    FROM orders o
    JOIN order_statuses os ON os.id = o.order_status_id
    JOIN payments p ON p.id = o.payment_id
    JOIN users u ON u.id = o.user_id
    WHERE YEAR(o.created_at) = YEAR(CURRENT_DATE)
    AND WEEK(o.created_at, 1) = WEEK(CURRENT_DATE, 1)
    GROUP BY o.id
    ORDER BY o.id DESC
```

I've added a simple url to view the data: `/orders-summery`. it's also available in the home page.

#### Additionally, create a second view where we can visualize a weekly report with 7 columns, each column will be labeled with the day and date, and each row will show a concatenation of these elements and they will be separated by a double colon ::
- order_uuid
- number of products
- order amount in cents

![Weekly Order Summery](https://raw.githubusercontent.com/sohag-pro/pet-shop/main/weekly_order_summery.jpg)

```sql
CREATE VIEW weekly_order_summery_view AS
SELECT
MAX(CASE WHEN day_and_date = 'Mon' COLLATE utf8mb4_general_ci THEN order_details END) AS Monday,
MAX(CASE WHEN day_and_date = 'Tue' COLLATE utf8mb4_general_ci THEN order_details END) AS Tuesday,
MAX(CASE WHEN day_and_date = 'Wed' COLLATE utf8mb4_general_ci THEN order_details END) AS Wednesday,
MAX(CASE WHEN day_and_date = 'Thu' COLLATE utf8mb4_general_ci THEN order_details END) AS Thursday,
MAX(CASE WHEN day_and_date = 'Fri' COLLATE utf8mb4_general_ci THEN order_details END) AS Friday,
MAX(CASE WHEN day_and_date = 'Sat' COLLATE utf8mb4_general_ci THEN order_details END) AS Saturday,
MAX(CASE WHEN day_and_date = 'Sun' COLLATE utf8mb4_general_ci THEN order_details END) AS Sunday
FROM (
SELECT
    day_and_date,
    order_details,
    ROW_NUMBER() OVER (PARTITION BY day_and_date ORDER BY order_id) AS row_num
FROM (
    SELECT
        DATE_FORMAT(o.created_at, '%a') COLLATE utf8mb4_general_ci AS day_and_date,
        o.id AS order_id,
        CONCAT(
            o.id, 
            '::', 
            SUM(CAST(JSON_UNQUOTE(JSON_EXTRACT(product_data.json_element, '$.quantity')) AS UNSIGNED)), 
            '::', 
            ROUND(o.amount * 100)
        ) AS order_details
    FROM
        orders o
    JOIN
        JSON_TABLE(
            JSON_EXTRACT(o.products, '$[*]'),
            '$[*]' COLUMNS (
                json_element JSON PATH '$'
            )
        ) AS product_data
        ON 1 = 1
    WHERE
        YEARWEEK(o.created_at, 1) = YEARWEEK(NOW(), 1)
    GROUP BY
        day_and_date, o.id
) AS subquery
WHERE order_details IS NOT NULL
) AS pivot_data
GROUP BY row_num;
```
I've added a simple url to view the data: `/weekly-orders-summery`. it's also available in the home page.
