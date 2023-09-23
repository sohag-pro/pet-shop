<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SummeryController extends Controller
{
    public function index()
    {
        $orders = DB::table('order_summery_view')->get();

        return view('orders-summery', compact('orders'));
    }
}
