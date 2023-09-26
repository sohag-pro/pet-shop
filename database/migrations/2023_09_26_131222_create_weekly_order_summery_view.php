<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
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
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP VIEW weekly_order_summery_view');
    }
};
