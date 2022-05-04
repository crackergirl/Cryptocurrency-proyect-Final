<?php


namespace Tests\Application\CoinLoreServiceTest;
use App\Application\CoinLoreCryptoDataSource\CoinLoreCryptoDataSource;
use Tests\TestCase;
use Exception;
use Mockery;
use App\Application\API\OpenWalletService;

class OpenWalletTest extends TestCase
{
    private OpenWalletService $openWalletService;
    private CoinLoreCryptoDataSource $coinLoreCryptoDataSource;

    /**
     * @setUp
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->coinLoreCryptoDataSource = Mockery::mock(CoinLoreCryptoDataSource::class);

        $this->openWalletService = new OpenWalletService($this->coinLoreCryptoDataSource);
    }

    /**
     * @test
     */
    public function genericError()
    {
        $this->coinLoreCryptoDataSource
            ->expects('openWallet')
            ->once()
            ->andThrow(new Exception('A coin with specified ID was not found.',404));

        $this->expectException(Exception::class);

        $this->openWalletService->execute();
    }

    /**
     * @test
     */
    public function openWalletSuccessful()
    {
        $this->coinLoreCryptoDataSource
            ->expects('openWallet')
            ->once()
            ->andReturn("1");

        $response = $this->openWalletService->execute();

        $this->assertEquals("1",$response);
    }

}
