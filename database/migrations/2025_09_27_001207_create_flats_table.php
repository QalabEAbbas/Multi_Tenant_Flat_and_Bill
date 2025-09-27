<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
    Schema::create('flats', function (Blueprint $table) {
        $table->id();
        $table->string('flat_number');
        $table->string('owner_name')->nullable();
        $table->string('owner_contact')->nullable();
        $table->foreignId('building_id')->constrained()->onDelete('cascade');
        $table->timestamps();
    });

    }

    public function down(): void
    {
        Schema::dropIfExists('flats');
    }
};
