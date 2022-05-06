<?php

namespace App\Application\API;
use App\Application\CoinLoreCryptoDataSource\CoinLoreCryptoDataSource;
use App\Domain\Coin;
use Exception;

class GetCoinService
{
    private CoinLoreCryptoDataSource $coinLoreCryptoDataSource;

    public function __construct(CoinLoreCryptoDataSource $coinLoreCryptoDataSource)
    {
        $this->coinLoreCryptoDataSource = $coinLoreCryptoDataSource;
    }

    /***
     * @throws Exception
     */
    public function execute(string $coin_id): Coin
    {
        try {
            $coin = $this->coinLoreCryptoDataSource->getCoin($coin_id);

        }catch (Exception $exception){
            throw new Exception($exception->getMessage(),$exception->getCode());
        }
        return $coin;
    }
}
