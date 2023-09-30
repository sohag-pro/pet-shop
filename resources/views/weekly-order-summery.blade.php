@extends('app')

@section('title', 'Orders Summery')

@section('content')
<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">This weeks weekly order summery</h1>
<p class="mb-4">I assumed week starts on monday</p>

<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Code used to make this view</h6>
    </div>
    <div class="card-body">
        <pre id="layer"></pre>
            <pre id="highlight">
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
        </pre>
    </div>
</div>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">This weeks weekly order summery</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
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
@endsection
