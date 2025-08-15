@extends('layouts.app')

@section('title', 'Criar Tarefa')

@section('content')
<div class="dashboard-container">
    <div class="card-custom p-4">
        <h1 class="h4 mb-3">Criar Tarefa</h1>

        <form action="{{ route('tarefas.store') }}" method="POST">
            @csrf

            @include('tarefas.partials.form-fields', ['tarefa' => null])

            <div class="d-flex gap-2 mt-3">
                <button id="submit-btn" type="submit" class="btn btn-primary">Criar</button>
                <a href="{{ route('tarefas.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection

<!-- Botão sempre habilitado. Validação obrigatória será feita no backend. -->