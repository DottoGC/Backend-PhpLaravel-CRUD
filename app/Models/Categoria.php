<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    //use HasFactory;

    protected $table='categorias';

    protected $fillable=[
        'nombre',
        'padre_id',
        'nivel'
    ];

    protected $hidden=[
        'created_at',
        'updated_at'
    ];
    
    public function padre(){
        return $this->belongsTo(Categoria::class, 'padre_id', 'id');
    }


}
