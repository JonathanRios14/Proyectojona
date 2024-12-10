<?php
namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Project;
use App\Notifications\ProyectoEvaluadoNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class CommentController extends Controller
{
    // Mostrar los comentarios de un proyecto
    public function index($projectId)
    {
        $project = Project::findOrFail($projectId);
        return view('projects.showprofesor', compact('project'));
    }

    public function store(Request $request, $projectId)
    {
        // Validación del contenido del comentario
        $request->validate([
            'contenido' => 'required|string|max:1000',
        ]);

        // Encuentra el proyecto
        $project = Project::findOrFail($projectId);

        // Verifica si el proyecto está en estado "activo"
        if ($project->estado === 'activo') {
            // Mensaje de error cuando el proyecto está activo
            return redirect()->back()->with('error', 'No puedes agregar un comentario mientras el proyecto esté en estado "Activo".');
        }

        // Verifica si ya existe un comentario para el proyecto
        $existingComment = Comment::where('project_id', $projectId)->first(); // Si ya existe un comentario, no permitirá agregar otro

        if ($existingComment) {
            // Mensaje de error si ya existe un comentario
            return redirect()->back()->with('error', 'Ya has calificado este proyecto. No puedes agregar otro comentario.');
        }

        // Crea el comentario
        $comentario = Comment::create([
            'contenido' => $request->contenido,
            'usuario_id' => auth()->id(),
            'project_id' => $project->id,
        ]);

        // Enviar la notificación al estudiante siempre que se cree un comentario
        Notification::send($project->estudiante, new ProyectoEvaluadoNotification($project));

        // Mensaje de éxito después de agregar el comentario
        return redirect()->route('projects.showprofesor', $projectId)->with('success', 'Comentario agregado exitosamente.');
    }

    // Editar un comentario
    public function edit($commentId)
    {
        $comment = Comment::findOrFail($commentId);
        // Verifica que el usuario sea el profesor que dejó el comentario
        if ($comment->usuario_id != auth()->id()) {
            return redirect()->back()->with('error', 'No tienes permiso para editar este comentario.');
        }

        return view('comments.edit', compact('comment'));
    }

    // Actualizar un comentario
    public function update(Request $request, $commentId)
    {
        $comment = Comment::findOrFail($commentId);
        $comment->update([
            'contenido' => $request->contenido,
        ]);

        return redirect()->route('projects.showprofesor', $comment->project_id)->with('success', 'Comentario actualizado exitosamente.');
    }

    // Eliminar un comentario
    public function destroy($commentId)
    {
        $comment = Comment::findOrFail($commentId);
        // Verifica que el usuario sea el profesor que dejó el comentario
        if ($comment->usuario_id != auth()->id()) {
            return redirect()->back()->with('error', 'No tienes permiso para eliminar este comentario.');
        }

        $comment->delete();

        return redirect()->route('projects.showprofesor', $comment->project_id)->with('success', 'Comentario eliminado exitosamente.');
    }
}

