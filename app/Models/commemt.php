<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class commemt extends Model
{
    use HasFactory;
    protected $table = 'comment';
    protected $primaryKey = 'Comment_ID';
    public $timestamps = false; 

    protected $fillable = [
        'Comment_ID	', 'UserID','PostID', 'Content', 'Created_at'
    ];

    public function users()
    {
        return $this->belongsTo(User::class, 'UserID', 'id');
    }

    public function news()
    {
        return $this->belongsTo(post::class, 'PostID', 'id');
    }
}
