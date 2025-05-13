<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peserta;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PesertaImport;

class PesertaController extends Controller
{

    public function __construct()
    {
        // Hanya user yang sudah login yang bisa akses
        $this->middleware('auth');
    }

    public function index()
    {
        $pesertas = Peserta::all();
        return view('peserta.index', compact('pesertas'));
    }

    public function getData(Request $request)
{
    $peserta = Peserta::select(['id', 'name', 'email', 'address', 'no_hp']);

    return DataTables::of($peserta)
        ->addColumn('aksi', function ($row) {
            $editBtn = '<button class="btn btn-kuning btn-sm btn-edit" data-id="' . $row->id . '"><i class="fas fa-edit"></i></button>';
            $deleteForm = '
                <form action="' . route('peserta.destroy', $row->id) . '" method="POST" class="d-inline">
                    ' . csrf_field() . method_field('DELETE') . '
                    <button class="btn btn-kuning btn-sm"><i class="fas fa-trash"></i></button>
                </form>';
            return $editBtn . ' ' . $deleteForm;
        })
        ->rawColumns(['aksi']) // penting supaya HTML-nya tidak di-escape
        ->make(true);
}


    public function create()
    {
        return view('peserta.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:pesertas',
        ]);

        Peserta::create($request->all());

        return redirect()->route('peserta.index')->with('success', 'Peserta berhasil ditambahkan.');
    }


    public function edit($id)
    {

        $peserta = Peserta::find($id);

        if (!$peserta) {
            return response()->json(['message' => 'Peserta tidak ditemukan'], 404);
        }
    
        return response()->json([
            'name' => $peserta->name,
            'email' => $peserta->email,
            'address' => $peserta->address,
            'no_hp' => $peserta->no_hp,
        ]);
    }

    public function update(Request $request, $id)
    {
        // Mencari peserta berdasarkan ID
        $peserta = Peserta::findOrFail($id);
    
        // Validasi data yang dikirimkan dari frontend
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:pesertas,email,' . $id, // Validasi email, kecuali untuk email peserta yang sama
            'address' => 'nullable|string',
            'no_hp' => 'nullable|string|max:20',
        ]);
    
        // Update data peserta berdasarkan inputan form yang diterima
        $peserta->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'address' => $request->input('address'),
            'no_hp' => $request->input('no_hp'),
        ]);
    
        // Mengembalikan response sukses
        return redirect()->route('peserta.index')->with('success', 'Peserta berhasil diupdate.');
    }

    public function destroy($id)
    {
        $peserta = Peserta::findOrFail($id);
        $peserta->delete();

        return redirect()->route('peserta.index')->with('success', 'Peserta berhasil dihapus.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv'
        ]);

        try {
            // Mengimpor data menggunakan PesertaImport
            Excel::import(new PesertaImport, $request->file('file'));

            return back()->with('success', 'Data berhasil diimpor!');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();

            // Mengirimkan kesalahan validasi ke UI
            return back()->withErrors($failures);
        }
}
}
