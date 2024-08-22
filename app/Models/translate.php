<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class translate extends Model
{
    use HasFactory;
    protected $table = 'translations';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = ['model_type', 'model_id','locale','field','value'];
}
