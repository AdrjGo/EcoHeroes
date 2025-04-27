<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta la migración.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('historiales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('usuario_id');   // Relación con el usuario
            $table->unsignedBigInteger('solicitud_id'); // Relación con la solicitud
            $table->enum('tipo_evento', [
                'solicitud_creada',
                'solicitud_asignada',
                'solicitud_completada',
                'solicitud_cancelada'
            ]);               // Usamos enum para tipo_evento
            $table->text('detalle');                     // Detalle del evento
            $table->timestamp('fecha')->default(now());  // Fecha y hora del evento
            $table->timestamps();                       // created_at y updated_at

            // Definición de claves foráneas
            $table->foreign('usuario_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('solicitud_id')->references('id')->on('solicitudes')->onDelete('cascade');
        });
    }

    /**
     * Revierte la migración.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('historiales');
    }
};
