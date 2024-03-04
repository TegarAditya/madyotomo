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
            $table->string('document_number')->unique();
            $table->string('proof_number');
            $table->string('name')->nullable();
            $table->date('entry_date')->nullable();
            $table->date('deadline_date')->nullable();
            $table->integer('paper_config');
            $table->string('finished_size');
            $table->string('material_size');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('paper_id');
            $table->integer('sort')->nullable();
            $table->timestamps();
            $table->softDeletes();
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
