<?php

namespace App\Services\Aparat;

use Illuminate\Support\Facades\Http;

class AparatHandler
{
    private Http $http;
    public function __construct(Http $http)
    {
        $this->http = $http;
    }

    public function mostViewedVideos()
    {
        $url = 'https://www.aparat.com/etc/api/mostviewedvideos';
        $response = $this->http::get($url);
        dd($response->json());
    }
}
