<?php

namespace Tests\App\Application\CoinLoreServiceTest;

use App\Application\API\GetBalanceWalletService;
use App\Domain\Wallet;
use Tests\TestCase;
use Exception;
use App\Application\CacheSource\CacheSource;
use Mockery;

class GetBalanceWalletServiceTest extends TestCase
{
    private GetBalanceWalletService $getBalanceWalletService;
    private CacheSource $walletCache;

    /**
     * @setUp
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->walletCache = Mockery::mock(CacheSource::class);
        $this->getBalanceWalletService = new GetBalanceWalletService($this->walletCache);
    }

    /**
     * @test
     * @throws Exception
     */
    public function walletNotFound()
    {
        $this->walletCache
            ->expects('get')
            ->with('1')
            ->once()
            ->andThrow(new Exception('a wallet with the specified ID was not found.'));

        $this->expectExceptionMessage('a wallet with the specified ID was not found.');

        $this->getBalanceWalletService->execute('1');
    }

    /**
     * @test
     * @throws Exception
     */
    public function genericError()
    {
        $this->walletCache
            ->expects('get')
            ->with('1')
            ->once()
            ->andThrow(new Exception('Service unavailable'));

        $this->expectExceptionMessage('Service unavailable');

        $this->getBalanceWalletService->execute('1');
    }

    /**
     * @test
     * @throws Exception
     */
    public function getBalanceWallet()
    {
        $wallet = new Wallet("1");
        $wallet->setProfit(2);
        $wallet->setExpenses(1);

        $this->walletCache
            ->expects('get')
            ->with('1')
            ->once()
            ->andReturn($wallet);

        $response = $this->getBalanceWalletService->execute('1');

        $this->assertEquals(1,$response);
    }
}
