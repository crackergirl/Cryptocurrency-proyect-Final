<?php

namespace Tests\app\Infrastructure\Controller;

use App\Application\DataSource\CryptoDataSource;
use Illuminate\Http\Response;
use Tests\TestCase;
use Exception;
use App\Domain\Coin;
use Mockery;

class GetCoinControllerTest extends TestCase
{
    private CryptoDataSource $coinLoreCryptoDataSource;

    /**
     * @setUp
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->coinLoreCryptoDataSource = Mockery::mock(CryptoDataSource::class);

        $this->app->bind(CryptoDataSource::class, fn () => $this->coinLoreCryptoDataSource);
    }

    /**
     * @test
     */
    public function genericError()
    {
        $this->coinLoreCryptoDataSource
            ->expects('getCoin')
            ->with('90')
            ->once()
            ->andThrow(new Exception('Service unavailable',503));

        $response = $this->get('/api/coin/status/90');

        $response->assertStatus(Response::HTTP_SERVICE_UNAVAILABLE)->assertExactJson(['error' => 'Service unavailable']);
    }

    /**
     * @test
     */
    public function coinExists()
    {
        $coin = new Coin('90','1','1','1','1',1);

        $this->coinLoreCryptoDataSource
            ->expects('getCoin')
            ->with('90')
            ->once()
            ->andReturn(json_encode(array(['id' => '90',
                'name' => '1',
                'symbol' => '1',
                'nameid' => '1',
                'price_usd' => '1',
                'rank' => 1])));

        $response = $this->get('/api/coin/status/90');

        $response->assertStatus(Response::HTTP_OK)->assertExactJson(['{"coin_id":"90","name":"1","symbol":"1","name_id":"1","rank":1,"price_usd":"1"}']);
    }

    /**
     * @test
     */
    public function coinNotExists()
    {

        $this->coinLoreCryptoDataSource
            ->expects('getCoin')
            ->with('90')
            ->once()
            ->andThrow(new Exception('A coin with specified ID was not found.',404));

        $response = $this->get('/api/coin/status/90');

        $response->assertStatus(Response::HTTP_NOT_FOUND)->assertExactJson(['error' => 'A coin with specified ID was not found.']);
    }
}
