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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('original_file');
            $table->string('signed_file')->nullable();
            $table->boolean('is_signed')->default(false);
            $table->string('signature_type')->nullable(); // typed or drawn
            $table->text('typed_signature')->nullable(); // if typed
            $table->string('drawn_signature_file')->nullable(); // if drawn
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
