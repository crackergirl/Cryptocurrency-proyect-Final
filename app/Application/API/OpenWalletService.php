<?php

namespace App\Application\API;
use App\Domain\Wallet;
use App\Application\CacheSource\CacheSource;
use Exception;
use Illuminate\Support\Facades\Cache;

class OpenWalletService
{
    private CacheSource $walletCache;

    public function __construct(CacheSource $walletCache)
    {
        $this->walletCache = $walletCache;
    }

    /***
     * @throws Exception
     */
    public function execute(): string
    {
        try {
            return $this->open();

        }catch (Exception $exception){
            throw new Exception($exception->getMessage(),$exception->getCode());
        }


    }

    public function open():string
    {
        $wallet_id = 1;

        while(Cache::has('wallet'.$wallet_id)){
            $wallet_id+=1;
        }

        $wallet = new Wallet($wallet_id);
        $this->walletCache->set($wallet_id,$wallet);

        return strval($wallet_id);
    }
}
