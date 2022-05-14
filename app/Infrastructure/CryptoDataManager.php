<?php

namespace App\Infrastructure;

use App\Application\DataSource\CryptoDataSource;
use Illuminate\Http\Response;
use Exception;

class CryptoDataManager implements CryptoDataSource
{
    /***
     * @throws Exception
     */
    public function getCoin(string $coin_id): bool|string
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_USERAGENT => 'Coinlore Crypto Data API',
            CURLOPT_URL => "https://api.coinlore.net/api/ticker/?id=" . $coin_id,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_VERBOSE => true,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_TIMEOUT => 30
        ));
        $curl_execution=curl_exec($curl);
        curl_close($curl);
        if($curl_execution==false)
        {
            throw new Exception('Service unavailable',Response::HTTP_SERVICE_UNAVAILABLE);
        }
        return $curl_execution;
    }

}
