<?php

namespace App\Application\API;
use App\Application\CoinLoreCryptoDataSource\CoinLoreCryptoDataSource;
use Exception;

class OpenWalletService
{
    private CoinLoreCryptoDataSource $coinLoreCryptoDataSource;

    public function __construct(CoinLoreCryptoDataSource $coinLoreCryptoDataSource)
    {
        $this->coinLoreCryptoDataSource = $coinLoreCryptoDataSource;
    }

    /***
     * @return string
     * @throws \Exception
     */
    public function execute(): string
    {
        try {
            return $this->coinLoreCryptoDataSource->openWallet();
        }catch (Exception $exception){
            throw new \Exception($exception->getMessage(),$exception->getCode());
        }
    }
}
