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
        Schema::create('financial_projections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->enum('scenario', ['optimistic', 'expected', 'pessimistic'])->default('expected');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('projection_periods');
            $table->enum('period_type', ['monthly', 'quarterly', 'yearly'])->default('monthly');
            $table->json('sales_projection')->nullable();
            $table->json('expenses_projection')->nullable();
            $table->json('cash_flow_projection')->nullable();
            $table->decimal('growth_rate', 8, 4)->default(0);
            $table->text('assumptions')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('restrict');
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['tenant_id', 'scenario']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financial_projections');
    }
};
