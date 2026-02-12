<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('prestamos', function (Blueprint $table) {
            $table->id();
            $table->decimal('monto', 10, 2);
            $table->decimal('total_pagar', 10, 2);
            $table->decimal('ganancia', 10, 2)->default(0);
            $table->string('metodo', 7);
            $table->string('metodo_pago', 9);
            $table->decimal('interes', 10, 2);
            $table->integer('cantidad')->default(0);
            $table->integer('estado')->default(1);
            $table->unsignedBigInteger('id_cliente');
            $table->unsignedBigInteger('id_usuario');
            $table->string('letra', 255)->nullable();
            $table->string('garantia', 255)->nullable();
            $table->string('foto', 255)->nullable();
            $table->timestamps();


            $table->foreign('id_cliente')->references('id')->on('clientes')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('id_usuario')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prestamos');
    }
};
