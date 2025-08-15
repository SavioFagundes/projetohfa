<div class="modal-header">
    <div>
        <h5 class="modal-title">Nova Tarefa</h5>
        <div class="modal-subtitle">Preencha os dados para criar uma nova tarefa</div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
</div>
<div class="modal-body">
    <form id="modal-form-create" action="{{ route('tarefas.store') }}" method="POST">
        @csrf
        @include('tarefas.partials.form-fields', ['tarefa' => null])
    </form>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
    <button type="submit" form="modal-form-create" class="btn btn-primary">Criar</button>
</div>
