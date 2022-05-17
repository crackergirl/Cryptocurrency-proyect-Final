<?php

namespace Tests\App\Application\CoinLoreServiceTest;

use App\Application\CacheSource\CacheSource;
use Tests\TestCase;
use Mockery;
use App\Application\API\OpenWalletService;
use Exception;

class OpenWalletServiceTest extends TestCase
{
    private OpenWalletService $openWalletService;
    private CacheSource $walletCache;

    /**
     * @setUp
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->walletCache = Mockery::mock(CacheSource::class);
        $this->openWalletService = new OpenWalletService($this->walletCache);
    }

    /**
     * @test
     */
    public function openWalletInsertInCacheSuccessful()
    {
        $this->walletCache
            ->expects('create')
            ->with('1',Mockery::on(function($wallet){
                return $wallet->getWalletId() === '1';
            }))
            ->once()
            ->andReturn(true);

        $response=$this->openWalletService->insertWalletInCache('1');

        $this->assertTrue($response);
    }

    /**
     * @test
     */
    public function openWalletGetWalletIdWithPreviousWalletAliveSuccessful()
    {
        $this->walletCache
            ->expects('exists')
            ->with('1')
            ->once()
            ->andReturn(true);
        $this->walletCache
            ->expects('exists')
            ->with('2')
            ->once()
            ->andReturn(false);

        $response=$this->openWalletService->createWalletId();

        $this->assertEquals($response,'2');
    }

    /**
     * @test
     */
    public function openWalletGetWalletIdWithOutPreviousWalletAliveSuccessful()
    {
        $this->walletCache
            ->expects('exists')
            ->with('1')
            ->once()
            ->andReturn(false);

        $response=$this->openWalletService->createWalletId();

        $this->assertEquals($response,'1');
    }

    /**
     * @test
     * @throws Exception
     */
    public function genericError()
    {
        $this->walletCache
            ->expects('exists')
            ->with(1)
            ->once()
            ->andThrow(new Exception('Service unavailable'));

        $this->expectExceptionMessage('Service unavailable');

        $this->openWalletService->execute();
    }
}

