<?php

namespace App\Infrastructure;
use App\Application\CoinLoreCryptoDataSource\CoinLoreCryptoDataSource;
use App\Domain\Coin;

class CoinLoreCryptoDataManager implements CoinLoreCryptoDataSource
{
    /***
     * @param string $coin
     * @return Coin
     * @throws \Exception
     */
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
        if(empty($coin)){
            throw new \Exception('A coin with specified ID was not found.');
        }
        $coin_object = new Coin($coin[0]->id, $coin[0]->name, $coin[0]->symbol, 1, $coin[0]->price_usd,$coin[0]->rank);

        return $coin_object;
    }

    public function buyCoin($coid_id,$wallet_id,$amount_usd): int
    {
        //POST fields
        $post_fields = [
            'coin_id' => $coid_id,
            'wallet_id' => $wallet_id,
            'amount_usd' => $amount_usd,
        ];

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.coinlore.net/api/ticker/?id=".$coid_id,
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

        return intval($final_results);
    }

}
