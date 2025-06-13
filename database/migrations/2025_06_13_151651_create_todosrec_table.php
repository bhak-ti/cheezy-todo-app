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
        Schema::create('TODOSREC', function (Blueprint $table) {
            $table->id('TODOID'); // Primary Key
            $table->string('TODODESC'); // Deskripsi tugas
            $table->boolean('TODOISDONE')->default(false); // Status
            $table->timestamp('TODOFINISHTIMESTAMP')->nullable(); // Waktu selesai
            $table->timestamps(); // CREATED_AT dan UPDATED_AT
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('TODOSREC');
    }
};
