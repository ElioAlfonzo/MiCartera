<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Wallet;
use App\Transfer;

class TransferTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testPostTransfer()
    {
        $wallet = factory(Wallet::class)->create();
        $transfer = factory(Transfer::class)->make(); //solo crea el transfer en memoria

        $response = $this->json('POST', '/api/transfer', [
            'descripcion' => $transfer->description,
            'amount' => $transfer->amount,
            'wallet_id' => $wallet->id
        ]);


        $response->assertJsonStructure([
            'id','description','amount','wallet_id'
        ])->assertStatus(201); //para ver que se creo que se forma correcta


        //Alamcenar en nuestra BD
        $this->assertDatabaseHas('transfers',[
            'description' => $transfer->description,
            'amount' => $transfer->amount,
            'wallet_id' => $wallet->id
        ]);

        // Operacion Arimetrica
        $this->assertDatabaseHas('wallets', [
            'id' => $wallet->id,
            'money' => $wallet->money + $transfer->amount
        ]);
    }
}
