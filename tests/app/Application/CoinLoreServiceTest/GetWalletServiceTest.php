<?php

namespace Tests\Application\CoinLoreServiceTest;
use App\Application\API\GetWalletService;
use App\Application\CacheSource\CacheSource;
use App\Domain\Coin;
use App\Domain\Wallet;
use Tests\TestCase;
use Mockery;
use Exception;

class GetWalletServiceTest extends TestCase
{
    private GetWalletService $getWalletService;
    private CacheSource $walletCache;

    /**
     * @setUp
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->walletCache = Mockery::mock(CacheSource::class);

        $this->getWalletService = new GetWalletService($this->walletCache);
    }

    /**
     * @test
     */
    public function walletNotFound()
    {
        $this->walletCache
            ->expects('get')
            ->with('1')
            ->once()
            ->andThrow(new Exception('a wallet with the specified ID was not found.'));

        $this->expectExceptionMessage('a wallet with the specified ID was not found.');

        $this->getWalletService->execute('1');
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
            ->andThrow(new Exception('Service unavailable'));

        $this->expectExceptionMessage('Service unavailable');

        $this->getWalletService->execute('1');
    }

    /**
     * @test
     */
    public function getWalletSuccessful()
    {
        $coin1= new Coin('1','ethereum','$','1','1564.23',1);
        $coin2= new Coin('2','Dogecoin','%','2','162.65',7);
        $wallet = new Wallet('1');
        $wallet->setCoins($coin1,7);
        $wallet->setCoins($coin2,3);
        $this->walletCache
            ->expects('get')
            ->with('1')
            ->once()
            ->andReturn($wallet);

        $response = $this->getWalletService->execute('1');

        $this->assertEquals($wallet,$response);
    }
}
