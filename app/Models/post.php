<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class post extends Model
{
    use HasFactory;
    protected $table = 'posts';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = ['title', 'description', 'published_at','topic_id'];

    public function topic()
    {
        return $this->belongsTo(topics::class, 'topic_id','id');
    }

    public function content()  
    {
        return $this->hasMany(table_content::class, 'post_id', 'id');
    }

    public function vocab()
    {
        return $this->hasMany(vocab::class, 'post_id','id');
    }

    public function comment()
    {
        return $this->hasMany(commemt::class, 'PostID','id');
    }

}
