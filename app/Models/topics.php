<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class topics extends Model
{
    use HasFactory;
    protected $table = 'topics';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = ['name', 'category','level','created_at'];

    public function post(){
        return $this->hasMany(post::class,'topic_id','id');
    }
    
    
}
