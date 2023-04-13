<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use \Illuminate\Support\Facades\DB;
class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insert([
            'id'=>'1',
            'id_rol'=>'1',
            'nombre_rol' => 'administrador'
        ]);

        DB::table('roles')->insert([
            'id'=>'2',
            'id_rol'=>'2',
            'nombre_rol' => 'cliente'
        ]);
        
    }
}
