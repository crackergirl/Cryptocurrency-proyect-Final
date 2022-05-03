<?php

namespace Tests\app\Infrastructure\Controller;

use App\Application\CoinLoreCryptoDataSource\CoinLoreCryptoDataSource;
use Illuminate\Http\Response;
use Tests\TestCase;
use Exception;
use App\Domain\Coin;
use Mockery;

class GetCoinControllerTest extends TestCase
{
    /**
     * @test
     */
    public function genericError()
    {
        $coinLoreCryptoDataSource = Mockery::mock(CoinLoreCryptoDataSource::class);

        $this->app->bind(CoinLoreCryptoDataSource::class, fn () => $coinLoreCryptoDataSource);

        $coinLoreCryptoDataSource
            ->expects('getCoin')
            ->with(90)
            ->once()
            ->andThrow(new Exception('Service unavailable',503));

        $response = $this->get('/api/coin/status/90');

        $response->assertStatus(Response::HTTP_SERVICE_UNAVAILABLE)->assertExactJson(['error' => 'Service unavailable']);
    }

    /**
     * @test
     */
    public function coinWithGivenIdNotExists()
    {

        $response = $this->getJson('/api/coin/status/12345');

        $response->assertStatus(Response::HTTP_NOT_FOUND)->assertExactJson(['error' => 'A coin with specified ID was not found.']);
    }

    /**
     * @test
     */
    public function coinWithGivenIdExists()
    {
        $coinLoreCryptoDataSource = Mockery::mock(CoinLoreCryptoDataSource::class);

        $this->app->bind(CoinLoreCryptoDataSource::class, fn () => $coinLoreCryptoDataSource);

        $coin = new Coin('1','1','1','1','1',1);

        $coinLoreCryptoDataSource
            ->expects('getCoin')
            ->with(90)
            ->once()
            ->andReturn($coin);

        $response = $this->get('/api/coin/status/90');

        $response->assertStatus(Response::HTTP_OK)->assertExactJson(['{"coin_id":"1","name":"1","symbol":"1","name_id":"1","rank":1,"price_usd":"1"}']);
    }
}
