<div class="modal-header">
    <div>
        <h5 class="modal-title">{{ $tarefa->titulo }}</h5>
        <div class="modal-subtitle">Status: {{ $tarefa->status }} • Prioridade:
            @if($tarefa->prioridade === \App\Models\Tarefa::PRIORIDADE_ALTA)
                <span class="badge bg-danger">{{ $tarefa->prioridade }}</span>
            @elseif($tarefa->prioridade === \App\Models\Tarefa::PRIORIDADE_MEDIA)
                <span class="badge bg-warning text-dark">{{ $tarefa->prioridade }}</span>
            @else
                <span class="badge bg-secondary">{{ $tarefa->prioridade ?? '-' }}</span>
            @endif
        </div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
</div>
<div class="modal-body">
    <h5>{{ $tarefa->titulo }}</h5>
    <p>{{ $tarefa->descricao ?? '-' }}</p>
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
    @include('tarefas.partials.comments')
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
</div>
