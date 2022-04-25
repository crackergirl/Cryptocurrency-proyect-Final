<?php


namespace App\Infrastructure\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Http;

class CryptoDataController extends BaseController
{
    public function __invoke(string $route): JsonResponse
    {
        //$response = Http::get('https://api.coinlore.net/'.$route, []);


    }


}
