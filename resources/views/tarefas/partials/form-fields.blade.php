<div class="row g-3">
    <div class="col-12 col-md-8">
        <label for="titulo" class="form-label">Título <span class="text-danger">*</span></label>
        <input type="text" name="titulo" id="titulo" value="{{ old('titulo', optional($tarefa)->titulo) }}"
               class="form-control" maxlength="255" required>
    </div>

    <div class="col-12 col-md-4">
        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
        <select name="status" id="status" class="form-select" required>
            <option value="" {{ old('status', optional($tarefa)->status) === null ? 'selected' : '' }}>Não selecionado</option>
            @foreach(\App\Models\Tarefa::statuses() as $status)
                <option value="{{ $status }}" {{ old('status', optional($tarefa)->status) == $status ? 'selected' : '' }}>{{ $status }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-12">
        <label for="descricao" class="form-label">Descrição</label>
        <textarea name="descricao" id="descricao" class="form-control" rows="4" maxlength="255">{{ old('descricao', optional($tarefa)->descricao) }}</textarea>
    </div>

    <div class="col-12 col-md-6">
        <label for="prioridade" class="form-label">Prioridade</label>
        <select name="prioridade" id="prioridade" class="form-select">
            @foreach(\App\Models\Tarefa::prioridades() as $p)
                <option value="{{ $p }}" {{ old('prioridade', optional($tarefa)->prioridade) == $p ? 'selected' : '' }}>{{ $p }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-12 col-md-6">
        <label for="data_limite" class="form-label">Data Limite</label>
        <input type="date" name="data_limite" id="data_limite" value="{{ old('data_limite', optional(optional($tarefa)->data_limite)->format('Y-m-d')) }}" class="form-control">
    </div>
</div>
