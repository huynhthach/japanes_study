<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class question extends Model
{
    use HasFactory;
    protected $table = 'questions';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = ['exam_id', 'question_text', 'created_at' ];

    public function answer(){
        return $this->hasMany(answers::class,'question_id','id');
    }
    
    public function wrong_answer(){
        return $this->hasMany(wrong_answer::class,'question_id','id');
    }

    public function exam()
    {
        return $this->belongsTo(exams::class,'exam_id','id');
    }
}
