<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class exams extends Model
{
    use HasFactory;
    protected $table = 'exams';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = ['title','level', 'year', 'created_at' ];

    public function question(){ 
        return $this->hasMany(question::class,'exam_id','id');
    }

    public function mycourse(){
        return $this->hasMany(question::class,'exam_id','id');
    }
}
