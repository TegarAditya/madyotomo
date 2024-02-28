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
            $table->string('document_number');
            $table->string('proof_number');
            $table->string('name');
            $table->unsignedBigInteger('customer_id');
            $table->date('entry_date');
            $table->date('deadline_date');
            $table->unsignedBigInteger('paper_id');
            $table->int('paper_config');
            $table->double('finished_size');
            $table->double('material_size');
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
