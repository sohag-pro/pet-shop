<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Orders</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css"
        integrity="sha512-b2QcS5SsA8tZodcDtGRELiGv5SaKSk1vDHDaQRda0htPYWZ6046lr3kJ5bAAQdpV2mmA/4v0wQF9MyU6/pDIAg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ asset('style.css') }}">
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

        <div class="row mb-5">
            <div class="col-12 text-center">
                <h2>Orders List</h2>
                <div class="table-responsive">
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
                            <td>Invoice</td>
                        </tr>
                        @forelse ($response->data as $order)
                            <tr>
                                <td> {{ $order->uuid }} </td>
                                <td> {{ $order->user->first_name }} {{ $order->user->last_name }} </td>
                                <td> {{ $order->user->email }} </td>
                                <td> {{ $order->user->phone_number }} </td>
                                <td> {{ $order->amount }}Kn </td>
                                <td style="white-space: nowrap"> {{ now()->parse($order->created_at)->toDateString() }}
                                </td>
                                <td> {{ $order->payment?->type ?? 'n/a' }} </td>
                                <td style="text-transform: capitalize"> {{ $order->order_status[0]->title }}</td>
                                <td> <a target="_blank" href="{{route('invoice', $order->uuid)}}">Invoice</a> </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9">No orders found</td>
                            </tr>
                        @endforelse
                    </table>
                </div>
                @php
                    /*
                    {
                    "current_page": 1,
                    "first_page_url": "http://pet-shop.buckhill.com.hr/api/v1/orders?page=1",
                    "from": 1,
                    "last_page": 5,
                    "last_page_url": "http://pet-shop.buckhill.com.hr/api/v1/orders?page=5",
                    "next_page_url": "http://pet-shop.buckhill.com.hr/api/v1/orders?page=2",
                    "path": "http://pet-shop.buckhill.com.hr/api/v1/orders",
                    "per_page": 10,
                    "prev_page_url": null,
                    "to": 10,
                    "total": 50
                    */
                @endphp

                <style>
                    .pagination {
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        margin-top: 20px;
                    }

                    .pagination a {
                        padding: 10px;
                        margin: 0 5px;
                        border-radius: 5px;
                        text-decoration: none;
                        color: #352f4b;
                        background-color: #ededed;
                    }

                    .pagination a.active {
                        background-color: #352f4b;
                        color: white;
                    }
                </style>
                <div class="pagination">
                    {{-- Previous button --}}
                    <a href="?page={{ $prev_page }}" >
                        Previous
                    </a>
                    @for ($i = 1; $i <= $response->last_page; $i++)
                        <a href="?page={{ $i }}"
                            class="{{ $response->current_page == $i ? 'active' : '' }}">
                            {{ $i }}
                        </a>
                    @endfor
                    {{-- Next button --}}
                    <a href="?page={{ $next_page }}">
                        Next
                    </a>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"
        integrity="sha512-egJ/Y+22P9NQ9aIyVCh0VCOsfydyn8eNmqBy+y2CnJG+fpRIxXMS6jbWP8tVKp0jp+NO5n8WtMUAnNnGoJKi4w=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{ asset('script.js') }}"></script>
</body>

</html>
