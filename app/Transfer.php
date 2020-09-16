<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    public function Wallet(){
        return $this->belongsTo('App\Wallet');
    }
}
