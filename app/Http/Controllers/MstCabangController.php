<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use Illuminate\Http\Request;

class MstCabangController extends Controller
{

    public function index()
    {
        $data = Cabang::get();
        return view('master/mstcabang/mstcabang', [
            'data'  => $data
        ]);
    }
    public function create()
    {
        return view('master/mstcabang/mstcabangcreate');
    }

    public function store(Request $request)
    {
        $data = new Cabang();
        $data->nama = $request->nama;
        $data->save();
        return redirect()->route('cabang')->with(['message' => 'Berhasil menambah data.']);
    }

    public function edit($id)
    {
        $data = Cabang::findOrFail($id);
        return view('master/mstcabang/mstcabangedit', [
            'data' => $data,
        ]);
    }

    public function update(Request $request)
    {
        $data = Cabang::find($request->id);
        $data->nama = $request->nama;
        $data->save();
        return redirect()->route('cabang')->with(['message' => 'Berhasil mengupdate data.']);
    }


    public function delete(Request $request)
    {
        $data = Cabang::find($request->id);
        $data->delete();
        return redirect()->route('cabang')->with(['message' => 'Berhasil menghapus data.']);
    }
}
