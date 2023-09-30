@extends('app')

@section('title', 'Orders Summery')

@section('content')
<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">This weeks orders summery</h1>
<p class="mb-4">Get all the orders from API</p>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">This Weeks order summery</h6>
    </div>
    <div class="card-body">
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
@endsection
