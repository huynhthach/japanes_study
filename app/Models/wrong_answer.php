<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class wrong_answer extends Model
{
    use HasFactory;
    protected $table = 'wrong_answer';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = ['question_id', 'wrong_answer_text', 'is_correct', 'created_at'];

    public function question(){
        return $this->hasMany(question::class,'question_id','id');
    }
}
