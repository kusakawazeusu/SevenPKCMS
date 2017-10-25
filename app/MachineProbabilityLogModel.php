<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MachineProbabilityLogModel extends Model
{
    //
    protected $table = 'machineprobabilitylog';

    const UPDATED_AT = null;

    protected $fillable = [
            'MachineID',
            'TwoPairs',
            'ThreeOfAKind',
            'Straight',
            'Flush',
            'FullHouse',
            'FourOfAKind',
            'STRFlush',
            'FiveOfAKind',
            'RoyalFlush',
            'RealFourOfAKind',
            'RealSTRFlush',
            'RealFiveOfAKind',
            'RealRoyalFlush',
            'Turtle',
            'DoubleStar',
            'BonusDifficulty',
            'WildCard'//,
            //'Water' => Input::get('Water')
    ];
}
