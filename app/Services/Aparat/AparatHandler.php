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
        $url = config('aparat.mostViewedVideosUrl');
        $response = $this->http::get($url);
        return $response->json('mostviewedvideos');
    }

    public function login()
    {
        $password = config('aparat.password');
        $user = config('aparat.user');
        $url = config('aparat.loginUrl');

        $url = str_replace('{user}',$user, $url);
        $url = str_replace('{password}',$password,$url);

        $response = $this->http::get($url);

        return $response->json('login');

    }
}
