<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    protected $fillable = ['name', 'quantity', 'price'];
    public static function form() {
    	return [
    		'name' => '',
    		'quantity' => '',
    		'price' => ''
    	];
    }
}
