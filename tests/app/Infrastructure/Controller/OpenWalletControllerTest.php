<?php

namespace Tests\app\Infrastructure\Controller;
use App\Application\CacheSource\CacheSource;
use Illuminate\Http\Response;
use Tests\TestCase;
use Exception;
use Mockery;

class OpenWalletControllerTest extends TestCase
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
    public function openWalletSuccessful()
    {
        $this->walletCache
            ->expects('exists')
            ->with(1)
            ->once()
            ->andReturn(false);
        $this->walletCache
            ->expects('create')
            ->with('1',Mockery::on(function($wallet){
                return $wallet->getWalletId() === '1';
            }))
            ->once()
            ->andReturn(true);

        $response = $this->post('/api/wallet/open');

        $response->assertStatus(Response::HTTP_OK)->assertExactJson(["wallet_id" => "1"]);
    }

    /**
     * @test
     */
    public function genericError()
    {
        $this->walletCache
            ->expects('exists')
            ->with(1)
            ->once()
            ->andThrow(new Exception('Service unavailable',503));

        $response = $this->post('/api/wallet/open');

        $response->assertStatus(Response::HTTP_SERVICE_UNAVAILABLE)->assertExactJson(['error' => 'Service unavailable']);
    }
}
