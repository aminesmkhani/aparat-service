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
        return $response->json('mostviewedvideos');
    }

    public function login()
    {
        $password = sha1(md5('aparatpass'));
        $user = 'aparatusername';
        $url = 'https://www.aparat.com/etc/api/login/luser/{user}/lpass/{password}';

        $url = str_replace('{user}',$user, $url);
        $url = str_replace('{password}',$password,$url);

        $response = $this->http::get($url);

        return $response->json('login');

    }
}
