<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class SummeryController extends Controller
{
    public function index()
    {
        $orders = DB::table('order_summery_view')->get();

        return view('orders-summery', compact('orders'));
    }

    public function weekly()
    {
        $orders = DB::table('weekly_order_summery_view')->get();
        $dayNamesWithDates = $this->getDayNamesWithDates();

        return view('weekly-order-summery', compact('orders', 'dayNamesWithDates'));
    }

    /**
     * @return array
     */
    private function getDayNamesWithDates()
    {
        $dayNamesWithDates = [];

        for ($i = 1; $i <= 7; $i++) {
            $dayNamesWithDates[] = date('l d/m/Y', strtotime(date('Y').'W'.date('W').$i));
        }

        return $dayNamesWithDates;
    }
}
