<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'description', 'image', 'quantity', 'price'];

    public function offers() {
        return $this->hasMany(Offer::class);
    } 

    public function user() {
        return $this->belongsTo(User::class);
    }  

    public static function form()
    {
        return [
            'name' => '',
            'description' => '',
            'image' => '',
            'quantity' => '',
            'price' => '',
            'offers' => [
                Offer::form()
            ]
        ];
    }
}
