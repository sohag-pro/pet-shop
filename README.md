<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

## About Pet Shop

It's a laravel pseudo-project. Available at [pet-shop.sohag.pro](https://pet-shop.sohag.pro/)

## Index

-   [Installation](#installation)
    -   [Traditional](#traditional-way)
    -   [Sail](#sail-way-docker)
-   [Challenges](#challenges)
    -   [SQL Queries and Helpers](#sql-queries-and-helpers)
        -   [Improve the Pet Shop web-based application's ER diagram](#improve-the-pet-shop-web-based-applications-er-diagram)
        -   [Write an SQL statement to create a view that will be updated daily showing all the orders for the current week including products, clients, payments and status details.](#write-an-sql-statement-to-create-a-view-that-will-be-updated-daily-showing-all-the-orders-for-the-current-week-including-products-clients-payments-and-status-details)
        -   [Additionally, create a second view where we can visualize a weekly report with 7 columns, each column will be labeled with the day and date, and each row will show a concatenation of these elements and they will be separated by a double colon ::](#additionally-create-a-second-view-where-we-can-visualize-a-weekly-report-with-7-columns-each-column-will-be-labeled-with-the-day-and-date-and-each-row-will-show-a-concatenation-of-these-elements-and-they-will-be-separated-by-a-double-colon-)
    -   [HTML email template](#html-email-template)
        -   [Email Template Preview](#email-template-preview)
        -   [Code to create the html email template with Handlebars](#code-to-create-the-html-email-template-with-handlebars)
        -   [How to test the email template?](#how-to-test-the-email-template)
    -   [Client Issue Resolution](#client-issue-resolution)
        -   [Case 1](#case-1)
        -   [Case 2](#case-2)
        -   [Case 3](#case-3)
        -   [Case 4](#case-4)
    -   [JsonLogic (bonus)](#jsonlogic-bonus)
        -   [a) There is a due amount AND the purchase was made after January 1st, 2021, AND the shipping country is whether Croatia or Italy.](#a-there-is-a-due-amount-and-the-purchase-was-made-after-january-1st-2021-and-the-shipping-country-is-whether-croatia-or-italy)
        -   [b) Same logic as “a)” but using some different variable(s) from the purchase data.](#b-same-logic-as-a-but-using-some-different-variables-from-the-purchase-data)
        -   [c) Create a rule to send internal emails based on 4 custom conditions (for you to choose) where at least 2 of 4 are TRUE.](#c-create-a-rule-to-send-internal-emails-based-on-4-custom-conditions-for-you-to-choose-where-at-least-2-of-4-are-true)

## Installation

-   Clone the repository
    ```bash
    git clone https://github.com/sohag-pro/pet-shop.git
    ```
-   Install composer dependencies
    ```bash
    composer install
    ```
-   Create a copy of your .env file
    ```bash
    cp .env.example .env
    ```
-   Generate an app encryption key
    ```bash
    php artisan key:generate
    ```

#### [Traditional](https://laravel.com/docs/10.x#your-first-laravel-project) way

-   Create an empty database for our application
-   In the .env file, add database information to allow Laravel to connect to the database
    ```bash
    DB_CONNECTION=mysql
    DB_HOST=
    DB_PORT=
    DB_DATABASE=
    DB_USERNAME=
    DB_PASSWORD=
    ```
-   Migrate the database
    ```bash
    php artisan migrate
    ```
-   Seed the database
    ```bash
    php artisan db:seed
    ```
-   Run the application
    ```bash
    php artisan serve
    ```

#### [Sail](https://laravel.com/docs/10.x/sail) way (Docker)

-   Install [Docker](https://docs.docker.com/get-docker/)
-   If you want to use `sail` command, other than `./vendor/bin/sail`, then add the following line to your ~/.bashrc or ~/.zshrc file:
    ```bash
    alias sail='[ -f sail ] && sh sail || sh vendor/bin/sail'
    ```
-   Run the application
    ```bash
    sail up
    ```
-   Migrate the database
    ```bash
    sail artisan migrate
    ```
-   Seed the database
    ```bash
    sail artisan db:seed
    ```

## Challenges

### SQL Queries and Helpers

#### Improve the Pet Shop web-based application's ER diagram

![Improve the ER Diagram](https://raw.githubusercontent.com/sohag-pro/pet-shop/main/er_product_feature_update.jpg)

##### How this is gonna work?

-   To have multiple sub-categories for a product, I created a new table named `product_category` with `product_id` and `category_id` columns. So, a product can have multiple categories. it's a many-to-many relationship.

-   To have multiple sub-category for a category, I added a new column named `parent_id` in the `categories` table. So, a category can have multiple sub-categories. it's a one-to-many relationship.
    -   how to know if a category is a sub-category or not?
        -   if `parent_id` is null, then it's a parent category
        -   if `parent_id` is not null, then it's a sub-category

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
        CONCAT(u.first_name, " ", u.last_name) AS user_name,
        u.email AS user_email,
        u.phone_number AS user_phone,
        o.products AS order_products
    FROM orders o
    JOIN order_statuses os ON os.id = o.order_status_id
    JOIN payments p ON p.id = o.payment_id
    JOIN users u ON u.id = o.user_id
    WHERE YEAR(o.created_at) = YEAR(CURRENT_DATE)
    AND WEEK(o.created_at, 1) = WEEK(CURRENT_DATE, 1)
    ORDER BY o.id DESC
```

I've added a simple url to view the data: [/orders-summery](https://pet-shop.sohag.pro/orders-summery). it's also available in the home page.

#### Additionally, create a second view where we can visualize a weekly report with 7 columns, each column will be labeled with the day and date, and each row will show a concatenation of these elements and they will be separated by a double colon ::

-   order_uuid
-   number of products
-   order amount in cents

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

I've added a simple url to view the data: [/weekly-orders-summery](https://pet-shop.sohag.pro/weekly-orders-summery). it's also available in the home page.

### HTML email template

#### Email Template Preview

![Email Template](https://raw.githubusercontent.com/sohag-pro/pet-shop/main/email_template.jpg)

#### Code to create the html email template with Handlebars

I've added an extra `@` before the handlebars variables to avoid the conflict with the blade template engine.

```html
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Invoice</title>
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link
            href="https://fonts.googleapis.com/css2?family=Mukta:wght@400;500;600;700;800&display=swap"
            rel="stylesheet"
        />
    </head>

    <body style="font-family: 'Mukta', sans-serif;">
        <div id="template">
            <table style="width: 620px; margin: 0 auto;">
                <tr>
                    <td>
                        <img src="/header.png" alt="" />
                    </td>
                </tr>
                <tr>
                    <td>
                        <p
                            style="font-family: 'Poppins', sans-serif; line-height: 30px;"
                        >
                            Dear @{{fullname user}}, We are contacting you because there is an <i>amount due</i> on your purchase @{{purchase.id}}
                        </p>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 30px;">
                        <table
                            style="width: 100%; border: none; border-collapse: collapse;"
                        >
                            <thead>
                                <tr>
                                    <th
                                        colspan="3"
                                        style="text-align: center; background-color: #352f4b; color: white; padding: 15px; border-radius: 10px 10px 0px 0px; font-size: large; letter-spacing: 1px;"
                                    >
                                        Purchase Summary
                                    </th>
                                </tr>
                                <tr>
                                    <td
                                        style="text-align: left; padding: 10px 5px;"
                                    >
                                        Date:
                                    </td>
                                    <td colspan="2" style="text-align: left;">
                                        @{{format created_at}}
                                    </td>
                                </tr>
                            </thead>
                            <tbody id="productsCompiled"></tbody>
                            <tfoot>
                                <tr>
                                    <td></td>
                                    <td
                                        style="padding: 10px 0px; font-weight: bolder;"
                                    >
                                        Total amount
                                    </td>
                                    <td
                                        style="text-align: right; padding-right: 5px; white-space: nowrap;"
                                    >
                                        @{{amount}} <b>Kn</b>
                                    </td>
                                </tr>
                                <tr
                                    style="background-color: #ededed; border: 1px solid #cdcdcd; border-left: none; border-right: none;"
                                >
                                    <td></td>
                                    <td
                                        style="padding: 10px 0px; font-weight: bolder;"
                                    >
                                        Amount paid
                                    </td>
                                    <td
                                        style="text-align: right; padding-right: 5px;"
                                    >
                                        @{{paidAmount amount payment}} <b>Kn</b>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p>
                            According to our records, the <i>amount due</i> is
                            @{{dueAmount amount payment}} Kn. Please, click on
                            the next button <b>to pay</b> this difference:
                        </p>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center; padding: 30px;">
                        <a
                            href="https://pet-shop.buckhill.com.hr/checkout"
                            style="background-color: #352f4b; padding: 10px 50px; border-radius: 50px; text-decoration: none; color: white; text-transform: uppercase;"
                            >Pay Now</a
                        >
                    </td>
                </tr>
                <tr>
                    <td>
                        <p>
                            If you have any other concerns, please contact our
                            technical support team.
                        </p>
                        <p>Kind regards,</p>
                        <p>Petson Team</p>
                    </td>
                </tr>
            </table>
        </div>

        <script id="products" type="text/x-handlebars-template">

            @{{#each products}}
                <tr
                    style="background-color: #ededed; border: 1px solid #cdcdcd; border-left: none; border-right: none;"
                >
                    <td style="padding: 10px 5px;">@{{this.quantity}}x</td>
                    <td>@{{this.product}}</td>
                    <td
                        style="text-align: right; padding-right: 5px; white-space: nowrap;"
                    >@{{this.price}} Kn</td>
                </tr>
                @{{/each}}
        </script>

        <!-- Include Handlebars from a CDN -->
        <script src="https://cdn.jsdelivr.net/npm/handlebars@latest/dist/handlebars.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
        <script>
            Handlebars.registerHelper("dueAmount", function (total, payment) {
            if( payment == null ) {
                return total;
            }
                return 0;
            });
    
            Handlebars.registerHelper("paidAmount", function (total, payment) {
                if( payment == null ) {
                    return 0;
                }
                return total;
            });
    
            Handlebars.registerHelper("format", function (datetime) {
                return moment(datetime).format('MMMM Do YYYY, h:mm:ss a');
            });

            Handlebars.registerHelper("fullname", function (user) {
                if (user.middle_name == null) {
                    return user.last_name + " " + user.first_name;
                }
                return user.last_name + " " + user.first_name + " " + user.middle_name;
            });

            var data = {!!json_encode($order)!!}; // this is the data from the controller

            var products = Handlebars.compile(document.getElementById("products").innerHTML);

            document.getElementById("productsCompiled").innerHTML = products(data);

            var template = Handlebars.compile(document.getElementById("template").innerHTML);

            document.getElementById("template").innerHTML = template(data);
        </script>
    </body>
</html>
```

#### How to test the email template?

-   Click on the [Orders](https://pet-shop.sohag.pro/orders) card on home page
-   Click on the `invoice` button of any order

### Client Issue Resolution

#### Case 1

**Issue**: The client has brought to our attention an issue concerning the final price displayed during their purchase. They bought one item for 35Kn and two items for 20Kn each, but the final price shown on the screen was 85Kn.

**Solution**: Steps to address the issue:

-   Gather more information about the issue
    -   What is the actual final price?
    -   What is the actual price of each item?
    -   What is the actual quantity of each item?
    -   What is the actual total price of each item?
-   Validate the pricing logic
    -   if there was any recent change in the pricing logic?
-   Collaborate with the team to find the root cause
    -   Check the pricing logic in the code
    -   Check the pricing logic in the database
    -   Check the pricing logic in the UI
    -   Check the pricing logic in the API
-   Communicate with the Client
    -   Explain the root cause
    -   if the issue was on our side, then apologize for the inconvenience and adjust the final price accordingly by refunding the extra amount
-   Implement preventive measures
    -   work with the team to implement the solution
    -   work with the team to implement the steps to prevent this issue in the future
    -   conduct a thorough testing to make sure the issue is resolved
-   Follow-up with the client
    -   make sure the issue is resolved
    -   make sure the client is satisfied with the solution
    -   gather feedback from the client

#### Case 2

**Issue**: A client has reported difficulty in logging in despite providing the correct username and password combination. In addition, when attempting to reset the password through the "Forgot password?" feature, they are not receiving the recovery email with the link.

**Solution**: Steps to address the issue:

-   Verify login credentials
    -   Confirm with the client that they are entering the correct username and password combination. Ensure they have not inadvertently made any typographical errors.
-   Check for any recent changes
    -   Check if there was any recent change in the login logic
    -   Check if there was any recent change in the password reset logic
-   Check for account lockout
    -   Check if the account is locked out
    -   Check if the account is disabled
-   Check for any recent changes in the email
    -   Check if there was any recent change in the email service
    -   Check if there was any recent change in the email template
-   Assist the client in resetting their password
    -   if the client is unable to reset their password, then reset the password for them with admin privileges and send them the new password securely
-   Communicate with the Client
    -   Maintain a professional and courteous tone throughout the troubleshooting process
    -   Provide clear and concise instructions
-   Implement preventive measures
    -   work with the team to implement the solution
    -   work with the team to implement the steps to prevent this issue in the future
    -   conduct a thorough testing to make sure the issue is resolved
-   Follow-up with the client
    -   make sure the issue is resolved
    -   make sure the client is satisfied with the solution
    -   gather feedback from the client

#### Case 3

**Issue**: A customer is encountering issues during the payment stage, with the screen freezing when attempting to complete the process, despite trying all available payment methods.

**Solution**: Steps to address the issue:

-   Gather more information about the issue
    -   What is the actual issue?
    -   What is the actual error message?
    -   What is the actual payment method?
    -   What is the actual payment gateway?
    -   What is the actual browser?
    -   What is the actual device?
    -   What is the actual operating system?
-   Validate the payment methods are working
    -   if there was any recent change in the payment logic?
    -   monitor payment logs
    -   check for payment gateway issues
-   Check for technical issues
    -   Check if there was any recent change in the payment gateway
    -   Check if there was any recent change in the payment service
    -   Check if there was any recent change in the payment API
    -   Check if there was any recent change in the payment UI
    -   check for server issues and downtimes
    -   monitor server logs
-   Test the payment methods
    -   Test the payment methods in the same browser
    -   Test the payment methods in different browsers
    -   Test the payment methods in different devices
    -   Test the payment methods in different operating systems
-   Temporary solution
    -   if the issue was on our side, then provide a temporary solution to the client like manual payment or payment through a different payment gateway
-   Communicate with the Client
    -   Explain the root cause
    -   Keep the client updated with the progress
-   Implement preventive measures
    -   work with the team to implement the solution
    -   work with the team to implement the steps to prevent this issue in the future
    -   conduct a thorough testing to make sure the issue is resolved
-   Follow-up with the client
    -   make sure the issue is resolved
    -   make sure the client is satisfied with the solution
    -   gather feedback from the client

#### Case 4

**Issue**: A customer has reported receiving the "Amount Due" email as expected. However, they mentioned that the wording hasn't been displayed correctly, preventing them from seeing the actual amount they need to pay. Instead, the email only reads "{amunt_due}."

**Solution**: Steps to address the issue:

-   Gather more information about the issue
    -   What is the actual issue?
-   Validate the email template
    -   if there was any recent change in the email template?
    -   Check for variable typos
-   Test the email template
    -   Test the email template in different browsers
    -   Test the email template in different devices
    -   Test the email template in different operating systems
-   Communicate with the Client
    -   Explain the root cause
    -   Keep the client updated with the progress
-   Implement preventive measures
    -   work with the team to implement the solution
    -   work with the team to implement the steps to prevent this issue in the future
    -   conduct a thorough testing to make sure the issue is resolved
-   Follow-up with the client
    -   make sure the issue is resolved
    -   make sure the client is satisfied with the solution
    -   gather feedback from the client

### JsonLogic (bonus)

For this task, you will need to build on top of that previous work and create a JSON logic rule that uses the information from the orders endpoints to determine whether the email should be sent to the customer, considering specific conditions. The rule should return TRUE if:

#### a) There is a due amount AND the purchase was made after January 1st, 2021, AND the shipping country is whether Croatia or Italy.

**Rule**

```json
{
    "and": [
        { ">=": [{ "var": "dueAmount" }, 1] },
        { ">=": [{ "var": "purchaseDate" }, "2021-01-01"] },
        { "in": [{ "var": "shippingCountry" }, ["Croatia", "Italy"]] }
    ]
}
```

**Explanation**

-   `dueAmount` is the amount due
-   `purchaseDate` is the date of purchase
-   `shippingCountry` is the shipping country

**Example**

```json
{
    "dueAmount": 5,
    "purchaseDate": "2021-05-01",
    "shippingCountry": "Croatia"
}
```

#### b) Same logic as “a)” but using some different variable(s) from the purchase data.

**Rule**

```json
{
    "and": [
        { ">=": [{ "var": "dueAmount" }, 1] },
        { ">=": [{ "var": "purchaseDate" }, "2021-01-01"] },
        { "in": [{ "var": "shippingCountry" }, ["Croatia", "Italy"]] },
        { "in": [{ "var": "paymentMethod" }, ["Credit Card", "PayPal"]] }
    ]
}
```

**Explanation**

-   `dueAmount` is the amount due
-   `purchaseDate` is the date of purchase
-   `shippingCountry` is the shipping country
-   `paymentMethod` is the payment method

**Example**

```json
{
    "dueAmount": 5,
    "purchaseDate": "2021-05-01",
    "shippingCountry": "Croatia",
    "paymentMethod": "Credit Card"
}
```

#### c) Create a rule to send internal emails based on 4 custom conditions (for you to choose) where at least 2 of 4 are TRUE.

**Rule**

```json
{
    ">=": [
        {
            "+": [
                { "var": "condition1" },
                { "var": "condition2" },
                { "var": "condition3" },
                { "var": "condition4" }
            ]
        },
        2
    ]
}
```

**Explanation**

-   `condition1` is the first condition
-   `condition2` is the second condition
-   `condition3` is the third condition
-   `condition4` is the fourth condition

**Example**

```json
{
    "condition1": 1,
    "condition2": 0,
    "condition3": 0,
    "condition4": 1
}
```
