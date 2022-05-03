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

    private CoinLoreCryptoDataSource $coinLoreCryptoDataSource;
    /**
     * @setUp
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->coinLoreCryptoDataSource = Mockery::mock(CoinLoreCryptoDataSource::class);

        $this->app->bind(CoinLoreCryptoDataSource::class, fn () => $this->coinLoreCryptoDataSource);
    }

    /**
     * @test
     */
    public function genericError()
    {

        $this->coinLoreCryptoDataSource
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

        $this->coinLoreCryptoDataSource
            ->expects('getCoin')
            ->with(90)
            ->once()
            ->andThrow(new Exception('A coin with the specified ID was not found',404));

        $response = $this->get('/api/coin/status/90');

        $response->assertStatus(Response::HTTP_NOT_FOUND)->assertExactJson(['error' => 'A coin with the specified ID was not found']);
    }

    /**
     * @test
     */
    public function coinWithGivenIdExists()
    {
        $coin = new Coin('1','1','1','1','1',1);

        $this->coinLoreCryptoDataSource
            ->expects('getCoin')
            ->with(90)
            ->once()
            ->andReturn($coin);

        $response = $this->get('/api/coin/status/90');

        $response->assertStatus(Response::HTTP_OK)->assertExactJson(['{"coin_id":"1","name":"1","symbol":"1","name_id":"1","rank":1,"price_usd":"1"}']);
    }
}
