<?php

namespace App\Http\Controllers;

use App\Models\Jenis_Fasilitas;
use Illuminate\Http\Request;

class MstFasilitasController  extends Controller
{

    public function index()
    {
        $data = Jenis_Fasilitas::get();
        return view('master/mstfasilitas/mstfasilitas', [
            'data'  => $data
        ]);
    }
    public function create()
    {
        return view('master/mstfasilitas/mstfasilitascreate');
    }

    public function store(Request $request)
    {
        $data = new Jenis_Fasilitas();
        $data->jenis_fasilitas = $request->jenis_fasilitas;
        $data->save();
        return redirect()->route('fasilitas')->with(['message' => 'Berhasil menambah data.']);
    }

    public function edit($id)
    {
        $data = Jenis_Fasilitas::findOrFail($id);
        return view('master/mstfasilitas/mstfasilitasedit', [
            'data' => $data,
        ]);
    }

    public function update(Request $request)
    {
        $data = Jenis_Fasilitas::find($request->id);
        $data->jenis_fasilitas = $request->jenis_fasilitas;
        $data->save();
        return redirect()->route('fasilitas')->with(['message' => 'Berhasil mengupdate data.']);
    }


    public function delete(Request $request)
    {
        $data = Jenis_Fasilitas::find($request->id);
        $data->delete();
        return redirect()->route('fasilitas')->with(['message' => 'Berhasil menghapus data.']);
    }
}
