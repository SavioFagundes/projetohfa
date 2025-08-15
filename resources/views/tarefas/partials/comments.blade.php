@if(isset($tarefa))
    @php
        // Avoid querying comments table if migrations haven't been run yet
        $hasCommentsTable = \Illuminate\Support\Facades\Schema::hasTable('comments');
    @endphp

    <div class="comments-section mt-3">
        <h6>Comentários</h6>

        @if($hasCommentsTable)
            <form action="{{ route('tarefas.comments.store', $tarefa) }}" method="POST">
                @csrf
                <div class="mb-2">
                    <textarea name="body" class="form-control" rows="3" placeholder="Escreva um comentário..."></textarea>
                </div>
                <div class="d-flex justify-content-end">
                    <button class="btn btn-primary btn-sm">Adicionar comentário</button>
                </div>
            </form>

            <div class="mt-3">
                @foreach($tarefa->comments as $comment)
                    <div class="border rounded p-2 mb-2">
                        <div class="small text-muted">{{ $comment->user?->name ?? 'Anônimo' }} • {{ $comment->created_at->format('d/m/Y H:i') }}</div>
                        <div class="mt-1">{{ $comment->body }}</div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="alert alert-warning small">Seus comentários ainda não estão disponíveis — por favor execute as migrations (tabela <code>comments</code> ausente).</div>
        @endif

    </div>
@endif
