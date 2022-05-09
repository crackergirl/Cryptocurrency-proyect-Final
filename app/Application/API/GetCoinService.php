<?php

namespace App\Application\API;
use App\Application\DataSource\CryptoDataSource;
use App\Domain\Coin;
use Exception;

class GetCoinService
{
    private CryptoDataSource $coinLoreCryptoDataSource;

    public function __construct(CryptoDataSource $coinLoreCryptoDataSource)
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
