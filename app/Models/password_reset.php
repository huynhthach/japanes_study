<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class password_reset extends Model
{
    use HasFactory;

    protected $table = 'password_reset';
    protected $primaryKey = 'email';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['email','token','created_at'];
}
