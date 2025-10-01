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
    Schema::create('bills', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('flat_id');
        $table->unsignedBigInteger('bill_category_id');
        $table->string('month');
        $table->decimal('amount', 10, 2);
        $table->decimal('due_amount', 10, 2)->default(0);
        $table->enum('status', ['unpaid','paid'])->default('unpaid');
        $table->text('notes')->nullable();
        $table->timestamps();

        $table->foreign('flat_id')->references('id')->on('flats')->onDelete('cascade');
        $table->foreign('bill_category_id')->references('id')->on('bill_categories')->onDelete('cascade');
    });

}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bills');
    }
};
