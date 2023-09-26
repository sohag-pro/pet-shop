<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Order Summery</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css"
        integrity="sha512-b2QcS5SsA8tZodcDtGRELiGv5SaKSk1vDHDaQRda0htPYWZ6046lr3kJ5bAAQdpV2mmA/4v0wQF9MyU6/pDIAg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <div class="container">
        <nav>
            <ul class="nav">
                <li class="nav-item">
                    <a class="nav-link" style="font-size: 40px" href="/">Pet Shop</a>
                </li>
            </ul>
        </nav>

        <div class="row">
            <div class="col-12">
                <h2>Code used to make this view</h2>
                <p>
                    Note: As it's not possible to create a view with dynamic column names, we have to use a backend script to generate the view with dynamic column names.
                </p>
                <pre>
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
                    GROUP BY
                    row_num;
                </pre>
            </div>
        </div>

        <div class="row mb-5">
            <div class="col-12 text-center table-responsive">
                <h2>This weeks weekly order summery</h2>
                <small>Week Starts on Monday</small>
                <table class="table table-striped">
                    <tr>
                        @foreach ($dayNamesWithDates as $dayName)
                            <td>{{ $dayName }}</td>
                        @endforeach
                    </tr>
                    @forelse ($orders as $dayOrders)
                        <tr>
                            <td style="white-space: nowrap; font-size: 12px;"> {{ $dayOrders->Monday }} </td>
                            <td style="white-space: nowrap; font-size: 12px;"> {{ $dayOrders->Tuesday }} </td>
                            <td style="white-space: nowrap; font-size: 12px;"> {{ $dayOrders->Wednesday }} </td>
                            <td style="white-space: nowrap; font-size: 12px;"> {{ $dayOrders->Thursday }} </td>
                            <td style="white-space: nowrap; font-size: 12px;"> {{ $dayOrders->Friday }} </td>
                            <td style="white-space: nowrap; font-size: 12px;"> {{ $dayOrders->Saturday }} </td>
                            <td style="white-space: nowrap; font-size: 12px;"> {{ $dayOrders->Sunday }} </td>
                        </tr>
                    @empty
                    @endforelse
                </table>
            </div>
        </div>
    </div>
</body>

</html>
