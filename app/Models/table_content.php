<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

    class table_content extends Model
    {
        use HasFactory;
        protected $table = 'table_of_contents';
        protected $primaryKey = 'id';
        public $timestamps = false;

        protected $fillable = [
            'post_id',
            'description',
            'img_id',
        ];

        public function post()
        {
            return $this->belongsTo(post::class,'post_id','id');
        }

        public function image()
        {
            return $this->belongsTo(image::class, 'img_id', 'id');
        }

    }
