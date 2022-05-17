<?php

namespace Tests\App\Infrastructure\Controller;

use App\Domain\Wallet;
use App\Application\CacheSource\CacheSource;
use Illuminate\Http\Response;
use Tests\TestCase;
use Exception;
use Mockery;

class GetBalanceWalletControllerTest extends TestCase
{
    private CacheSource $walletCache;

    /**
     * @setUp
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->walletCache = Mockery::mock(CacheSource::class);
        $this->app->bind(CacheSource::class, fn () => $this->walletCache);
    }

    /**
     * @test
     */
    public function genericError()
    {
        $this->walletCache
            ->expects('get')
            ->with('1')
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
            ->expects('get')
            ->once()
            ->with('1')
            ->andThrow(new Exception('a wallet with the specified ID was not found.',404));

        $response = $this->get('api/wallet/1/balance');

        $response->assertStatus(Response::HTTP_NOT_FOUND)->assertExactJson(['error' => 'a wallet with the specified ID was not found.']);
    }

    /**
     * @test
     */
    public function getBalanceWallet()
    {
        $wallet = new Wallet("1");
        $wallet->setProfit(1);
        $wallet->setExpenses(1);

        $this->walletCache
            ->expects('get')
            ->with('1')
            ->once()
            ->andReturn($wallet);

        $response = $this->get('api/wallet/1/balance');

        $response->assertStatus(Response::HTTP_OK)->assertExactJson(["balance_usd" => 0]);
    }
}
