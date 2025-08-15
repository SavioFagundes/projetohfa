@if($tarefas->count())
    <style>
        /* Action buttons 'life' styles */
        .table .action-btn { border: none; padding: 6px 10px; border-radius: 6px; font-size: 13px; font-weight:600; transition: transform .12s ease, box-shadow .12s ease, opacity .12s ease; }
        .table .action-btn:active { transform: translateY(1px); }
        .table .action-btn:focus { outline: none; box-shadow: 0 0 0 3px rgba(13,110,253,0.12); }
        .table .action-show { background: linear-gradient(90deg,#0d6efd,#3aa0ff); color: #fff; }
        .table .action-show:hover { transform: translateY(-3px); box-shadow: 0 6px 18px rgba(58,160,255,0.18); }
        .table .action-edit { background: linear-gradient(90deg,#f59e0b,#f97316); color: #fff; }
        .table .action-edit:hover { transform: translateY(-3px); box-shadow: 0 6px 18px rgba(249,115,22,0.18); }
        .table .action-delete { background: linear-gradient(90deg,#ef4444,#f43f5e); color: #fff; }
    .table .action-delete:hover { transform: translateY(-3px); box-shadow: 0 6px 18px rgba(244,63,94,0.18); }
    /* Icon spacing inside action buttons */
    .table .action-btn svg { vertical-align: middle; margin-right: 6px; width: 14px; height: 14px; }
    </style>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Título</th>
                <th>Status</th>
                <th>Prioridade</th>
                <th>Data Limite</th>
                <th>Criada em</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tarefas as $tarefa)
                <tr class="{{ $tarefa->is_atrasada ? 'table-danger' : '' }}">
                    <td class="align-middle">{{ $tarefa->titulo }}</td>
                    <td class="align-middle">
                        <div class="d-flex align-items-center">
                            <select class="form-select form-select-sm status-select" data-id="{{ $tarefa->id }}" style="width:160px;">
                                @foreach(\App\Models\Tarefa::statuses() as $st)
                                    <option value="{{ $st }}" {{ $tarefa->status === $st ? 'selected' : '' }}>{{ $st }}</option>
                                @endforeach
                            </select>
                            <span class="ms-2 status-badge">
                                @if($tarefa->status === \App\Models\Tarefa::STATUS_PENDENTE)
                                    <span class="badge badge-pendente">{{ $tarefa->status }}</span>
                                @elseif($tarefa->status === \App\Models\Tarefa::STATUS_EM_ANDAMENTO)
                                    <span class="badge badge-andamento">{{ $tarefa->status }}</span>
                                @else
                                    <span class="badge badge-concluida">{{ $tarefa->status }}</span>
                                @endif
                            </span>
                        </div>
                    </td>
                    <td class="align-middle">
                        @if($tarefa->prioridade === \App\Models\Tarefa::PRIORIDADE_ALTA)
                            <span class="badge bg-danger">{{ $tarefa->prioridade }}</span>
                        @elseif($tarefa->prioridade === \App\Models\Tarefa::PRIORIDADE_MEDIA)
                            <span class="badge bg-warning text-dark">{{ $tarefa->prioridade }}</span>
                        @else
                            <span class="badge bg-secondary">{{ $tarefa->prioridade }}</span>
                        @endif
                    </td>
                    <td>{{ $tarefa->data_limite?->format('d/m/Y') ?? '-' }}</td>
                    <td>{{ $tarefa->created_at->format('d/m/Y') }}</td>
                    <td>
                        <div class="d-flex flex-row gap-2 align-items-center justify-content-center">
                            <button type="button" class="btn btn-sm action-btn action-show btn-modal-show" data-url="{{ route('tarefas.modal.show', $tarefa) }}" aria-label="Ver tarefa">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                Ver
                            </button>
                            <button type="button" class="btn btn-sm action-btn action-edit btn-modal-edit" data-url="{{ route('tarefas.modal.edit', $tarefa) }}" aria-label="Editar tarefa">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.5 2.5a2.121 2.121 0 113 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                Editar
                            </button>
                            <form action="{{ route('tarefas.destroy', $tarefa) }}" method="POST" class="d-inline form-delete" data-id="{{ $tarefa->id }}" style="margin:0;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm action-btn action-delete" aria-label="Excluir">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22"/></svg>
                                    Excluir
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $tarefas->links() }}
@else
    <div class="alert alert-info">Nenhuma tarefa encontrada.</div>
@endif
