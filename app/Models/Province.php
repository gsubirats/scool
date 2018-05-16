<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Province.
 *
 * @package Acacha\Relationships\Models
 */
class Province extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name','state_id'];
}
