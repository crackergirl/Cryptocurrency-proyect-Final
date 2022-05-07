<?php

namespace Tests\Application\CoinLoreServiceTest;
use App\Infrastructure\Cache\WalletCache;
use Tests\TestCase;
use Mockery;
use App\Application\API\OpenWalletService;

class OpenWalletTest extends TestCase
{
    private OpenWalletService $openWalletService;
    private WalletCache $walletCache;

    /**
     * @setUp
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->walletCache = Mockery::mock(WalletCache::class);

        $this->openWalletService = new OpenWalletService($this->walletCache);
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

        $response = $this->openWalletService->execute();

        $this->assertEquals("1",$response);
    }

}
