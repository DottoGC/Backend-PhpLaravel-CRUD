<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categoria;
use App\Http\Requests\CreateCategoriaRequest;
use App\Http\Requests\UpdateCategoriaRequest;
use Illuminate\Support\Facades\DB;

class CategoriaController extends Controller
{

    //Muestra categoria/categorias
    public function index(Request $request){

        if($request->has('buscar')){
            $categorias=Categoria::where('name','like','%'.$request->buscar.'%')->get();

        }else{
            $categorias=DB::select("SELECT c1.id, c1.nombre, c2.nombre as padre_id, c1.nivel
            from categorias c1
            left join categorias c2 
            on c1.padre_id =c2.id");
        }

        return response()->json($categorias);
    }


    //Muestra categoria
    public function show($id){
        //$categorias=Categoria::findOrFail($id);
        $query=DB::select("SELECT c1.id, c1.nombre, c1.nivel,c2.nombre as padre_id
        from categorias c1,categorias c2
        where c1.padre_id =c2.id and c1.id=:idCategoria",['idCategoria'=>$id]);
        
        if(isset($query[0]->padre_id)){//Si no es nulo
            return response()->json($query[0]);//Retornamos la consulta con nombre del padre
        }else{
            //De lo contrario retornamos, sin el nombre del padre xq el padre_id es null
            $query=DB::select("SELECT c1.id, c1.nombre, c1.nivel,c1.padre_id
            from categorias c1
            where c1.id=:idCategoria",['idCategoria'=>$id]);
            return response()->json($query[0]);
        }

    }


    //Almacena categoria
    public function store(CreateCategoriaRequest $request){
        $input=$request->all();

        $query= DB::select("SELECT id from categorias c where nombre=:nombrepadre",['nombrepadre'=>$request->padre_id]);
        if(isset($query[0]->id)){//Si no es nulo
            $input['padre_id'] = $query[0]->id;//Asignamos el id del padre
        }

        $categoria=Categoria::create($input);

        if(isset($categoria)){
            $padre = $categoria->padre;
            while(isset($padre)){
                $categoria->nivel=$categoria->nivel+1;
                $categoria->save();
                
                $padre = $padre->padre;
            }

            return response()->json([
                'res'=>true,
                'message'=>'Categoria creada con exito',
                'data'=>$categoria
            ],200);            
        }


        return response()->json([
            'res'=>false,
            'message'=>'No se pudo crear categoria',
            'data'=>$categoria
        ],200);       

    }


    //Actualiza categoria
    public function update(UpdateCategoriaRequest $request,$id){
        $input=$request->all();
        $categoria=Categoria::findOrFail($id);

        $query= DB::select("SELECT id from categorias c where nombre=:nombrepadre",['nombrepadre'=>$request->padre_id]);
        if(isset($query[0]->id)){//Si no es nulo
            $input['padre_id'] = $query[0]->id;//Asignamos el id del padre
        }

        $categoria->update($input);                

        if(isset($categoria)){
            $padre = $categoria->padre;
            $categoria->nivel=0;
            while(isset($padre)){
                $categoria->nivel=$categoria->nivel+1;
                $categoria->save();
                
                $padre = $padre->padre;
            }
            return response()->json([
                'res'=>true,
                'message'=>'Categoria actualizado con exito'
            ],200);            
        }


        return response()->json([
            'res'=>false,
            'message'=>'No se pudo actualizar categoria'
        ],200);       

    }



    //Elimina categoria
    public function destroy($id)
    {
        //$categoria=Categoria::findOrFail($id);
        $categoriaEliminada=Categoria::destroy($id);

        if(isset($categoriaEliminada)){
            return response()->json([
                'res'=>true,
                'message'=>'Categoria eliminado con exito'
            ],200);            
        }

        return response()->json([
            'res'=>false,
            'message'=>'No se pudo eliminar categoria'
        ],200);
    }


}
