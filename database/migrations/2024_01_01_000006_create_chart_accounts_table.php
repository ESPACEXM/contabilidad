<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chart_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('parent_id')->nullable()->constrained('chart_accounts')->onDelete('cascade');
            $table->string('code', 20);
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('type', ['activo', 'pasivo', 'capital', 'ingreso', 'egreso'])->index();
            $table->enum('nature', ['deudora', 'acreedora'])->default('deudora');
            $table->enum('level', ['cuenta_mayor', 'subcuenta', 'auxiliar'])->default('cuenta_mayor');
            $table->boolean('allows_movements')->default(true);
            $table->boolean('is_active')->default(true);
            $table->decimal('initial_balance', 15, 2)->default(0);
            $table->integer('order')->default(0);
            $table->timestamps();
            $table->softDeletes();
            
            $table->unique(['tenant_id', 'code']);
            $table->index(['tenant_id', 'type']);
            $table->index(['parent_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chart_accounts');
    }
};

