<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    // 添加一条记录
	static function insertOne($user_id, $subject, $content){
		$note = new Note;
        $note->user_id = $user_id;
        $note->subject = $subject;
        $note->content = $content;
        $note->save();
	}
}
