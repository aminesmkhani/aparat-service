<?php

namespace App\Services\Aparat;

use App\Exceptions\CannotGetTokenException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class AparatHandler
{
    private Http $http;
    private $user;
    const TOKEN_EXPIRE_TIME = 1200;

    public function __construct(Http $http)
    {
        $this->http = $http;
        $this->user = config('aparat.user');
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
        $url = config('aparat.loginUrl');
        $url = str_replace('{user}',$this->user, $url);
        $url = str_replace('{password}',$password,$url);

        $response = $this->http::get($url);
        return $response->json('login');
    }

    public function upload()
    {
        $url = config('aparat.formUploadUrl');
       $token = $this->getToken();
       $url = str_replace('{user}',$this->user,$url);
       $url = str_replace('{token}',$token,$url);

       $response = $this->http::get($url);

       $formAction = $response->json('uploadform.formAction');
       $formId = $response->json('uploadform.frm-id');


       $uploadResponse = $this->http::attach(
           'video', file_get_contents(Storage::disk('public')->path('amin.mp4')), 'amin.mp4'
       )->post($formAction,[
           [
               'name' => 'frm-id',
               'contents' => $formId
           ],
           [
               'name'   => 'data[title]',
               'contents'  => 'Aparat Api',
           ],
           [
               'name'   => 'data[category]',
               'contents' => 10
           ]
       ]);

      return $uploadResponse->json('uploadpost');
    }


    private function getToken()
    {
       return Cache::remember('aparat_token',self::TOKEN_EXPIRE_TIME,function (){
            $loginData = $this->login();
            if (array_key_exists('ltoken',$loginData)){
                return $loginData['ltoken'];
            }

            throw new CannotGetTokenException;
        });

    }
}
