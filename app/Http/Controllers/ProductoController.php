<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Http\Requests\CreateProductoRequest;
use App\Http\Requests\UpdateProductoRequest;
use Illuminate\Support\Facades\DB;

class ProductoController extends Controller
{
    
    //Muestra producto/productos
    public function index(Request $request){

        if($request->has('buscar')){
            $productos=Producto::where('name','like','%'.$request->buscar.'%')->get();

        }else{
            //$productos=Producto::all();
            $productos=DB::select("SELECT p.id, p.nombre, p.cantidad, c.nombre as categoria_id
            from productos p, categorias c
            where p.categoria_id =c.id ");
        }

    return response()->json($productos);
    }


    //Muestra categoria
    public function show($id){
        //$producto=Producto::findOrFail($id);
        $query=DB::select("SELECT p.id, p.nombre, p.cantidad, c.nombre as categoria_id
        from productos p,categorias c
        where p.categoria_id =c.id and p.id =:idProducto ",['idProducto'=>$id]);

        return response()->json($query[0]);//Retornamos la consulta
    }


    //Almacena categoria
    public function store(CreateProductoRequest $request){
        $input=$request->all();

        $query= DB::select("SELECT id from categorias c where nombre=:nombrecategoria",['nombrecategoria'=>$request->categoria_id]);
        $input['categoria_id'] = $query[0]->id;      
        $producto=Producto::create($input);

        if(isset($producto)){
            return response()->json([
                'res'=>true,
                'message'=>'Producto creada con exito',
                'data'=>$producto
            ],200);            
        }


        return response()->json([
            'res'=>false,
            'message'=>'No se pudo crear producto',
            'data'=>$producto
        ],200);       

    }


    //Actualiza categoria
    public function update(UpdateProductoRequest $request,$id){
        $input=$request->all();
        $producto=Producto::findOrFail($id);

        $query= DB::select("SELECT id from categorias c where nombre=:nombrepadre",['nombrepadre'=>$request->categoria_id]);
        if(isset($query[0]->id)){//Si no es nulo
            $input['categoria_id'] = $query[0]->id;//Asignamos el id del padre
        }

        $producto->update($input);                

        if(isset($producto)){
            return response()->json([
                'res'=>true,
                'message'=>'Producto actualizado con exito'
            ],200);            
        }


        return response()->json([
            'res'=>false,
            'message'=>'No se pudo actualizar producto'
        ],200);       
    }

        //Elimina producto
    public function destroy($id)
    {
        //$categoria=Producto::findOrFail($id);
        $producto=Producto::destroy($id);

        if(isset($producto)){
            return response()->json([
                'res'=>true,
                'message'=>'Producto eliminado con exito'
            ],200);            
        }

        return response()->json([
            'res'=>false,
            'message'=>'No se pudo eliminar producto'
        ],200);
    }


}
