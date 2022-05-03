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
     * @return string
     * @throws \Exception
     */
    public function execute(string $coin_id): string
    {
        try {
            $this->coinLoreCryptoDataSource->getCoin($coin_id);
        }catch (Exception $exception){
             throw new \Exception($exception->getMessage(),$exception->getCode());
        }

        return "Successful Operation";
    }
}
