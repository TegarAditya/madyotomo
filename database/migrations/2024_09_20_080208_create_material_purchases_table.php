<?php

use App\Models\MaterialSupplier;
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
        Schema::create('material_purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(MaterialSupplier::class)->constrained()->cascadeOnDelete();
            $table->string('proof_number');
            $table->date('purchase_date');
            $table->integer('quantity');
            $table->boolean('is_paid')->default(false);
            $table->date('paid_off_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_purchases');
    }
};
