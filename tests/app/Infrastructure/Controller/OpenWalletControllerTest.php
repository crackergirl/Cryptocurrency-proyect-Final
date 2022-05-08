<?php

namespace Tests\app\Infrastructure\Controller;
use App\Infrastructure\Cache\WalletCache;
use Illuminate\Http\Response;
use Tests\TestCase;
use Exception;
use Mockery;

class OpenWalletControllerTest extends TestCase
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
            ->expects('open')
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
        $this->walletCache
            ->expects('open')
            ->once()
            ->andReturn("1");

        $response = $this->post('api/wallet/open');

        $response->assertStatus(Response::HTTP_OK)->assertExactJson(['wallet_id' => "1"]);
    }
}
