<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('recolectores', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('apellido');
            $table->string('ci')->unique(); // antes era dni
            $table->string('telefono')->nullable();
            $table->string('email')->unique()->nullable(); // puede añadirse luego
            $table->string('direccion')->nullable(); // se puede añadir luego
            $table->enum('licencia', ['A', 'B', 'C', 'P'])->nullable(); // enum y opcional
            $table->string('estado')->default('activo');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recolectores');
    }
};
