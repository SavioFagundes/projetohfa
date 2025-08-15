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
        Schema::table('tarefas', function (Blueprint $table) {
            // Adiciona prioridade se não existir
            if (! Schema::hasColumn('tarefas', 'prioridade')) {
                $table->string('prioridade', 50)->default('Médio')->after('status');
            }

            // Adiciona user_id para relacionar ao dono
            if (! Schema::hasColumn('tarefas', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('prioridade');
                // não adicionamos FK por compatibilidade com sqlite simples
                $table->index('user_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tarefas', function (Blueprint $table) {
            if (Schema::hasColumn('tarefas', 'user_id')) {
                $table->dropIndex(['user_id']);
                $table->dropColumn('user_id');
            }

            if (Schema::hasColumn('tarefas', 'prioridade')) {
                $table->dropColumn('prioridade');
            }
        });
    }
};
