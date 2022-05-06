<?php

namespace App\Infrastructure;
use App\Application\CoinLoreCryptoDataSource\CoinLoreCryptoDataSource;
use App\Domain\Coin;
use Illuminate\Http\Response;
use Exception;

class CoinLoreCryptoDataManager implements CoinLoreCryptoDataSource
{

    /***
     * @throws Exception
     */
     public function getCoin(string $coin_id): Coin
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

        $coin = json_decode(curl_exec($curl));
        curl_close($curl);
        if(empty($coin)){
            throw new Exception('A coin with specified ID was not found.',Response::HTTP_NOT_FOUND);
        }
        $coin_object = new Coin($coin[0]->id, $coin[0]->name, $coin[0]->symbol, 1, $coin[0]->price_usd,$coin[0]->rank);

        return $coin_object;
    }

}
