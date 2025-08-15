<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Tarefa;

class UpdateTarefaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'titulo' => ['required', 'string', 'max:255'],
            'descricao' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'string', 'in:' . implode(',', Tarefa::statuses())],
            'prioridade' => ['nullable', 'string', 'in:' . implode(',', Tarefa::prioridades())],
            'data_limite' => ['nullable', 'date', 'after_or_equal:today'],
        ];
    }

    public function messages(): array
    {
        return [
            'titulo.required' => 'O título é obrigatório.',
            'titulo.max' => 'O título não pode exceder 255 caracteres.',
            'descricao.max' => 'A descrição não pode exceder 255 caracteres.',
            'status.required' => 'O status é obrigatório.',
            'status.in' => 'O status selecionado não é válido.',
            'data_limite.date' => 'A data limite deve ser uma data válida.',
            'data_limite.after_or_equal' => 'A data limite deve ser hoje ou uma data futura.',
        ];
    }
}