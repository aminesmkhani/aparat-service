<?php

namespace App\Http\Controllers;

use App\Services\Aparat\AparatHandler;
use Illuminate\Http\Request;

class AparatController extends Controller
{
    private AparatHandler $aparat;
    public function __construct(AparatHandler $aparat)
    {
        $this->aparat = $aparat;
    }

    public function index()
    {
     $videos = $this->aparat->mostViewedVideos();

    }

}