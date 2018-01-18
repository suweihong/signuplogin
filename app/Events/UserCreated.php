<?php

namespace App\Events;

use Illuminate\Support\Facades\Mail;

use App\Models\User;
use App\Models\MailToken;

class UserCreated extends Event
{
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
    	// new
    	$mailToken = new MailToken;
    	$mailToken->user_id = $user->id;
    	$mailToken->token = str_random(32);

    	// success
    	if($mailToken->save()){

    		// sendmail
    		Mail::send('emails.signup', [
	    		'email'=> $user->email,
	    		'token'=> $mailToken->token,
	    	], function ($message) use ($user) {
			    $message->subject(trans('sendmail.signup_subject'));
			    $message->to($user->email);
			});

    	}
    }

}
