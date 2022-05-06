<?php

namespace App\Infrastructure;
use App\Application\CoinLoreCryptoDataSource\CoinLoreCryptoDataSource;
use App\Domain\Coin;
use App\Domain\Wallet;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Response;
use Exception;

class CoinLoreCryptoDataManager implements CoinLoreCryptoDataSource
{
    /***
     * @param string $coin_id
     * @return Coin
     * @throws \Exception
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

    /***
     *  @param string $coin_id
     * @param string $wallet_id
     * @param float $amount_usd
     * @return string
     * @throws \Exception
     * @var Wallet $wallet
     */
    public function buyCoin(string $coin_id,string $wallet_id,float $amount_usd):string{
        if(!Cache::has('wallet'.$wallet_id)){
            throw new Exception('A wallet with specified ID was not found.',Response::HTTP_NOT_FOUND);
        }
        try {
            $coin = $this->getCoin($coin_id);
            /** @var Wallet $wallet */
            $wallet = Cache::get('wallet'.$wallet_id);
            $wallet->setCoins($coin,$amount_usd);
            $wallet->setExpenses($wallet->getExpenses() + floatval($coin->getPriceUsd())*$amount_usd);
            Cache::forget('wallet'.$wallet_id);
            Cache::put('wallet'.$wallet_id,$wallet,600);
            return "successful operation";
        }catch (Exception $exception){
            throw new Exception($exception->getMessage(),$exception->getCode());
        }
    }

    /***
     * @return string
     * @throws \Exception
     */
    public function openWallet():string{
        $wallet_id = 1;
         while(Cache::has('wallet'.$wallet_id)){
             $wallet_id+=1;
         }
         $wallet = new Wallet($wallet_id);
        Cache::put('wallet'.$wallet_id,$wallet,600);
        return strval($wallet_id);
    }


}
