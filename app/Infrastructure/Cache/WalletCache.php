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
    public function get(string $wallet_id): Wallet
    {
        if(!Cache::has('wallet'.$wallet_id)){
            throw new Exception('A wallet with specified ID was not found.',Response::HTTP_NOT_FOUND);
        }
        /** @var Wallet $wallet */
        return Cache::get('wallet'.$wallet_id);
    }

    public function set(string $wallet_id, Wallet $wallet): void
    {
        Cache::forget('wallet'.$wallet_id);
        Cache::put('wallet'.$wallet_id,$wallet,600);
    }


}
