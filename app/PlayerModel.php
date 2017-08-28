<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlayerModel extends Model
{
    //
    public $timestamps = false;
    protected $table = 'player';

    protected $fillable = ['Name', 'CardNumber', 'Balance', 'CardType', 'IDCardNumber', 'Cellphone', 'IntroducerID', 'DocumentFront', 'DocumentBack', 'Photo', 'Gender', 'NickName', 'Career', 'Coming', 'ReceiveAd','Telephone','Enable','Birthday'];
}
