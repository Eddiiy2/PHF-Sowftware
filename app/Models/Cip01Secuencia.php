<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cip01Secuencia extends Model
{
    public $timestamps = false;
    use HasFactory;


    //NOS PERMITE PARA PODER MANDAR LOS DATOS DEL ID AL EDITAR, SIN ESTO NO PODEMOS MANDARLOS
    //3 HORAS CORRIGIENDO EL ERRRO Y SOLO NECESITABA ESTO PTM
    protected $primaryKey = 'valor';

    //DEBEMOS DE TENER DEFINIDO EL NOMBRE DE LA TABLA DE LA BASE DE DATOS EN EL CUAL TENGAMOS LA RELACION PARA QUE ASI NO NOS MARQUE NINGUN TIPO DE NRROR AL MOMENTO DE QUERER UTILIZAR LA BASE D E DATOPS

    protected $table = 'cip01_secuencia_area1';

}
