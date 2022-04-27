<?php


namespace Tests\app\Infrastructure\Controller;

use App\Application\CoinLoreCryptoDataSource\CoinLoreCryptoDataSource;
use Illuminate\Http\Response;
use Tests\TestCase;
use Exception;
use Mockery;

class CryptoDataControllerTest extends TestCase
{

    /**
     * @test
     */
    public function genericError()
    {
        $this->coinLoreCryptoDataSource = Mockery::mock(CoinLoreCryptoDataSource::class);
        $this->app->bind(CoinLoreCryptoDataSource::class, fn () => $this->coinLoreCryptoDataSource);

        $this->coinLoreCryptoDataSource
            ->expects('getCoin')
            ->with(90)
            ->once()
            ->andThrow(new Exception('Service unavailable'));

        $response = $this->get('/api/coin/status/90');

        $response->assertStatus(Response::HTTP_BAD_REQUEST)->assertExactJson(['error' => 'Service unavailable']);
    }

}
