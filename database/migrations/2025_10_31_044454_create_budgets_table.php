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
        Schema::create('budgets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('financial_period_id')->constrained('financial_periods')->onDelete('cascade');
            $table->string('name');
            $table->string('department')->nullable();
            $table->string('cost_center')->nullable();
            $table->enum('budget_type', ['operational', 'capital', 'departmental'])->default('operational');
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->decimal('executed_amount', 15, 2)->default(0);
            $table->enum('status', ['draft', 'approved', 'active', 'closed'])->default('draft');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('restrict');
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['tenant_id', 'financial_period_id']);
            $table->index(['tenant_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budgets');
    }
};
