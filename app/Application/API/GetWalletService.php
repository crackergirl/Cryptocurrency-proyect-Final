<?php

namespace App\Application\API;
use App\Application\CoinLoreCryptoDataSource\CoinLoreCryptoDataSource;
use App\Domain\Wallet;
use App\Infrastructure\Cache\WalletCache;
use Exception;

class GetWalletService
{
    private WalletCache $walletCache;

    public function __construct(WalletCache $walletCache)
    {
        $this->walletCache = $walletCache;
    }

    /***
     * @throws Exception
     */
    public function execute(string $wallet_id): Wallet
    {
        try {
            $wallet = $this->walletCache->getWallet($wallet_id);

        }catch (Exception $exception){
            throw new Exception($exception->getMessage(),$exception->getCode());
        }
        return $wallet;
    }

}
