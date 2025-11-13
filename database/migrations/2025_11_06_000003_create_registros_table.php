<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Crea la tabla `registros` para almacenar los datos también en la base de datos.
     * Campos: id, nombre, fecha, pasos, calorias, estado, timestamps.
     * Comentario para el profesor: añadimos persistencia en BD además del CSV
     * para cumplir el requisito del ejercicio y permitir búsquedas/filtrado.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('registros', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->date('fecha');
            $table->integer('pasos')->unsigned();
            $table->integer('calorias')->unsigned();
            $table->string('estado');
            $table->timestamps();
        });
    }

    /**
     *
     * Elimina la tabla `registros` si se hace rollback.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('registros');
    }
};
