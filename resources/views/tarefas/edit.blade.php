@extends('layouts.app')

@section('title', 'Editar Tarefa')

@section('content')
<h1>Editar Tarefa</h1>

<form action="{{ route('tarefas.update', $tarefa) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label for="titulo" class="form-label">Título <span class="text-danger">*</span></label>
        <input type="text" name="titulo" id="titulo" value="{{ old('titulo', $tarefa->titulo) }}"
               class="form-control @error('titulo') is-invalid @enderror" maxlength="255" required>
        @error('titulo')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="descricao" class="form-label">Descrição</label>
        <textarea name="descricao" id="descricao" class="form-control @error('descricao') is-invalid @enderror" maxlength="255">{{ old('descricao', $tarefa->descricao) }}</textarea>
        @error('descricao')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
        <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
            <option value="" {{ old('status', $tarefa->status) === '' ? 'selected' : '' }}>Não selecionado</option>
            @foreach($statuses as $status)
                <option value="{{ $status }}" {{ old('status', $tarefa->status) == $status ? 'selected' : '' }}>
                    {{ $status }}
                </option>
            @endforeach
        </select>
        @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <div id="status-hint" class="form-text text-danger" style="display: none;">O status não foi selecionado — selecione um status válido para habilitar o botão de envio.</div>
     </div>

    <div class="mb-3">
        <label for="prioridade" class="form-label">Prioridade</label>
        <select name="prioridade" id="prioridade" class="form-select @error('prioridade') is-invalid @enderror">
            @foreach($prioridades as $p)
                <option value="{{ $p }}" {{ old('prioridade', $tarefa->prioridade) == $p ? 'selected' : '' }}>{{ $p }}</option>
            @endforeach
        </select>
        @error('prioridade')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="data_limite" class="form-label">Data Limite</label>
        <input type="date" name="data_limite" id="data_limite" value="{{ old('data_limite', optional($tarefa->data_limite)->format('Y-m-d')) }}"
               class="form-control @error('data_limite') is-invalid @enderror">
        @error('data_limite')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <button id="submit-btn" type="submit" class="btn btn-primary" @if(empty(old('status', $tarefa->status))) disabled @endif>Salvar</button>
    <a href="{{ route('tarefas.index') }}" class="btn btn-secondary">Cancelar</a>
</form>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
    const status = document.getElementById('status');
    const submit = document.getElementById('submit-btn');
    const hint = document.getElementById('status-hint');
    if(!status || !submit) return;
    function toggle() {
        submit.disabled = status.value === '';
        if(hint) hint.style.display = status.value === '' ? 'block' : 'none';
    }
    status.addEventListener('change', toggle);
    toggle();
});
</script>
@endpush
