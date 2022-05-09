<?php

namespace Tests\app\Infrastructure\Controller;
use App\Domain\Wallet;
use App\Infrastructure\Cache\WalletCache;
use Illuminate\Http\Response;
use Tests\TestCase;
use Exception;
use Mockery;
use App\Domain\Coin;

class GetWalletControllerTest extends TestCase
{
    private WalletCache $walletCache;

    /**
     * @setUp
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->walletCache = Mockery::mock(WalletCache::class);

        $this->app->bind(WalletCache::class, fn () => $this->walletCache);
    }

    /**
     * @test
     */
    public function genericError()
    {
        $this->walletCache
            ->expects('getWallet')
            ->once()
            ->andThrow(new Exception('Service unavailable',503));

        $response = $this->get('api/wallet/1');

        $response->assertStatus(Response::HTTP_SERVICE_UNAVAILABLE)->assertExactJson(['error' => 'Service unavailable']);
    }

    /**
     * @test
     */
    public function walletNotFound()
    {
        $this->walletCache
            ->expects('getWallet')
            ->once()
            ->andThrow(new Exception('a wallet with the specified ID was not found',404));

        $response = $this->get('api/wallet/1');

        $response->assertStatus(Response::HTTP_NOT_FOUND)->assertExactJson(['error' => 'a wallet with the specified ID was not found']);
    }

    /**
     * @test
     */
    public function getWalletSuccessful()
    {
        $coin1= new Coin('1','ethereum','$','1','1564.23',1);
        $coin2= new Coin('2','Dogecoin','%','2','162.65',7);
        $wallet = new Wallet('1');
        $wallet->setCoins($coin1,7);
        $wallet->setCoins($coin2,3);
        $this->walletCache
            ->expects('getWallet')
            ->once()
            ->andReturn($wallet);

        $response = $this->get('api/wallet/1');

        $response->assertStatus(Response::HTTP_OK)->assertExactJson([
            '[{"coin_id":"1","name":"ethereum","symbol":"$","amount":7,"value_usd":1564.23},{"coin_id":"2","name":"Dogecoin","symbol":"%","amount":3,"value_usd":162.65}]'
        ]);
    }
}
