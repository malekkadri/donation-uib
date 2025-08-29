<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    // Make sure this matches your actual table name
    protected $table = 'contact'; // or 'contacts' if that's what you have

    protected $fillable = [
        'name',
        'email',
        'mobile',
        'message'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
