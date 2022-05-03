<?php

namespace Tests\app\Infrastructure\Controller;

use App\Application\CoinLoreCryptoDataSource\CoinLoreCryptoDataSource;
use Illuminate\Http\Response;
use Tests\TestCase;
use Exception;
use Mockery;

class BuyCoinControllerTest extends TestCase
{
    /**
     * @test
     */
    public function genericError()
    {
        $coinLoreCryptoDataSource = Mockery::mock(CoinLoreCryptoDataSource::class);

        $this->app->bind(CoinLoreCryptoDataSource::class, fn () => $coinLoreCryptoDataSource);

        $data = ['coin_id' => '90','wallet_id'=>'1', 'amount_usd'=> 0];

        $coinLoreCryptoDataSource
            ->expects('getCoin')
            ->with(90)
            ->once()
            ->andThrow(new Exception('Service unavailable',503));

        $response = $this->post('api/coin/buy', $data);

        $response->assertStatus(Response::HTTP_SERVICE_UNAVAILABLE)->assertExactJson(['error' => 'Service unavailable']);
    }

    /**
     * @test
     */
    public function buycoinSuccessful()
    {
        $data = ['coin_id' => '90','wallet_id'=>'1', 'amount_usd'=> 0];

        $response = $this->postJson('/api/coin/buy',$data);

        $response->assertStatus(Response::HTTP_OK)->assertExactJson(["Successful Operation"]);
    }

    /**
     * @test
     */
    public function coinNotFound()
    {
        $data = ['coin_id' => '12345','wallet_id'=>'1', 'amount_usd'=>0];

        $response = $this->postJson('/api/coin/buy',$data);

        $response->assertStatus(Response::HTTP_NOT_FOUND)->assertExactJson(['error' => 'A coin with specified ID was not found.']);
    }
}
