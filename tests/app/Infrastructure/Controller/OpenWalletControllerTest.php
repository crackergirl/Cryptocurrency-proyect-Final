<?php

namespace Tests\app\Infrastructure\Controller;
use App\Application\CoinLoreCryptoDataSource\CoinLoreCryptoDataSource;
use Illuminate\Http\Response;
use Tests\TestCase;
use Exception;
use Mockery;

class OpenWalletControllerTest extends TestCase
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
        $this->CoinLoreCryptoDataSource
            ->expects('openWallet')
            ->once()
            ->andThrow(new Exception('Service unavailable',503));

        $response = $this->post('api/wallet/open');

        $response->assertStatus(Response::HTTP_SERVICE_UNAVAILABLE)->assertExactJson(['error' => 'Service unavailable']);
    }

    /**
     * @test
     */
    public function openWalletSuccessful()
    {
        $this->CoinLoreCryptoDataSource
            ->expects('openWallet')
            ->once()
            ->andReturn("1");

        $response = $this->post('api/wallet/open');

        $response->assertStatus(Response::HTTP_OK)->assertExactJson(['wallet_id' => "1"]);
    }
}
