<?php

namespace Tests\app\Infrastructure\Validation;

use Tests\TestCase;
use App\Infrastructure\Validation\ParametersValidation;
use Illuminate\Http\Request;
use Exception;

class ParametersValidationTest extends TestCase
{
    private ParametersValidation $parametersValidation;
    private Request $request;

    /**
     * @setUp
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->parametersValidation = new ParametersValidation();
        $this->request = new Request();
        $this->request->setMethod('POST');
    }

    /**
     * @test
     */
    public function validationError()
    {
        $this->request->request->add(['coin_id' => '12345','wallet_id'=>'1']);

        $this->expectException(Exception::class);

        $this->parametersValidation->execute($this->request);
    }

    /**
     * @test
     */
    public function validationOK()
    {
        $this->request->request->add(['coin_id' => '12345','wallet_id'=>'1', 'amount_usd'=> 0]);

        $response = $this->parametersValidation->execute($this->request);

        $this->assertTrue($response);
    }

}
