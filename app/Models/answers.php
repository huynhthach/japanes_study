<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class answers extends Model
{
    use HasFactory;
    protected $table = 'answers';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = ['question_id', 'answer_text', 'is_correct', 'created_at'];

    public function question(){
        return $this->belongsTo(question::class,'question_id','id');
    }
}
