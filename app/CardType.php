<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CardType extends Model
{
    //

    public $timestamps = false;
    protected $table = 'cardtype';
    public $primaryKey = 'ID';    

    protected $fillable = ['CardType'];
}
