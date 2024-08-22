<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class image extends Model
{
    use HasFactory;
    protected $table = 'image';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = ['img_id', 'url','created_at'];

    public function content(){
        return $this->hasMany(table_content::class,'img_id','id');
    }

    public function vocab(){
        return $this->hasMany(vocab::class,'img_id','id');
    }
}
