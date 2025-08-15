@extends('layouts.app')

@section('title', 'Ver Tarefa')

@section('content')
<h1>Ver Tarefa</h1>

<div class="card mb-3">
    <div class="card-body">
        <h4 class="card-title">{{ $tarefa->titulo }}</h4>
        <p class="card-text">{{ $tarefa->descricao ?? '-' }}</p>
        <p><strong>Status:</strong> {{ $tarefa->status }}</p>
    <p><strong>Prioridade:</strong>
        @if($tarefa->prioridade === \App\Models\Tarefa::PRIORIDADE_ALTA)
            <span class="badge bg-danger">{{ $tarefa->prioridade }}</span>
        @elseif($tarefa->prioridade === \App\Models\Tarefa::PRIORIDADE_MEDIA)
            <span class="badge bg-warning text-dark">{{ $tarefa->prioridade }}</span>
        @else
            <span class="badge bg-secondary">{{ $tarefa->prioridade ?? '-' }}</span>
        @endif
    </p>
    <p><strong>Data Limite:</strong> {{ $tarefa->data_limite?->format('d/m/Y') ?? '-' }}</p>
    <p><small class="text-muted">Criada em: {{ $tarefa->created_at->format('d/m/Y H:i') }}</small></p>
    @include('tarefas.partials.comments')

        <a href="{{ route('tarefas.edit', $tarefa) }}" class="btn btn-warning">Editar</a>

        <form action="{{ route('tarefas.destroy', $tarefa) }}" method="POST" class="d-inline" id="form-delete">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger" onclick="return confirm('Tem certeza que deseja excluir esta tarefa?');">Excluir</button>
        </form>

        <a href="{{ route('tarefas.index') }}" class="btn btn-secondary">Voltar</a>
    </div>
</div>
@endsection