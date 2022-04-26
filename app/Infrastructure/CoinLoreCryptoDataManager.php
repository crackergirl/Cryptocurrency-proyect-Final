<?php

namespace App\Infrastructure;

use App\Application\CoinLoreCryptoDataSource\CoinLoreCryptoDataSource;
use App\Domain\Coin;

class CoinLoreCryptoDataManager implements CoinLoreCryptoDataSource
{
    public function getCoin(string $coin): float
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_USERAGENT => 'Coinlore Crypto Data API',
            CURLOPT_URL => "https://api.coinlore.net/api/ticker/?id=".$coin,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_VERBOSE => true,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_TIMEOUT => 30
        ));

        $coin = json_decode(curl_exec($curl));
        curl_close($curl);

        return $coin[0]->price_usd;
    }
}
