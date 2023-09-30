@extends('app')

@section('title', 'Orders Summery')

@section('content')
<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">This weeks orders summery</h1>
<p class="mb-4">I assumed week starts on monday</p>

<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Code used to make this view</h6>
    </div>
    <div class="card-body">
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
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">This Weeks order summery</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped" id="dataTable">
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
@endsection
