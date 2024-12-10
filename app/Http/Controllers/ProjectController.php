<?php

    namespace App\Http\Controllers;

    use App\Models\Project;
    use App\Models\User;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Storage;
    use App\Notifications\ProyectoEvaluadoNotification;


    class ProjectController extends Controller
    {
        public function index()
        {
            $user = auth()->user();

            if (!$user) {
                return redirect()->route('login');
            }

            if ($user->role == 'profesor') {
                $proyectos = Project::where('profesor_id', $user->id)->paginate(20);
            } else {
                $proyectos = Project::where('estudiante_id', $user->id)->paginate(20);
            }

            return view('dashboard', compact('proyectos'));
        }

        public function show($id)
        {
            $proyecto = Project::findOrFail($id);

            if (auth()->user()->role == 'estudiante' && $proyecto->estudiante_id != auth()->id()) {
                return redirect()->route('projects.index')->with('error', 'No tienes acceso a este proyecto.');
            }

            if (auth()->user()->role == 'profesor' && $proyecto->profesor_id != auth()->id()) {
                return redirect()->route('projects.index')->with('error', 'No tienes acceso a este proyecto.');
            }

            return view('projects.show', compact('proyecto'));
        }

        public function showProfesor($id)
        {
            $proyecto = Project::with(['files', 'estudiante', 'profesor', 'comments.usuario'])->findOrFail($id);

            if (auth()->user()->role == 'profesor' && $proyecto->profesor_id != auth()->id()) {
                abort(403, 'No tienes acceso a este proyecto.');
            }

            return view('projects.showprofesor', compact('proyecto'));
        }


        public function create()
        {
            $user = auth()->user();

            if ($user->role != 'estudiante') {
                return redirect()->route('projects.index');
            }

            $profesores = User::where('role', 'profesor')->get();

            return view('projects.create', compact('profesores'));
        }

        public function store(Request $request)
        {
            $user = auth()->user();

            if ($user->role != 'estudiante') {
                return redirect()->route('projects.index');
            }

            $validated = $request->validate([
                'nombre' => 'required|string|max:255',
                'descripcion' => 'required|string|max:1000',
                'estado' => 'required|in:activo,completado',
                'profesor_id' => 'required|exists:users,id',
                'file' => 'required|file|mimes:pdf,jpeg,png,doc,docx|max:5120',
            ]);

            $proyecto = Project::create([
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'profesor_id' => $request->profesor_id,
                'estudiante_id' => $user->id,
                'estado' => $request->estado,
            ]);

            if ($request->hasFile('file')) {
                $path = $request->file('file')->store('files');
                $proyecto->files()->create([
                    'nombre' => $request->file('file')->getClientOriginalName(),
                    'ruta' => $path,
                ]);
            }

            return redirect()->route('dashboard')->with('success', 'Proyecto creado con éxito.');
        }


        public function edit($id)
        {
            $proyecto = Project::findOrFail($id);

            if ($proyecto->estudiante_id != auth()->id()) {
                return redirect()->route('dashboard')->with('error', 'No tienes acceso a este proyecto.');
            }

            return view('projects.edit', compact('proyecto'));
        }

        public function update(Request $request, $id)
        {
            $proyecto = Project::findOrFail($id);

            if ($proyecto->estudiante_id != auth()->id()) {
                return redirect()->route('dashboard')->with('error', 'No tienes acceso a este proyecto.');
            }

            $request->validate([
                'nombre' => 'required|string|max:255',
                'descripcion' => 'required|string|max:1000',
                'estado' => 'required|in:activo,completado',
                'file' => 'nullable|file|mimes:pdf,jpeg,png,doc,docx|max:5120',  // Validación del archivo
            ]);

            $proyecto->update([
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'estado' => $request->estado,
            ]);

            if ($request->has('delete_file') && $proyecto->files()->exists()) {
                $file = $proyecto->files()->first();
                Storage::delete($file->ruta);

                $file->delete();
            }

            if ($request->hasFile('file')) {
                $path = $request->file('file')->store('files');

                $proyecto->files()->create([
                    'nombre' => $request->file('file')->getClientOriginalName(),
                    'ruta' => $path,
                ]);
            }

            return redirect()->route('dashboard')->with('success', 'Proyecto actualizado con éxito.');
        }


        public function destroy($id)
        {
            $proyecto = Project::findOrFail($id);

            if ($proyecto->estudiante_id != auth()->id()) {
                return redirect()->route('dashboard')->with('error', 'No tienes acceso a este proyecto.');
            }

            $proyecto->delete();

            return redirect()->route('dashboard')->with('success', 'Proyecto eliminado con éxito.');
        }
    }
