<div class="modal-header">
    <div>
        <h5 class="modal-title">Editar Tarefa</h5>
        <div class="modal-subtitle">Altere os campos desejados e salve as alterações</div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
</div>
<div class="modal-body">
    <form id="modal-form-edit" action="{{ route('tarefas.update', $tarefa) }}" method="POST">
        @csrf
        @method('PUT')
        @include('tarefas.partials.form-fields', ['tarefa' => $tarefa])
    </form>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
    <button type="submit" form="modal-form-edit" class="btn btn-primary">Salvar</button>
</div>
