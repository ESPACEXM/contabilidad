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
        Schema::create('budget_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('budget_id')->constrained()->onDelete('cascade');
            $table->foreignId('chart_account_id')->nullable()->constrained('chart_accounts')->onDelete('set null');
            $table->string('description');
            $table->decimal('budgeted_amount', 15, 2);
            $table->decimal('executed_amount', 15, 2)->default(0);
            $table->integer('month')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['budget_id', 'month']);
            $table->index('chart_account_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budget_items');
    }
};
