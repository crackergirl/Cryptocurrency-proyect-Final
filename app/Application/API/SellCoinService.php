<?php

namespace App\Application\API;
use App\Domain\Coin;
use App\Domain\Wallet;
use App\Application\CacheSource\CacheSource;
use Exception;
use Illuminate\Http\Response;

class SellCoinService
{
    private CacheSource $walletCache;

    public function __construct(CacheSource $walletCache)
    {
        $this->walletCache = $walletCache;

    }

    /***
     * @throws Exception
     */
    public function execute(string $coinId,string $walletId,float $amountUsd, Coin $coin): string
    {
        try {
            $wallet = $this->exceptionWallet($coinId,$walletId,$amountUsd);
            $this->sellCoin($coinId, $coin,$walletId,$wallet,$amountUsd);
        }catch (Exception $exception){
            throw new Exception($exception->getMessage(),$exception->getCode());
        }
        return "successful operation";
    }

    /***
     * @throws Exception
     */
    private function exceptionWallet(string $coinId,string $walletId,float $amountUsd): Wallet
    {
        $wallet = $this->walletCache->get($walletId);
        if (!$wallet->existCoin($coinId)){
            throw new Exception('A coin with specified ID was not found.',Response::HTTP_NOT_FOUND);
        }
        $amountCoinWallet = $wallet->getAmountCoinByID($coinId);
        if ($amountCoinWallet < $amountUsd){
            throw new Exception('the quantity has been exceeded, you have '.$amountCoinWallet.'.',Response::HTTP_NOT_FOUND);
        }
        return $wallet;
    }

    private function sellCoin(string $coinId,Coin $actualCoin,string $walletId,Wallet $wallet,float $amountUsd): void
    {
        $actualPriceCoin = $actualCoin->getPriceUsd();
        $wallet->setProfit($wallet->getProfit() + floatval($actualPriceCoin)*$amountUsd);
        $coin = $wallet->getCoinByID($coinId);
        $amountCoinWallet = $wallet->getAmountCoinByID($coinId);
        $wallet->setCoins($coin,$amountCoinWallet - $amountUsd);
        $this->walletCache->set($walletId,$wallet);
    }
}
