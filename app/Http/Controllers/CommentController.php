<?php

namespace App\Http\Controllers;

use App\Models\Tarefa;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Tarefa $tarefa)
    {
        $data = $request->validate([
            'body' => 'required|string|max:2000',
        ]);

        $comment = new Comment();
        $comment->tarefa_id = $tarefa->id;
        $comment->user_id = $request->user()?->id;
        $comment->body = $data['body'];
        $comment->save();

        // reload comments relation for rendering
        $tarefa->load(['comments.user']);

        // If request is AJAX, return the rendered comments partial so the client
        // can replace the comments section without a full page reload.
        if($request->ajax() || $request->wantsJson()){
            $html = view('tarefas.partials.comments', compact('tarefa'))->render();
            return response()->json(['success' => true, 'html' => $html], 201);
        }

        return back()->with('status', 'Comentário adicionado.');
    }

    public function destroy(Request $request, Tarefa $tarefa, Comment $comment)
    {
        // optional: check ownership or permissions
        if($request->user()?->id !== $comment->user_id){
            abort(403);
        }
        $comment->delete();
        return back()->with('status', 'Comentário removido.');
    }
}
