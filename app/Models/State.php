<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class State.
 *
 * @package Acacha\Relationships\Models
 */
class State extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['country_code','name'];

}
