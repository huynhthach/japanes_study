<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class vocab extends Model
{
    use HasFactory;
    protected $table = 'vocabularies';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = ['word', 'kanji','meaning','example','post_id', 'img_id' ,'created_at'];

    public function post()
    {
        return $this->belongsTo(post::class, 'post_id', 'id');
    }

    public function image()
    {
        return $this->belongsTo(image::class, 'img_id', 'id');
    }
}
