<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use Illuminate\Routing\Controller as BaseController;

class FileController extends BaseController
{
    public function store(Request $request, $projectId)
    {
        $request->validate([
            'file' => 'required|file|max:10240',
        ]);

        $file = $request->file('file');
        $path = $file->store('public/files');

        $newFile = new File();
        $newFile->nombre = $file->getClientOriginalName();
        $newFile->ruta = $path;
        $newFile->project_id = $projectId;
        $newFile->save();

        return back()->with('success', 'Archivo subido exitosamente.');
    }

    public function destroy($fileId)
    {
        $file = File::findOrFail($fileId);
        Storage::delete($file->ruta);
        $file->delete();

        return back()->with('success', 'Archivo eliminado exitosamente.');
    }


}
