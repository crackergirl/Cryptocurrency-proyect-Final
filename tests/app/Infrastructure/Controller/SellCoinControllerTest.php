<?php

namespace Tests\app\Infrastructure\Controller;

use App\Application\CoinLoreCryptoDataSource\CoinLoreCryptoDataSource;
use App\Domain\Coin;
use App\Infrastructure\Cache\WalletCache;
use Illuminate\Http\Response;
use Tests\TestCase;
use Exception;
use Mockery;

class SellCoinControllerTest extends TestCase
{
    private CoinLoreCryptoDataSource $CoinLoreCryptoDataSource;

    /**
     * @setUp
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->CoinLoreCryptoDataSource = Mockery::mock(CoinLoreCryptoDataSource::class);

        $this->app->bind(CoinLoreCryptoDataSource::class, fn () => $this->CoinLoreCryptoDataSource);
    }

    /**
     * @test
     */
    public function genericError()
    {

        $data = ['coin_id' => '90','wallet_id'=>'1', 'amount_usd'=> 0];

        $this->CoinLoreCryptoDataSource
            ->expects('getCoin')
            ->with(90)
            ->once()
            ->andThrow(new Exception('Service unavailable',503));

        $response = $this->post('api/coin/sell', $data);

        $response->assertStatus(Response::HTTP_SERVICE_UNAVAILABLE)->assertExactJson(['error' => 'Service unavailable']);
    }

    /**
     * @test
     */
    public function sellCoinSuccessful()
    {
        $walletCache = new WalletCache();
        $walletCache->open();
        $wallet = $walletCache->get('1');
        $coin = new Coin('90','1','1','1','1',1);
        $wallet->setCoins($coin,'2');
        $walletCache->set('1',$wallet);

        $data = ['coin_id' => '90','wallet_id'=> '1', 'amount_usd'=> 2];

        $this->CoinLoreCryptoDataSource
            ->expects('getCoin')
            ->with(90)
            ->once()
            ->andReturn($coin);

        $response = $this->post('api/coin/sell', $data);

        $response->assertStatus(Response::HTTP_OK)->assertExactJson(['successful operation']);
    }

    /**
     * @test
     */
    public function NumberOfCoinsExceeded()
    {
        $walletCache = new WalletCache();
        $walletCache->open();
        $wallet = $walletCache->get('1');
        $coin = new Coin('90','1','1','1','1',1);
        $wallet->setCoins($coin,'2');
        $walletCache->set('1',$wallet);

        $data = ['coin_id' => '90','wallet_id'=> '1', 'amount_usd'=> 4];

        $this->CoinLoreCryptoDataSource
            ->expects('getCoin')
            ->with(90)
            ->once()
            ->andReturn($coin);

        $response = $this->post('api/coin/sell', $data);

        $response->assertStatus(Response::HTTP_NOT_FOUND)->assertExactJson(['error' => "the quantity has been exceeded, you have 2."]);

    }

}
