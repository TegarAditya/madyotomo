<?php

use App\Models\Machine;
use App\Models\Material;
use App\Models\MaterialUsage;
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
        Schema::create('material_usage_items', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(MaterialUsage::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Material::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Machine::class)->constrained()->cascadeOnDelete();
            $table->integer('quantity');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_usage_items');
    }
};
