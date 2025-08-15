<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarefa extends Model
{
    use HasFactory;

    // Campos em assignable mass (permitidos para fill)
    protected $fillable = [
        'titulo',
        'descricao',
    'status',
    'data_limite',
    'prioridade',
    'user_id',
    ];

    // Casts para manipulação automática de tipos
    protected $casts = [
        'data_limite' => 'date',
    ];

    // Valores possíveis para status (útil para validação e views)
    public const STATUS_PENDENTE = 'Pendente';
    public const STATUS_EM_ANDAMENTO = 'Em Andamento';
    public const STATUS_CONCLUIDA = 'Concluída';

    public static function statuses(): array
    {
        return [
            self::STATUS_PENDENTE,
            self::STATUS_EM_ANDAMENTO,
            self::STATUS_CONCLUIDA,
        ];
    }

    // Prioridade levels
    public const PRIORIDADE_BAIXA = 'Baixa';
    public const PRIORIDADE_MEDIA = 'Médio';
    public const PRIORIDADE_ALTA = 'Alta';

    public static function prioridades(): array
    {
        return [
            self::PRIORIDADE_BAIXA,
            self::PRIORIDADE_MEDIA,
            self::PRIORIDADE_ALTA,
        ];
    }

    /**
     * Indica se a tarefa está atrasada: data_limite anterior a hoje e não concluída.
     */
    public function getIsAtrasadaAttribute(): bool
    {
        if (! $this->data_limite) {
            return false;
        }

        // Considera atrasada se a data_limite já passou (exclui hoje) e status != concluída
        $today = now()->startOfDay();
        return $this->data_limite->startOfDay()->lt($today) && $this->status !== self::STATUS_CONCLUIDA;
    }

    /**
     * Scope para filtrar apenas tarefas atrasadas.
     */
    public function scopeAtrasadas($query)
    {
        return $query->whereNotNull('data_limite')
                     ->whereDate('data_limite', '<', now()->toDateString())
                     ->where('status', '!=', self::STATUS_CONCLUIDA);
    }

    /**
     * Relacionamento com User (owner)
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    /**
     * Comments associated with this tarefa.
     */
    public function comments()
    {
        return $this->hasMany(\App\Models\Comment::class)->latest();
    }

    /**
     * Scope para limitar tarefas ao usuário autenticado.
     */
    public function scopeForUser($query, $user)
    {
        if (! $user) return $query;
        return $query->where('user_id', $user->id);
    }
}