<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Transfer;
use App\Wallet;

class TransferController extends Controller
{
    public function store(Request $request){
        //Actualizamos el dinero en la cartera
        $wallet = Wallet::find($request->wallet_id);
        $wallet->money = $wallet->money + $request->amount;
        $wallet->update();

        //Registramos la transferencia
        $transfer = new Transfer();
        $transfer->description = $request->description;
        $transfer->amount = $request->amount;
        $transfer->wallet_id = $request->wallet_id;
        $transfer->save();
        //retornamos en format json y una respuesta 201
        return response()->json($transfer, 201);
    }    
}
