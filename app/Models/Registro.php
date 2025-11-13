<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registro extends Model
{
    use HasFactory;
    /**
     * Campos asignables (fillable)
     * ----------------------------
    * Explicación:
    *  - `$fillable` evita la asignación masiva insegura. Sólo los campos
     *    incluidos aquí se pueden rellenar con `Modelo::create($array)`.
     *  - Si no pones estos campos, `Registro::create()` fallará o no guardará
     *    los valores. Es una medida de seguridad para evitar que el usuario
     *    modifique campos no deseados.
     */
    protected $fillable = [
        'nombre',
        'fecha',
        'pasos',
        'calorias',
        'estado',
    ];

    /**
     * Notas adicionales:
     *  - Si en el futuro añades campos como `user_id` para relacionar con
     *    usuarios autenticados, añádelos también en $fillable o usa $guarded
     *    según tu preferencia.
     */
}