<?php

namespace App\Infrastructure\Cache;
use App\Domain\Wallet;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use App\Application\CacheSource\CacheSource;
use Exception;

class WalletCache implements CacheSource
{
    /***
     * @throws Exception
     */
    public function get(string $walletId): Wallet
    {
        if(!Cache::has('wallet'.$walletId)){
            throw new Exception('A wallet with specified ID was not found.',Response::HTTP_NOT_FOUND);
        }
        /** @var Wallet $wallet */
        return Cache::get('wallet'.$walletId);
    }

    public function set(string $walletId, Wallet $wallet): bool
    {
        Cache::forget('wallet'.$walletId);
        Cache::put('wallet'.$walletId,$wallet,600);
        return true;
    }

    public function create(string $walletId, Wallet $wallet): bool
    {
        Cache::put('wallet'.$walletId,$wallet,600);
        return true;
    }

    public function exists(string $walletId): bool
    {
        return Cache::has('wallet'.$walletId);
    }


}
