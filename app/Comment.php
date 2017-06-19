<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
	protected $fillable = ['body']; 
    public static function form() {
    	return [
    		'body' => ''
    	];
    }
    public function user() {
    	return $this->belongsTo(User::class)->select(['id', 'name', 'email']);
    }
}
