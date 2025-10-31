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
        Schema::create('financial_analysis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->enum('analysis_type', ['van', 'tir', 'break_even', 'payback', 'profitability_index'])->default('van');
            $table->decimal('initial_investment', 15, 2)->default(0);
            $table->decimal('discount_rate', 8, 4)->default(0);
            $table->json('cash_flows')->nullable();
            $table->decimal('result_value', 15, 2)->nullable();
            $table->decimal('fixed_costs', 15, 2)->nullable();
            $table->decimal('variable_cost_per_unit', 15, 2)->nullable();
            $table->decimal('selling_price_per_unit', 15, 2)->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('restrict');
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['tenant_id', 'analysis_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financial_analysis');
    }
};
