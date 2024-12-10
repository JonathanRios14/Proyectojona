<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    public function estudiante()
    {
        return $this->belongsTo(User::class, 'estudiante_id');
    }


    public function profesor()
    {
        return $this->belongsTo(User::class, 'profesor_id');
    }


    protected $table = 'projects';

    protected $fillable = ['nombre', 'descripcion', 'estudiante_id', 'profesor_id', 'estado'];




    public function files()
    {
        return $this->hasMany(File::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
