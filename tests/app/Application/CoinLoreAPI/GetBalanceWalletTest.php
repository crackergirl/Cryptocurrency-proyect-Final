<?php

namespace Tests\Application\CoinLoreServiceTest;
use App\Application\API\GetBalanceWalletService;
use Tests\TestCase;
use Exception;
use App\Infrastructure\Cache\WalletCache;
use Mockery;

class GetBalanceWalletTest extends TestCase
{
    private GetBalanceWalletService $getBalanceWalletService;
    private WalletCache $walletCache;

    /**
     * @setUp
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->walletCache = Mockery::mock(WalletCache::class);

        $this->getBalanceWalletService = new GetBalanceWalletService($this->walletCache);
    }

    /**
     * @test
     */
    public function walletNotFound()
    {
        $this->walletCache
            ->expects('getBalance')
            ->with('1')
            ->once()
            ->andThrow(new Exception('a wallet with the specified ID was not found.'));

        $this->expectException(Exception::class);

        $this->getBalanceWalletService->execute('1');
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
            ->andReturn(1);

        $response = $this->getBalanceWalletService->execute('1');

        $this->assertEquals(1,$response);
    }
}
