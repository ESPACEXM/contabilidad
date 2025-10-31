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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained('product_categories')->onDelete('set null');
            $table->string('sku')->nullable();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('type', ['product', 'service'])->default('product');
            $table->decimal('unit_price', 15, 2)->default(0);
            $table->decimal('cost_price', 15, 2)->default(0);
            $table->string('unit_of_measure', 50)->default('pza');
            $table->decimal('stock_quantity', 15, 3)->default(0);
            $table->decimal('min_stock', 15, 3)->default(0);
            $table->decimal('max_stock', 15, 3)->nullable();
            $table->enum('valuation_method', ['fifo', 'lifo', 'average'])->default('average');
            $table->string('image')->nullable();
            $table->boolean('track_inventory')->default(true);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            
            $table->unique(['tenant_id', 'sku']);
            $table->index(['tenant_id', 'is_active']);
            $table->index(['tenant_id', 'category_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
