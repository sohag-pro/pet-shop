<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('CREATE VIEW order_summery_view AS
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
            AND WEEK(o.created_at, 1) = WEEK(CURRENT_DATE)
            GROUP BY o.id
            ORDER BY o.id DESC
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP VIEW order_summery_view');
    }
};
