<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MailToken extends Model
{
	use SoftDeletes;

	// user
	public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

}