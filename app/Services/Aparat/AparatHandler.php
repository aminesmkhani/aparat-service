<?php

namespace App\Services\Aparat;

use App\Exceptions\CannotGetFormActionException;
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

    public function upload(string $filename, string $title, int $category)
    {
      $formAction = $this->getUploadForm();

       $formActionUrl = $formAction['formAction'];
       $formId = $formAction['frm-id'];


       $uploadResponse = $this->http::attach(
           'video', file_get_contents(Storage::disk('public')->path($filename)), $filename
       )->post($formActionUrl,[
           [
               'name' => 'frm-id',
               'contents' => $formId
           ],
           [
               'name'   => 'data[title]',
               'contents'  => $title,
           ],
           [
               'name'   => 'data[category]',
               'contents' => $category
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


    private function getUploadForm()
    {
        $url = config('aparat.formUploadUrl');
        $token = $this->getToken();
        $url = str_replace('{user}',$this->user,$url);
        $url = str_replace('{token}',$token,$url);

        $response = $this->http::get($url);

        if (is_null($response->json('uploadform.formAction'))){
                throw new CannotGetFormActionException;
        }

        return $response->json('uploadform');

    }


    public function delete(int $uid)
    {

    }
}
