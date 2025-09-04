<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
  use HasFactory;

  protected $table = 'donation';
  
  protected $fillable = [
      'name',
      'email',
      'mobile',
      'amount',
      'country_id',
      'state_id',
      'city_id',
      'street_address',
      'add_to_leaderboard',
      'status',
      'cause',        
      'session_id' // Added session_id
  ];

  public function country()
  {
      return $this->belongsTo(Country::class);
  }

  public function state()
  {
      return $this->belongsTo(State::class);
  }

  public function city()
  {
      return $this->belongsTo(City::class);
  }
}
