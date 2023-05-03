<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EtiquetasG1 extends Model
{
    public $timestamps = false;
    use HasFactory;
    protected $primaryKey = 'id';

    protected $table = 'etiquetas_grafica1';
}
