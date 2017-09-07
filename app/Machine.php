<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Machine extends Model
{
    protected $table = 'machine';

    public $primaryKey = 'ID';

    public $timestamps = false;

    protected $fillable = [
        'AgentID',
        'MachineName',
        'IPAddress',
        'SectionID',
        'MaxDepositCredit',
        'DepositCreditOnce',
        'MinCoinOut',
        'MaxCoinIn',
        'CoinInOnce',
        'CoinInBonus',
        'TwoPairsOdd',
        'ThreeOfAKindOdd',
        'StraightOdd',
        'FlushOdd',
        'FullHouseOdd',
        'FourOfAKindOdd',
        'STRFlushOdd',
        'FiveOfAKindOdd',
        'RoyalFlushOdd'
    ];
}
