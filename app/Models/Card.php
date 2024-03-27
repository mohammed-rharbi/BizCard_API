<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{

    protected $fillable = ['name', 'company', 'title', 'phone','users_id'];
    use HasFactory;

    public function user(){

       return $this->belongsTo(User::class);
    }
}
