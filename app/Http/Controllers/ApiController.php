<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{
        
    public function getCategoriasNivelMaximo()
    {
        $query= DB::select("SELECT max(nivel) as maximo from categorias c");    
        $maxLevel= $query[0]->maximo;

        $query2= DB::select("SELECT id,nombre,padre_id from categorias c where nivel =:maxi",['maxi'=>$maxLevel]);
        return response()->json($query2);

    }

    public function obtenerReporte()
    {
        $query= DB::select("SELECT z.padre_id categoria, sum(w.cantidad) cantidad
        from(
        
        SELECT categoria, sum(cantidad) cantidad
        from(
            SELECT p.nombre, p.cantidad, c.nombre as categoria
            from productos p, categorias c
            where p.categoria_id=c.id
        )a
        group by categoria 
        
        )w, (
        
        SELECT c1.id, c1.nombre, c2.nombre as padre_id 
                    from categorias c1
                    left join categorias c2 
                    on c1.padre_id =c2.id
        )z
        where w.categoria = z.nombre
        union
        SELECT categoria, sum(cantidad) cantidad
        from(
            SELECT p.nombre, p.cantidad, c.nombre as categoria
            from productos p, categorias c
            where p.categoria_id=c.id
        )a
        group by categoria
        union
        SELECT nombre as categoria, cantidad 
        from productos p2");
        return response()->json($query);

    }
}
