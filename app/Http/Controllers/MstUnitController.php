<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;

class MstUnitController extends Controller
{

    public function index()
    {
        $data = Unit::get();
        return view('master/mstunit/mstunit', [
            'data'  => $data
        ]);
    }
    public function create()
    {
        return view('master/mstunit/mstunitcreate');
    }

    public function store(Request $request)
    {
        $data = new Unit();
        $data->nama = $request->nama;
        $data->save();
        return redirect()->route('unit')->with(['message' => 'Berhasil menambah data.']);
    }

    public function edit($id)
    {
        $data = Unit::findOrFail($id);
        return view('master/mstunit/mstunitedit', [
            'data' => $data,
        ]);
    }

    public function update(Request $request)
    {
        $data = Unit::find($request->id);
        $data->nama = $request->nama;
        $data->save();
        return redirect()->route('unit')->with(['message' => 'Berhasil mengupdate data.']);
    }


    public function delete(Request $request)
    {
        $data = Unit::find($request->id);
        $data->delete();
        return redirect()->route('unit')->with(['message' => 'Berhasil menghapus data.']);
    }
}
