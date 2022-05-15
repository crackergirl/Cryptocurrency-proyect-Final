<?php

namespace Tests\app\Infrastructure\Controller;
use App\Application\CacheSource\CacheSource;
use App\Application\DataSource\CryptoDataSource;
use App\Domain\Wallet;
use Illuminate\Http\Response;
use Tests\TestCase;
use Exception;
use Mockery;

class BuyCoinControllerTest extends TestCase
{
    private CacheSource $walletCache;
    private CryptoDataSource $coinLoreCryptoDataManager;

    /**
     * @setUp
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->walletCache = Mockery::mock(CacheSource::class);
        $this->coinLoreCryptoDataManager = Mockery::mock(CryptoDataSource::class);

        $this->app->bind(CacheSource::class, fn () => $this->walletCache);
        $this->app->bind(CryptoDataSource::class, fn () => $this->coinLoreCryptoDataManager);
    }

    /**
     * @test
     */
    public function genericError()
    {
        $data = ['coin_id' => '90','wallet_id'=>'1', 'amount_usd'=> 3];
        $this->coinLoreCryptoDataManager
            ->expects('getCoin')
            ->with('90')
            ->once()
            ->andThrow(new Exception('Service unavailable',503));

        $response = $this->post('api/coin/buy',$data);

        $response->assertExactJson(['error' => 'Service unavailable']);
    }

    /**
     * @test
     */
    public function coinNotExists()
    {
        $data = ['coin_id' => '90','wallet_id'=>'1', 'amount_usd'=> 3];
        $this->coinLoreCryptoDataManager
            ->expects('getCoin')
            ->with('90')
            ->once()
            ->andThrow(new Exception('A coin with specified ID was not found.',404));

        $response = $this->post('api/coin/buy',$data);

        $response->assertStatus(Response::HTTP_NOT_FOUND)->assertExactJson(['error' => 'A coin with specified ID was not found.']);
    }

    /**
     * @test
     */
    public function buyCoinSuccessful()
    {
        $wallet = new Wallet('1');
        $data = ['coin_id' => '90','wallet_id'=>'1', 'amount_usd'=> 3];
        $this->coinLoreCryptoDataManager
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

        $response = $this->post('api/coin/buy',$data);

        $response->assertStatus(Response::HTTP_OK)->assertExactJson(["successful operation"]);
    }
}
