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
        Schema::create('spks', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id');
            $table->string('document_number');
            $table->string('report_number');
            $table->date('entry_date');
            $table->date('deadline_date');
            $table->integer('machine_id');
            $table->text('note');
            $table->string('print_type');
            $table->string('spare');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spks');
    }
};
