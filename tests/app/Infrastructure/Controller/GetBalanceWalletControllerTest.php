<?php


namespace Tests\app\Infrastructure\Controller;
use App\Infrastructure\Cache\WalletCache;
use Illuminate\Http\Response;
use Tests\TestCase;
use Exception;
use Mockery;

class GetBalanceWalletControllerTest extends TestCase
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
            ->expects('getBalance')
            ->once()
            ->andThrow(new Exception('Service unavailable',503));

        $response = $this->get('api/wallet/1/balance');

        $response->assertStatus(Response::HTTP_SERVICE_UNAVAILABLE)->assertExactJson(['error' => 'Service unavailable']);
    }

    /**
     * @test
     */
    public function walletNotFound()
    {
        $this->walletCache
            ->expects('getBalance')
            ->once()
            ->andThrow(new Exception('a wallet with the specified ID was not found.',404));

        $response = $this->get('api/wallet/1/balance');

        $response->assertStatus(Response::HTTP_NOT_FOUND)->assertExactJson(['error' => 'a wallet with the specified ID was not found.']);
    }

    /**
     * @test
     */
    public function getBalanceWallet()
    {
        $this->walletCache
            ->expects('getBalance')
            ->with('1')
            ->once()
            ->andReturn(0);

        $response = $this->get('api/wallet/1/balance');

        $response->assertStatus(Response::HTTP_OK)->assertExactJson(["balance_usd" => 0]);
    }
}
