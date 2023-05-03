<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cip02Secuencia extends Model
{
    public $timestamps = false;
    use HasFactory;
    protected $primaryKey = 'valor';

    protected $table = 'cip02_secuencia_area1';
}
