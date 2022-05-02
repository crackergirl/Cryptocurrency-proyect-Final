<?php


namespace Tests\app\Infrastructure\Controller;
use App\Application\CoinLoreCryptoDataSource\CoinLoreCryptoDataSource;
use Illuminate\Http\Response;
use Tests\TestCase;
use Exception;
use Mockery;

class BuyCoinControllerTest extends TestCase
{
    private CoinLoreCryptoDataSource $coinLoreCryptoDataSource;

    /**
     * @setUp
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->coinLoreCryptoDataSource = Mockery::mock(CoinLoreCryptoDataSource::class);

        $this->app->bind(CoinLoreCryptoDataSource::class, fn () => $this->coinLoreCryptoDataSource);
    }

    /**
     * @test
     */
    public function genericError()
    {
        $data = ['coin_id' => '90','wallet_id'=>'1', 'amount_usd'=>0];

        $this->coinLoreCryptoDataSource
            ->expects('getCoin')
            ->with('90')
            ->once()
            ->andThrow(new Exception('Service unavailable',503));

        $response = $this->post('api/coin/buy', $data);

        $response->assertStatus(Response::HTTP_SERVICE_UNAVAILABLE)->assertExactJson(['error' => 'Service unavailable']);
    }

    /**
     * @test
     */
    public function buyCoinSuccessful()
    {
        $data = ['coin_id' => '90','wallet_id'=>'1', 'amount_usd'=>10];

        $response = $this->postJson('api/coin/buy', $data);

        var_dump($response->getContent());
        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * @test
     */
    public function coinNotFound()
    {
        $data = ['coin_id' => '90','wallet_id'=>'1', 'amount_usd'=>0];

        $this->coinLoreCryptoDataSource
            ->expects('buyCoin')
            ->with('90','1',0)
            ->once()
            ->andThrow(new Exception(
                'A coin with the specified ID was not found',404));

        $response = $this->post('api/coin/buy', $data);

        $response->assertStatus(Response::HTTP_NOT_FOUND)->assertExactJson(['error' => 'A coin with the specified ID was not found']);
    }



}
