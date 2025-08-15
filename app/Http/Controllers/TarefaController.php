<?php

namespace App\Http\Controllers;

use App\Models\Tarefa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDO;
use App\Http\Requests\StoreTarefaRequest;
use App\Http\Requests\UpdateTarefaRequest;

use Illuminate\Http\JsonResponse;

class TarefaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Exibe lista paginada de tarefas.
     */
    public function index(Request $request)
    {
        // Query builder to apply filters/ordering
    $query = Tarefa::query()->forUser($request->user());

        // Search by title
        if ($q = $request->query('q')) {
            $query->where('titulo', 'like', '%' . $q . '%');
        }

        // Filter by status
        if ($status = $request->query('status')) {
            // only apply if not empty string
            $query->where('status', $status);
        }

        // Filter by data_limite range
        if ($from = $request->query('from')) {
            $query->whereDate('data_limite', '>=', $from);
        }
        if ($to = $request->query('to')) {
            $query->whereDate('data_limite', '<=', $to);
        }

        // Ordering
        $allowed = ['created_at', 'data_limite', 'titulo'];
        $sort = $request->query('sort', 'created_at');
        $direction = $request->query('direction', 'desc');
        if (! in_array($sort, $allowed)) {
            $sort = 'created_at';
        }
        $direction = strtolower($direction) === 'asc' ? 'asc' : 'desc';

        $query->orderBy($sort, $direction);

        $tarefas = $query->paginate(10)->appends($request->query());

    $statuses = Tarefa::statuses();

        return view('tarefas.index', compact('tarefas', 'statuses'));
    }

    /**
     * Exibe formulário de criação.
     */
    public function create()
    {
    $statuses = Tarefa::statuses();
    $prioridades = Tarefa::prioridades();
    return view('tarefas.create', compact('statuses', 'prioridades'));
    }

    /**
     * Armazena nova tarefa.
     */
    public function store(StoreTarefaRequest $request)
    {
        $data = $request->validated();
        // Garantir valor default para status caso não venha
        $data['status'] = $data['status'] ?? Tarefa::STATUS_PENDENTE;
    // Garantir prioridade default
    $data['prioridade'] = $data['prioridade'] ?? Tarefa::PRIORIDADE_MEDIA;

    $data['user_id'] = $request->user()->id;
    Tarefa::create($data);

        return redirect()->route('tarefas.index')
                         ->with('success', 'Tarefa criada com sucesso.');
    }

    /**
     * Gera uma tarefa automaticamente (uso via botão)
     */
    public function generate(Request $request)
    {
        $data = $request->validate([
            'count' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        $count = $data['count'] ?? 1;

        $created = 0;

        DB::beginTransaction();
        try {
            // basear no maior id atual para compor títulos
            $max = Tarefa::max('id') ?? 0;
            for ($i = 1; $i <= $count; $i++) {
                $next = $max + $i;
                Tarefa::create([
                    'titulo' => "Tarefa {$next}",
                    'descricao' => null,
                    'status' => Tarefa::STATUS_PENDENTE,
                    'data_limite' => null,
                ]);
                $created++;
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('tarefas.index')
                             ->with('error', 'Falha ao gerar tarefas: ' . $e->getMessage());
        }

        return redirect()->route('tarefas.index')
                         ->with('success', "{$created} tarefa(s) criadas com sucesso.");
    }

    /**
     * Exibe uma tarefa.
     */
    public function show(Tarefa $tarefa)
    {
        return view('tarefas.show', compact('tarefa'));
    }

    /**
     * Exibe formulário de edição.
     */
    public function edit(Tarefa $tarefa)
    {
    $statuses = Tarefa::statuses();
    $prioridades = Tarefa::prioridades();
    return view('tarefas.edit', compact('tarefa', 'statuses', 'prioridades'));
    }

    /**
     * Atualiza a tarefa.
     */
    public function update(UpdateTarefaRequest $request, Tarefa $tarefa)
    {
    $this->authorize('update', $tarefa);
        $data = $request->validated();
        // Keep existing priority if not provided
        if (! array_key_exists('prioridade', $data)) {
            $data['prioridade'] = $tarefa->prioridade ?? Tarefa::PRIORIDADE_MEDIA;
        }
        $tarefa->update($data);

        return redirect()->route('tarefas.index')
                         ->with('success', 'Tarefa atualizada com sucesso.');
    }

    /**
     * Atualiza apenas o status da tarefa via AJAX.
     */
    public function updateStatus(Request $request, Tarefa $tarefa): JsonResponse
    {
    $this->authorize('update', $tarefa);
        $data = $request->validate([
            'status' => ['required', 'string', 'in:' . implode(',', Tarefa::statuses())],
        ]);

        $tarefa->status = $data['status'];
        $tarefa->save();

        return response()->json(['success' => true, 'status' => $tarefa->status]);
    }

    /**
     * Retorna o HTML do modal de criação (partial) para requisições AJAX.
     */
    public function modalCreate(Request $request)
    {
        $statuses = Tarefa::statuses();
        $prioridades = Tarefa::prioridades();
        return view('tarefas.modals.create', compact('statuses', 'prioridades'));
    }

    /**
     * Retorna o HTML do modal de visualização para requisições AJAX.
     */
    public function modalShow(Request $request, Tarefa $tarefa)
    {
    try {
        $this->authorize('view', $tarefa);
    } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
        // Return a 403-like HTML payload so the frontend can render it inside the modal
        return response()->view('errors.partials.modal-403', ['message' => 'Você não tem permissão para visualizar esta tarefa.'], 403);
    }

    return view('tarefas.modals.show', compact('tarefa'));
    }

    /**
     * Retorna o HTML do modal de edição (partial) para requisições AJAX.
     */
    public function modalEdit(Request $request, Tarefa $tarefa)
    {
    $this->authorize('update', $tarefa);
    $statuses = Tarefa::statuses();
    $prioridades = Tarefa::prioridades();
    return view('tarefas.modals.edit', compact('tarefa', 'statuses', 'prioridades'));
    }

    /**
     * Retorna parcial da tabela para atualizar via AJAX depois de ações.
     */
    public function partialTable(Request $request)
    {
    $query = Tarefa::query()->forUser($request->user());
        $allowed = ['created_at', 'data_limite', 'titulo'];
        $sort = $request->query('sort', 'created_at');
        $direction = $request->query('direction', 'desc');
        if (! in_array($sort, $allowed)) {
            $sort = 'created_at';
        }
        $direction = strtolower($direction) === 'asc' ? 'asc' : 'desc';
        $query->orderBy($sort, $direction);
        $tarefas = $query->paginate(10)->appends($request->query());
        return view('tarefas.partials.table', compact('tarefas'));
    }

    /**
     * Remove a tarefa.
     */
    public function destroy(Tarefa $tarefa)
    {
    $this->authorize('delete', $tarefa);
        $tarefa->delete();

        // Reset auto-increment / sequence so deleted IDs are not 'stored'
        try {
            $driver = DB::getPdo()->getAttribute(PDO::ATTR_DRIVER_NAME);
            switch ($driver) {
                case 'mysql':
                    // Reset AUTO_INCREMENT to 1 (MySQL will set it to max(id)+1)
                    DB::statement('ALTER TABLE tarefas AUTO_INCREMENT = 1');
                    break;
                case 'sqlite':
                    // Remove entry from sqlite_sequence so SQLite will reset the seq
                    DB::statement("DELETE FROM sqlite_sequence WHERE name='tarefas'");
                    break;
                case 'pgsql':
                case 'postgres':
                    // Set sequence next value to max(id)+1
                    DB::statement("SELECT setval(pg_get_serial_sequence('tarefas','id'), COALESCE((SELECT MAX(id) FROM tarefas),0) + 1, false)");
                    break;
                default:
                    // Unknown driver: do nothing
                    break;
            }
        } catch (\Exception $e) {
            // If resetting fails, ignore so delete still succeeds; optionally log
            // logger()->error('Failed to reset tarefas sequence: ' . $e->getMessage());
        }

        return redirect()->route('tarefas.index')
                         ->with('success', 'Tarefa excluída com sucesso.');
    }
}