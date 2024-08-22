<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class mycourse extends Model
{
    use HasFactory;
    protected $table = 'mycourse';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'exam_id',
        'progress',
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function exam()
    {
        return $this->belongsTo(exams::class,'exam_id','id');
    }

    public static function updateProgress($userId, $examId, $progress)
    {
        $course = self::firstOrCreate(
            ['user_id' => $userId, 'exam_id' => $examId],
            ['progress' => 0]
        );
        $course->progress = $progress;
        $course->save();
    }
}
