<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlayerAccModel extends Model
{
    //
	const CREATED_AT =null;
	const UPDATED_AT = 'Updated_at';
    protected $table = 'playeracc';
    protected $fillable = ['PlayerID'];
}
