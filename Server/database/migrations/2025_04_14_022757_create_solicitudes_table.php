<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solicitudes', function (Blueprint $table) {
            $table->id(); // Id de la solicitud
            $table->unsignedBigInteger('usuario_id'); // Clave foránea a la tabla 'users'
            $table->unsignedBigInteger('recolector_id'); // Clave foránea a la tabla 'recolectores'
            $table->string('direccion_recojo'); // Dirección del recojo
            $table->string('numero_referencia'); // Número de referencia de la solicitud
            $table->text('detalles_casa'); // Detalles de la casa
            $table->enum('tipo_material', ['organico', 'inorganico_reciclable', 'inorganico_no_reciclable', 'peligroso', 'sanitario', 'electronico', 'construccion', 'nuclear']); // Tipo de material
            $table->text('detalles_adicionales')->nullable(); // Detalles adicionales
            $table->string('estado')->default('pendiente'); // Estado de la solicitud (pendiente, completada, etc.)
            $table->timestamp('fecha_solicitud')->useCurrent(); // Fecha de solicitud
            $table->timestamp('fecha_programada')->nullable(); // Fecha programada para el recojo
            $table->timestamp('fecha_recojo')->nullable(); // Fecha de recojo real
            $table->decimal('latitud', 10, 8); // Latitud de la ubicación
            $table->decimal('longitud', 11, 8); // Longitud de la ubicación
            $table->enum('tamano_residuo', ['pequeño', 'mediano', 'grande']); // Tamaño del residuo
            $table->enum('tipo_residuo', ['organico', 'inorganico_reciclable', 'inorganico_no_reciclable', 'peligroso', 'sanitario', 'electronico', 'construccion', 'nuclear']); // Tipo de residuo
            $table->timestamps(); // Tiempos de creación y actualización

            // Claves foráneas
            $table->foreign('usuario_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('recolector_id')->references('id')->on('recolectores')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('solicitudes');
    }
};
