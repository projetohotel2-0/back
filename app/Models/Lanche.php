<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Lanche as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lanche extends Model
{
    use HasFactory, Notifiable, SoftDeletes;
    protected $fillable = [
        'name',
        'description',
        'type',
        'promotion',
        'discount',
        'images',
    ];
    // protected $casts = [
    //     'images' => 'json', // Ou 'json'
    //     'type' => 'json',
    // ];
}

