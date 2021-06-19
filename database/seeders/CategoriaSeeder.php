<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('categorias')->insert([
            [
                'padre_id'=>null,
                'nombre'=> 'Aceites',
                'cantidad'=> 15
            ],
            [                
                'padre_id'=>1,
                'nombre'=>'Motor',
                'cantidad'=> 15
            ],            
            [
                'padre_id'=>2,
                'nombre'=>'Minerales',
                'cantidad'=> 15
                
            ],
            [
                'padre_id'=>3,
                'nombre'=>'Castrol 20w50',
                'cantidad'=> 15
                
            ]

            ]);
    }
}
