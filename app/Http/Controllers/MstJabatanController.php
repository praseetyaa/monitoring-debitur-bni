<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use Illuminate\Http\Request;

class MstJabatanController extends Controller
{

    public function index()
    {
        $data = Jabatan::get();
        return view('master/mstjabatan/mstjabatan', [
            'data'  => $data
        ]);
    }
    public function create()
    {
        return view('master/mstjabatan/mstjabatancreate');
    }

    public function store(Request $request)
    {
        $data = new Jabatan();
        $data->nama = $request->nama;
        $data->save();
        return redirect()->route('jabatan')->with(['message' => 'Berhasil menambah data.']);
    }

    public function edit($id)
    {
        $data = Jabatan::findOrFail($id);
        return view('master/mstjabatan/mstjabatanedit', [
            'data' => $data,
        ]);
    }

    public function update(Request $request)
    {
        $data = Jabatan::find($request->id);
        $data->nama = $request->nama;
        $data->save();
        return redirect()->route('jabatan')->with(['message' => 'Berhasil mengupdate data.']);
    }


    public function delete(Request $request)
    {
        $data = Jabatan::find($request->id);
        $data->delete();
        return redirect()->route('jabatan')->with(['message' => 'Berhasil menghapus data.']);
    }
}
