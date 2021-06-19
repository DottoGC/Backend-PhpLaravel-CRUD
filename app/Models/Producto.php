<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    //use HasFactory;
    protected $table='productos';

    protected $fillable=[
        'nombre',
        'cantidad',
        'categoria_id',
        //'cantidad'
    ];

    protected $hidden=[
        'created_at',
        'updated_at'
    ];

    public function categoria(){
        return $this->belongsTo(Categoria::class, 'categoria_id', 'id');
    }
}
