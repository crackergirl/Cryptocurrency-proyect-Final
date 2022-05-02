<?php

namespace App\Infrastructure;
use App\Application\CoinLoreCryptoDataSource\CoinLoreCryptoDataSource;
use App\Domain\Coin;

class CoinLoreCryptoDataManager implements CoinLoreCryptoDataSource
{
     public function getCoin(string $coin): Coin
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
        $coin_object = new Coin($coin[0]->id, $coin[0]->name, $coin[0]->symbol, 1, $coin[0]->price_usd,$coin[0]->rank);

        return $coin_object;
    }

    public function buyCoin(): int
    {
        //POST fields
        $post_fields = [
            'coin_id' => '90',
            'wallet_id' => '1',
            'amount_usd' => 0,
        ];

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.coinlore.net/api/ticker/?id=90",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_VERBOSE => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($post_fields),
        ));

        $final_results = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "Laravel cURL Error #:" . $err;
        } else {
            print_r(json_decode($final_results));
        }

        return int($final_results);
    }

}
