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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('status')->default("pending");
            $table->float('total');
            $table->float('shipping_cost')->default(0);
            $table->integer('shipping_days')->default(0);
            $table->string('shipping_zipcode', 9); 
            $table->string('shipping_street');
            $table->string('shipping_number', 10)->nullable();
            $table->string('shipping_city');
            $table->string('shipping_state', 2); 
            $table->string('shipping_country')->default('Brasil');
            $table->string('shipping_complement')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
