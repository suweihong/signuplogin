<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Coin;

class Wallet extends Model
{

    // coin
	public function coin()
    {
        return $this->belongsTo('App\Models\Coin');
    }

	// fixNullData
	static public function fixNullData($userId)
	{
		// each
		foreach (Coin::all() as $coin) {
			$findCoin = self::where('user_id', $userId)
							->where('coin_id', $coin->id)
							->count();

			// insert
			if($findCoin < 1){
				self::insert([
					'user_id'=> $userId,
					'coin_id'=> $coin->id,
					'balance'=> 0,
					'created_at'=> date('Y-m-d H:i:s'),
					'updated_at'=> date('Y-m-d H:i:s'),
				]);
			}
		}
	}




}