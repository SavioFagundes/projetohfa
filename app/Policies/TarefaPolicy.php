<?php

namespace App\Policies;

use App\Models\Tarefa;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TarefaPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Tarefa $tarefa)
    {
    // Allow any authenticated user to view tarefas. Adjust if you need ownership restrictions.
    return true;
    }

    public function update(User $user, Tarefa $tarefa)
    {
        return $tarefa->user_id === $user->id;
    }

    public function delete(User $user, Tarefa $tarefa)
    {
        return $tarefa->user_id === $user->id;
    }

    public function create(User $user)
    {
        return true;
    }
}
