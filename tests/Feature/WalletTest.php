<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Wallet;
use App\Transfer;

class WalletTest extends TestCase
{
    use RefreshDatabase;
    
    public function testGetWallet()
    {
        //para ejecutar y crear wallets con el factory
        $wallet = factory(Wallet::class)->create();
        //para ejecutar y crear varios Transfers
        $transfers = factory(Transfer::class, 3)->create([
            'wallet_id' => $wallet->id //para que me inserte el wallet id antes generado
        ]);



        $response = $this->json('GET', '/api/wallet');
        //Validar que es el status para validar sea de 200
        $response->assertStatus(200)
                //valida que toda la infor venga de esta manera
                ->assertJsonStructure([
                    'id', 'money', 'transfers' => [
                        '*' => [
                            'id', 'amount', 'description', 'wallet_id'
                        ]
                    ]
                ]);
        // Probar cuantos transfer se estan retornando
        $this->assertCount(3, $response->json()['transfers']);
    }
}



