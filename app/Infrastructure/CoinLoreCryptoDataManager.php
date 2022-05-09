<?php

namespace App\Infrastructure;
use App\Application\DataSource\CryptoDataSource;
use App\Domain\Coin;
use Illuminate\Http\Response;
use Exception;

class CoinLoreCryptoDataManager implements CryptoDataSource
{

    /***
     * @throws Exception
     */
     public function getCoin(string $coin_id): Coin//1clases
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_USERAGENT => 'Coinlore Crypto Data API',
            CURLOPT_URL => "https://api.coinlore.net/api/ticker/?id=".$coin_id,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_VERBOSE => true,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_TIMEOUT => 30
        ));
        $curl_execution=curl_exec($curl);
        if($curl_execution==false)
        {
            throw new Exception('Service unavailable',Response::HTTP_SERVICE_UNAVAILABLE);
        }
        $coin = json_decode($curl_execution);
        curl_close($curl);
        if(empty($coin)){
            throw new Exception('A coin with specified ID was not found.',Response::HTTP_NOT_FOUND);
        }

        return new Coin($coin[0]->id, $coin[0]->name, $coin[0]->symbol, 1, $coin[0]->price_usd,$coin[0]->rank);
    }

}
