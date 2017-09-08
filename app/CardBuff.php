<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CardBuff extends Model
{
    // 
    public $timestamps = false;
    protected $table = 'cardbuff';
    public $primaryKey = 'ID';    

    protected $fillable = ['CardTypeID','StartTime', 'EndTime'];
}
