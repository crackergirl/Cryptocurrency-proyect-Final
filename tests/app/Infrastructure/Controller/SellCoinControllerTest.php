<?php

namespace Tests\app\Infrastructure\Controller;

use App\Application\CacheSource\CacheSource;
use App\Application\DataSource\CryptoDataSource;
use App\Domain\Coin;
use App\Domain\Wallet;
use App\Infrastructure\Cache\WalletCache;
use Illuminate\Http\Response;
use Tests\TestCase;
use Exception;
use Mockery;

class SellCoinControllerTest extends TestCase
{
    private CacheSource $walletCache;
    private CryptoDataSource $cryptoDataSource;

    /**
     * @setUp
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->walletCache = Mockery::mock(CacheSource::class);
        $this->cryptoDataSource = Mockery::mock(CryptoDataSource::class);

        $this->app->bind(CacheSource::class, fn () => $this->walletCache);
        $this->app->bind(CryptoDataSource::class, fn () => $this->cryptoDataSource);
    }

    /**
     * @test
     */
    public function genericError()
    {
        $data = ['coin_id' => '90','wallet_id'=>'1', 'amount_usd'=> 3];
        $this->cryptoDataSource
            ->expects('getCoin')
            ->with('90')
            ->once()
            ->andThrow(new Exception('Service unavailable',503));

        $response = $this->post('api/coin/sell',$data);

        $response->assertExactJson(['error' => 'Service unavailable']);
    }

    /**
     * @test
     */
    public function coinNotExists()
    {
        $data = ['coin_id' => '90','wallet_id'=>'1', 'amount_usd'=> 3];
        $wallet = new Wallet('1');
        $this->cryptoDataSource
            ->expects('getCoin')
            ->with('90')
            ->once()
            ->andReturn(json_encode(array(['id' => '90',
                'name' => '1',
                'symbol' => '1',
                'nameid' => '1',
                'price_usd' => '1',
                'rank' => 1])));
        $this->walletCache
            ->expects('get')
            ->with('1')
            ->once()
            ->andReturn($wallet);

        $response = $this->post('api/coin/sell',$data);

        $response->assertStatus(Response::HTTP_NOT_FOUND)->assertExactJson(['error' => 'A coin with specified ID was not found.']);
    }

    /**
     * @test
     */
    public function sellCoinSuccessful()
    {
        $coin = new Coin('90','1','1','1','1',1);
        $wallet = new Wallet('1');
        $wallet->setCoins($coin,4);
        $data = ['coin_id' => '90','wallet_id'=>'1', 'amount_usd'=> 3];
        $this->cryptoDataSource
            ->expects('getCoin')
            ->with('90')
            ->once()
            ->andReturn(json_encode(array(['id' => '90',
                'name' => '1',
                'symbol' => '1',
                'nameid' => '1',
                'price_usd' => '1',
                'rank' => 1])));
        $this->walletCache
            ->expects('get')
            ->with('1')
            ->once()
            ->andReturn($wallet);
        $this->walletCache
            ->expects('set')
            ->with('1',Mockery::on(function($wallet){
                return $wallet->getWalletId() === '1';
            }))
            ->once()
            ->andReturn(true);

        $response = $this->post('api/coin/sell',$data);

        $response->assertStatus(Response::HTTP_OK)->assertExactJson(["successful operation"]);
    }
}
