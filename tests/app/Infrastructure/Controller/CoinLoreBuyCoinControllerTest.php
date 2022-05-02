<?php


namespace Tests\app\Infrastructure\Controller;
use App\Application\CoinLoreCryptoDataSource\CoinLoreCryptoDataSource;
use Illuminate\Http\Response;
use Tests\TestCase;
use Exception;
use App\Domain\Coin;
use Mockery;

class CoinLoreBuyCoinControllerTest extends TestCase
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
        $data = ['90', '1', 0];

        $headers = ['coin_id', 'wallet_id', 'amount_usd'];

        $this->coinLoreCryptoDataSource
            ->expects('buyCoin')
            ->once()
            ->andThrow(new Exception('Service unavailable',503));

        $response = $this->post('api/coin/buy', $data, $headers);

        $response->assertStatus(Response::HTTP_SERVICE_UNAVAILABLE)->assertExactJson(['error' => 'Service unavailable']);
    }
}
