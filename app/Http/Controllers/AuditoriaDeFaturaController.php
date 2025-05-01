<?php

namespace App\Http\Controllers;

use App\Exports\GruposExport;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Imports\AuditoriaImport;
use Maatwebsite\Excel\Facades\Excel;
class AuditoriaDeFaturaController extends Controller
{
    public function index()
    {
        return Inertia::render('AuditoriaDeFatura');
    }

    public function importar(Request $request)
    {

        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
            'minutos' => 'required|integer|min:1',
        ]);

        $import = new AuditoriaImport($request->minutos);
        Excel::import($import, $request->file('file'));

        return response()->json([
            'success' => true,
            'grupos' => $import->grupos
        ]);
    }

    public function download(Request $request)
    {
        $grupos = json_decode($request->query('grupos'), true);

        return Excel::download(new GruposExport($grupos), 'auditoria_exportada.xlsx');
    }
}
