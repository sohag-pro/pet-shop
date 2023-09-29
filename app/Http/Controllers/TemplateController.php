<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Cache;

class TemplateController extends Controller
{
    /**
     * @param  array  $headers
     * @param  array  $options
     */
    private function sendRequest($end_point, $method = 'GET', $headers = [], $options = [])
    {
        $client = new Client();
        $base_url = env('API_BASE_URL', 'http://pet-shop.buckhill.com.hr/api/v1/');
        $request = new Request($method, "$base_url$end_point", $headers);
        $res = $client->sendAsync($request, $options)->wait();
        $response = $res->getBody()->getContents();

        return json_decode($response);
    }

    /**
     * @return mixed
     */
    public function getToken($force = false)
    {
        if (Cache::has('token') && ! $force) {
            return Cache::get('token');
        }

        $headers = [
            'Content-Type' => 'application/x-www-form-urlencoded',
        ];
        $options = [
            'form_params' => [
                'email' => env('API_EMAIL', 'admin@buckhill.co.uk'),
                'password' => env('API_PASSWORD', 'admin'),
            ]];

        $response = $this->sendRequest('admin/login', 'POST', $headers, $options);

        $token = $response->data->token;

        Cache::put('token', $token, 60);

        return $token;
    }

    public function orders()
    {
        $current_page = request()->get('page') ?? 1;
        $headers = [
            'Authorization' => 'Bearer '.$this->getToken(),
        ];

        $options = [
            'query' => [
                'page' => $current_page,
            ],
        ];

        $response = $this->sendRequest('orders', 'GET', $headers, $options);
        $next_page = $response->current_page + 1 <= $response->last_page ? $response->current_page + 1 : $response->last_page;
        $prev_page = $response->current_page - 1 > 0 ? $response->current_page - 1 : $response->current_page;

        return view('orders', compact('response', 'prev_page', 'next_page'));
    }
}
