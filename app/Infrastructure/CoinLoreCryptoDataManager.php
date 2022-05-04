<?php

namespace App\Infrastructure;
use App\Application\CoinLoreCryptoDataSource\CoinLoreCryptoDataSource;
use App\Domain\Coin;
use App\Domain\Wallet;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Response;
use phpDocumentor\Reflection\Types\Null_;

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
            throw new \Exception('A coin with specified ID was not found.',Response::HTTP_NOT_FOUND);
        }
        $coin_object = new Coin($coin[0]->id, $coin[0]->name, $coin[0]->symbol, 1, $coin[0]->price_usd,$coin[0]->rank);

        return $coin_object;
    }

    /***
     * @return string
     * @throws \Exception
     */
    public function openWallet():string{
         $id_wallet = 1;
         while(Cache::has("wallet".$id_wallet)){
             $id_wallet+=1;
         }
         $wallet = new Wallet($id_wallet);
        Cache::put('wallet'.$id_wallet,$wallet,600);
        return strval($id_wallet);
    }


}
