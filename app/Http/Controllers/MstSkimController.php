<?php

namespace App\Http\Controllers;

use App\Models\SKIM;
use Illuminate\Http\Request;

class MstSkimController extends Controller
{

    public function index()
    {
        $data = SKIM::get();
        return view('master/mstskim/mstskim', [
            'data'  => $data
        ]);
    }
    public function create()
    {
        return view('master/mstskim/mstskimcreate');
    }

    public function store(Request $request)
    {
        $data = new SKIM();
        $data->skim = $request->skim;
        $data->save();
        return redirect()->route('skim')->with(['message' => 'Berhasil menambah data.']);
    }

    public function edit($id)
    {
        $data = SKIM::findOrFail($id);
        return view('master/mstskim/mstskimedit', [
            'data' => $data,
        ]);
    }

    public function update(Request $request)
    {
        $data = SKIM::find($request->id);
        $data->skim = $request->skim;
        $data->save();
        return redirect()->route('skim')->with(['message' => 'Berhasil mengupdate data.']);
    }


    public function delete(Request $request)
    {
        $data = SKIM::find($request->id);
        $data->delete();
        return redirect()->route('skim')->with(['message' => 'Berhasil menghapus data.']);
    }
}
