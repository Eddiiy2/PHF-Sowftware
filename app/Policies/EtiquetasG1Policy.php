<?php

namespace App\Policies;

use App\Models\EtiquetasG1;
use App\Models\Usuario;
use Illuminate\Auth\Access\Response;

class EtiquetasG1Policy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Usuario $usuario): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Usuario $usuario, EtiquetasG1 $etiquetasG1): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Usuario $usuario): bool
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Usuario $usuario, EtiquetasG1 $etiquetasG1): bool
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Usuario $usuario, EtiquetasG1 $etiquetasG1): bool
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(Usuario $usuario, EtiquetasG1 $etiquetasG1): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(Usuario $usuario, EtiquetasG1 $etiquetasG1): bool
    {
        //
    }
}
