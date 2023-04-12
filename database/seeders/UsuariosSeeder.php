<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsuariosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('usuarios')->insert([
            'id'=>'1',
            'nombre' => ' Francisco Javier Matuz',
            'correo' => 'franciscomatuz@aimsa.com',
            'password' => bcrypt('contraseña'),
            'role_id'=>'1'
        ]);

        DB::table('usuarios')->insert([
            'id'=>'2',
            'nombre' => 'Apizaco Tlaxcala',
            'correo' => 'clientes@apizaco.com',
            'password' => bcrypt('apizaco'),
            'role_id'=>'2'
        ]);
    }
}
