<?php

namespace App\Models;

use App\Scopes\UsuarioScope;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tarea extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['user_id', 'tarea', 'descripcion', 'categoria'];
    
    // protected static function booted()
    // {
    //     static::addGlobalScope(new UsuarioScope);
    // }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function etiquetas()
    {
        return $this->belongsToMany(Etiqueta::class);
    }

    protected function tarea(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => strtoupper($value)
        );
    }

    public function scopeTrabajo($query)
    {
        return $query->where('categoria', 'Trabajo');
    }

    // public function setTareaAttribute($value)
    // {
    //     return $this->tarea = ucfirst(strtolower($value));
    // }
}
