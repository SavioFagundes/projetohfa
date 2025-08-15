@extends('layouts.app')

@section('title', 'Lista de Tarefas')

@section('content')
<div class="dashboard-container">
    <!-- Hide global logout links on this page only -->
    <style>
        .nav-logout, .btn-logout { display: none !important; }
    /* Make tarefas table scrollable with a vertical scrollbar on the right */
        #tarefas-table {
            max-height: 70vh;
            overflow-y: auto;
            overflow-x: auto;
            /* keep a small right padding so scrollbar doesn't overlap content */
            padding-right: 8px;
        }
        /* Keep table full width and remove bottom margin inside the scrollable box */
        #tarefas-table .table { width: 100%; margin-bottom: 0; border-collapse: collapse; }
        /* Make the table header sticky so only rows scroll */
        #tarefas-table .table thead th {
            position: sticky;
            top: 0;
            background: #ffffff;
            z-index: 2;
        }
        /* Small scrollbar styling (WebKit) to improve visibility */
        #tarefas-table::-webkit-scrollbar { width: 10px; }
        #tarefas-table::-webkit-scrollbar-thumb { background: rgba(0,0,0,0.12); border-radius: 5px; }
        /* Buttons for filters */
        .btn-apply {
            background: linear-gradient(90deg,#0d6efd,#3aa0ff);
            color: #fff;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            font-weight: 600;
            transition: transform .12s ease, box-shadow .12s ease;
        }
        .btn-apply:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(58,160,255,0.12); }
        .btn-clear {
            background: linear-gradient(90deg,#f3f4f6,#e9eef9);
            color: #374151;
            border: 1px solid rgba(0,0,0,0.06);
            padding: 6px 12px;
            border-radius: 6px;
            font-weight: 600;
        }
        .btn-clear:hover { transform: translateY(-1px); box-shadow: 0 6px 14px rgba(0,0,0,0.06); }
        /* inline logout button for tarefas page */
        .btn-logout-inline {
            background: linear-gradient(90deg,#ef4444,#f59e42);
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 6px 14px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: opacity .15s;
        }
        .btn-logout-inline:hover { opacity: .9; }
    </style>
    {{-- Header: título + ações primárias --}}
    <header class="d-flex justify-content-between align-items-center mb-3 dashboard-hero">
        <div>
            <h1 class="h3 mb-0">Lista de Tarefas</h1>
            <div class="small muted">Gerencie suas tarefas — crie, filtre e atualize rapidamente</div>
        </div>

        <div class="header-actions d-flex align-items-center">
            <a href="{{ route('tarefas.create') }}" class="btn btn-primary">Nova Tarefa</a>
            <form action="{{ route('tarefas.generate') }}" method="POST" class="d-inline ms-2">
                @csrf
                
            </form>
            <form method="POST" action="{{ route('logout') }}" class="d-inline ms-2">
                @csrf
                <button type="submit" class="btn btn-logout-inline">Sair</button>
            </form>
        </div>
    </header>

    {{-- Filters: busca, status, datas e ordenação --}}
    <section class="mb-3">
        <form id="filtersForm" method="GET" class="row g-2 align-items-center">
            <div class="col-auto">
                <input name="q" value="{{ request('q') }}" type="search" class="form-control search-input" placeholder="Pesquisar por título...">
            </div>
            <div class="col-auto">
                <select name="status" id="filter-status" class="form-select">
                    <option value="">Todos os status</option>
                    @foreach($statuses as $st)
                        <option value="{{ $st }}" {{ request('status') === $st ? 'selected' : '' }}>{{ $st }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto">
                <input name="from" id="filter-from" value="{{ request('from') }}" type="date" class="form-control" placeholder="De">
            </div>
            <div class="col-auto">
                <input name="to" id="filter-to" value="{{ request('to') }}" type="date" class="form-control" placeholder="Até">
            </div>
            
            <div class="col-auto">
                <button type="submit" class="btn btn-apply">Aplicar</button>
                <a href="{{ route('tarefas.index') }}" class="btn btn-clear ms-1">Limpar</a>
            </div>
            <div class="col text-end text-muted">Total: {{ $tarefas->total() }}</div>
        </form>
    </section>

    {{-- Applied filters as removable badges --}}
    @php
        $query = request()->query();
        $applied = collect($query)->except(['page'])->filter(function($v){ return $v !== null && $v !== '';})->all();
    @endphp

    @if(count($applied))
        <section class="mb-3 applied-filters">
            <div class="d-flex align-items-center flex-wrap">
                @if(request('q'))
                    <a href="{{ route('tarefas.index', \Illuminate\Support\Arr::except($query, ['q','page'])) }}" class="badge bg-primary text-white me-2 mb-2">Pesquisar: "{{ request('q') }}" &times;</a>
                @endif

                @if(request('status'))
                    <a href="{{ route('tarefas.index', \Illuminate\Support\Arr::except($query, ['status','page'])) }}" class="badge bg-info text-dark me-2 mb-2">Status: {{ request('status') }} &times;</a>
                @endif

                @if(request('from'))
                    <a href="{{ route('tarefas.index', \Illuminate\Support\Arr::except($query, ['from','page'])) }}" class="badge bg-secondary text-white me-2 mb-2">De: {{ request('from') }} &times;</a>
                @endif

                @if(request('to'))
                    <a href="{{ route('tarefas.index', \Illuminate\Support\Arr::except($query, ['to','page'])) }}" class="badge bg-secondary text-white me-2 mb-2">Até: {{ request('to') }} &times;</a>
                @endif

                {{-- sort/direction filters removed --}}

                <a href="{{ route('tarefas.index') }}" class="btn btn-sm btn-outline-secondary ms-2 mb-2">Limpar filtros</a>
            </div>
        </section>
    @endif

    {{-- Table partial: carregável via AJAX --}}
    <section class="card-custom table-card">
        <div class="card-body">
            <div id="tarefas-table" class="table-responsive">
                @include('tarefas.partials.table')
            </div>
        </div>
    </section>

    <!-- Modal container -->
    <div class="modal fade" id="mainModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" id="mainModalContent">
                <!-- Conteúdo carregado via AJAX -->
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
    const input = document.querySelector('input[name="q"]');
    if(!input) return;
    let timer = null;
    input.addEventListener('input', function(){
        // Client-side filter for immediate feedback
        clearTimeout(timer);
        const q = input.value.toLowerCase().trim();
        const rows = document.querySelectorAll('table tbody tr');
        rows.forEach(r => {
            const title = (r.querySelector('td')?.textContent || '').toLowerCase();
            r.style.display = title.includes(q) ? '' : 'none';
        });
        // Also debounce submit to refresh server-side results after pause
        timer = setTimeout(() => {
            const form = document.getElementById('filtersForm');
            if(form) form.submit();
        }, 800);
    });
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function(){
    const form = document.getElementById('filtersForm');
    if(!form) return;

    const submitForm = () => form.submit();

    // submit when selects or date inputs change
    ['#filter-status', '#filter-from', '#filter-to'].forEach(sel => {
        const el = document.querySelector(sel);
        if(el) el.addEventListener('change', submitForm);
    });
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function(){
    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if(!csrf) return;

    function showTempMessage(el, msg, cls='success'){
        const span = document.createElement('span');
        span.className = cls === 'success' ? 'text-success ms-2 small' : 'text-danger ms-2 small';
        span.textContent = msg;
        el.appendChild(span);
        setTimeout(()=> span.remove(), 2000);
    }

    document.querySelectorAll('.status-select').forEach(select => {
        select.addEventListener('change', async function(){
            const id = this.dataset.id;
            const status = this.value;
            const row = this.closest('tr');
            const badgeContainer = row.querySelector('.status-badge');

            // Disable while processing
            this.disabled = true;

            try {
                const res = await fetch(`{{ url('') }}/tarefas/${id}/status`, {
                    method: 'PATCH',
                    credentials: 'same-origin',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrf,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ status })
                });

                if(!res.ok){
                    const err = await res.json().catch(()=> ({}));
                    showTempMessage(badgeContainer, err.message || 'Erro', 'error');
                } else {
                    const json = await res.json();
                    // Update badge text and class
                    if(json.success){
                        // Replace badge content
                        badgeContainer.innerHTML = '';
                        const span = document.createElement('span');
                        span.className = (status === '{{ \App\Models\Tarefa::STATUS_PENDENTE }}') ? 'badge badge-pendente' : (status === '{{ \App\Models\Tarefa::STATUS_EM_ANDAMENTO }}' ? 'badge badge-andamento' : 'badge badge-concluida');
                        span.textContent = status;
                        badgeContainer.appendChild(span);
                        showTempMessage(badgeContainer, 'Atualizado');
                    }
                }
            } catch(e){
                showTempMessage(badgeContainer, 'Erro');
            } finally {
                this.disabled = false;
            }
        });
    });
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function(){
    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    const mainModalEl = document.getElementById('mainModal');
    const mainModal = new bootstrap.Modal(mainModalEl);

    async function loadIntoModal(url){
        try {
            const res = await fetch(url, { credentials: 'same-origin', headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            if(!res.ok){
                // Try to extract text/HTML from response to show details
                let details = '';
                try { details = await res.text(); } catch(e) { details = ''; }
                const body = `\n                    <div class="modal-header">\n                        <h5 class="modal-title">Erro ao carregar</h5>\n                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>\n                    </div>\n                    <div class="modal-body">\n                        <p>Não foi possível carregar o conteúdo. ${res.status === 403 ? 'Você não tem permissão para ver esta tarefa.' : ''}</p>\n                        <pre style="white-space:pre-wrap; background:#f8f9fa; padding:10px; border-radius:6px;">${details}</pre>\n                    </div>`;
                document.getElementById('mainModalContent').innerHTML = body;
                mainModal.show();
                return;
            }
            const html = await res.text();
            document.getElementById('mainModalContent').innerHTML = html;
            mainModal.show();
        } catch (err) {
            const body = `\n                <div class="modal-header">\n                    <h5 class="modal-title">Erro de rede</h5>\n                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>\n                </div>\n                <div class="modal-body">\n                    <p>Falha ao conectar ao servidor para carregar o modal.</p>\n                    <pre style="white-space:pre-wrap; background:#f8f9fa; padding:10px; border-radius:6px;">${err.message}</pre>\n                </div>`;
            document.getElementById('mainModalContent').innerHTML = body;
            mainModal.show();
        }
    }

    // Open create modal (AJAX) with fallback to full page if AJAX fails
    document.querySelector('.header-actions a.btn-primary')?.addEventListener('click', async function(e){
        const link = this;
        e.preventDefault();
        try {
            await loadIntoModal('{{ route('tarefas.modal.create') }}');
        } catch (err) {
            console.error('[DEBUG] modal-create failed, falling back to full navigation', err);
            // fallback to the link href (normal create page)
            window.location.href = link.href;
        }
    });

    // Delegation for show/edit buttons inside table
    document.getElementById('tarefas-table')?.addEventListener('click', function(e){
        const showBtn = e.target.closest('.btn-modal-show');
        const editBtn = e.target.closest('.btn-modal-edit');
        if(showBtn){
            e.preventDefault?.();
            console.log('[DEBUG] modal button clicked', { url: showBtn.dataset.url, type: 'show' });
            loadIntoModal(showBtn.dataset.url);
            return;
        }
        if(editBtn){
            e.preventDefault?.();
            console.log('[DEBUG] modal button clicked', { url: editBtn.dataset.url, type: 'edit' });
            // Scroll the task row into view before opening the modal
            try{
                const row = editBtn.closest('tr');
                if(row) row.scrollIntoView({ behavior: 'smooth', block: 'center' });
            } catch(err){ /* ignore */ }
            loadIntoModal(editBtn.dataset.url);
            return;
        }
    });

    // Delegate form submissions inside modal (create/edit) - sempre funciona mesmo após recarregar modal
    document.body.addEventListener('submit', async function(e){
        const form = e.target;
        // Só intercepta forms do modal de tarefas
        if(!form.closest('#mainModalContent')) return;
        e.preventDefault();
        const action = form.action;
        const method = form.querySelector('input[name="_method"]')?.value ?? (form.method || 'POST');
        const formData = new FormData(form);
        console.log('[DEBUG] modal form submit', { action: form.action, method: form.method });
        try {
            const res = await fetch(action, {
                method: 'POST',
                credentials: 'same-origin',
                headers: { 'X-CSRF-TOKEN': csrf, 'X-Requested-With': 'XMLHttpRequest' },
                body: formData
            });
            console.log('[DEBUG] modal form response', { ok: res.ok, status: res.status });
            if(res.redirected){
                window.location.href = res.url;
                return;
            }
            if(!res.ok){
                const text = await res.text();
                document.getElementById('mainModalContent').innerHTML = text;
                return;
            }
            mainModal.hide();
            await refreshTable();
        } catch(err){
            console.error(err);
            alert('Erro ao enviar formulário');
        }
    });

    // Delegate delete forms to AJAX
    async function handleDelete(form){
        if(!confirm('Tem certeza que deseja excluir esta tarefa?')) return;
        const action = form.action;
        const formData = new FormData(form);
        try{
            const res = await fetch(action, {
                method: 'POST', // use POST + _method override for compatibility
                credentials: 'same-origin',
                headers: { 'X-CSRF-TOKEN': csrf, 'X-Requested-With': 'XMLHttpRequest' },
                body: formData
            });
            if(res.ok){
                await refreshTable();
            } else {
                alert('Falha ao excluir');
            }
        } catch(e){
            alert('Erro ao excluir');
        }
    }

    document.getElementById('tarefas-table')?.addEventListener('submit', function(e){
        const form = e.target.closest('.form-delete');
        if(form){ e.preventDefault(); handleDelete(form); }
    });

    async function refreshTable(){
        const url = '{{ route('tarefas.partial.table') }}' + window.location.search;
    const res = await fetch(url, { credentials: 'same-origin', headers: { 'X-Requested-With': 'XMLHttpRequest' } });
        if(!res.ok) { console.error('Falha ao atualizar tabela'); return; }
        const html = await res.text();
        document.getElementById('tarefas-table').innerHTML = html;
    }
                console.log('[DEBUG] modal form response', { ok: res.ok, status: res.status });
});
</script>
@endpush