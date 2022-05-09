<?php
namespace App\Infrastructure\Cache;
use App\Domain\Wallet;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Exception;

class WalletCache
{
    public function open():string//servicio
    {
        $wallet_id = 1;
        while(Cache::has('wallet'.$wallet_id)){
            $wallet_id+=1;
        }
        $wallet = new Wallet($wallet_id);
        Cache::put('wallet'.$wallet_id,$wallet,600);
        return strval($wallet_id);
    }

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

    public function getBalance(string $wallet_id): float//servicio
    {
        try {
            $wallet = $this->get($wallet_id);
        } catch (Exception) {
            throw new Exception('A wallet with specified ID was not found.',Response::HTTP_NOT_FOUND);
        }
        return ($wallet->getProfit() - $wallet->getExpenses());
    }

    public function getWallet(string $wallet_id): Wallet
    {
        try{
            $wallet = $this->get($wallet_id);
        } catch (Exception) {
            throw new Exception('A wallet with specified ID was not found.',Response::HTTP_NOT_FOUND);
        }
        return $wallet;
    }

}
