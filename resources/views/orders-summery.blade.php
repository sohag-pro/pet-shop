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
        <link rel="stylesheet" href="{{asset('style.css')}}">
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
                <pre id="layer"></pre>
                <pre id="highlight">
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
ORDER BY o.id DESC
                </pre>
            </div>
        </div>

        <div class="row mb-5">
            <div class="col-12 text-center table-responsive">
                <h2>This weeks order summery</h2>
                <small>Week Starts on Monday</small>
                <table class="table table-striped">
                    <tr>
                        <td>Order ID</td>
                        <td>Customer Name</td>
                        <td>Customer Email</td>
                        <td>Customer Phone</td>
                        <td>Order Total</td>
                        <td>Order Date</td>
                        <td>Payment Method</td>
                        <td>Status</td>
                        <td>Products</td>
                    </tr>
                    @forelse ($orders as $order)
                        <tr>
                            <td> {{ $order->order_id }} </td>
                            <td> {{ $order->user_name }} </td>
                            <td> {{ $order->user_email }} </td>
                            <td> {{ $order->user_phone }} </td>
                            <td> {{ $order->amount }} </td>
                            <td> {{ $order->created_at }} </td>
                            <td> {{ $order->payment_method }} </td>
                            <td style="text-transform: capitalize"> {{ $order->order_status }}</td>
                            <td>
                                <table class="table table-striped table-dark">
                                    <tr>
                                        <td>Product Name</td>
                                        <td>Product Price</td>
                                        <td>Product Quantity</td>
                                    </tr>
                                    @foreach (json_decode($order->order_products) as $cart)
                                        <tr>
                                            <td> {{ $cart->product->title }} </td>
                                            <td> {{ $cart->product->price }} </td>
                                            <td> {{ $cart->quantity }} </td>
                                        </tr>
                                    @endforeach
                                </table>
                            </td>
                        </tr>
                    @empty

                    @endforelse
                </table>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"
        integrity="sha512-egJ/Y+22P9NQ9aIyVCh0VCOsfydyn8eNmqBy+y2CnJG+fpRIxXMS6jbWP8tVKp0jp+NO5n8WtMUAnNnGoJKi4w=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{asset('script.js')}}"></script>
</body>

</html>
