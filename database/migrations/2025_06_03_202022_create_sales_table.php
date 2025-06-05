<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('parent_sale_id')->nullable()->constrained('sales')->onDelete('cascade'); 
            $table->decimal('total_amount', 10, 2);
            $table->integer('installment_number')->nullable(); 
            $table->decimal('amount', 10, 2)->nullable();
            $table->date('due_date')->nullable();
            $table->string('status')->default('pending'); 
            $table->dateTime('sale_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};