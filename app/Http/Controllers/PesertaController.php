<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peserta;

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
}
