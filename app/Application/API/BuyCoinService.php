<?php

namespace App\Application\API;
use App\Application\CoinLoreCryptoDataSource\CoinLoreCryptoDataSource;
use Exception;

class BuyCoinService
{
    private CoinLoreCryptoDataSource $coinLoreCryptoDataSource;

    public function __construct(CoinLoreCryptoDataSource $coinLoreCryptoDataSource)
    {
        $this->coinLoreCryptoDataSource = $coinLoreCryptoDataSource;
    }

    /***
     * @param string $coin_id
     * @param string $wallet_id
     * @param float $amount_usd
     * @return int
     * @throws \Exception
     */
    public function execute(string $coin_id, string $wallet_id,float $amount_usd): string
    {
        //return $this->coinLoreCryptoDataSource->buyCoin($coid_id,$wallet_id,$amount_usd);
        try {
            $this->coinLoreCryptoDataSource->getCoin($coin_id);
        }catch (Exception $exception){
             throw new \Exception($exception->getMessage(),404);;
        }

        return "Successful Operation";
    }
}
