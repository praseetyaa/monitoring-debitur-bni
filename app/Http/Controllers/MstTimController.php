<?php

namespace App\Http\Controllers;

use App\Models\Tim;
use Illuminate\Http\Request;

class MstTimController extends Controller
{

    public function index()
    {
        $data = Tim::get();
        return view('master/msttim/msttim', [
            'data'  => $data
        ]);
    }
    public function create()
    {
        return view('master/msttim/msttimcreate');
    }

    public function store(Request $request)
    {
        $data = new Tim();
        $data->nama = $request->nama;
        $data->save();
        return redirect()->route('tim')->with(['message' => 'Berhasil menambah data.']);
    }

    public function edit($id)
    {
        $data = Tim::findOrFail($id);
        return view('master/msttim/msttimedit', [
            'data' => $data,
        ]);
    }

    public function update(Request $request)
    {
        $data = Tim::find($request->id);
        $data->nama = $request->nama;
        $data->save();
        return redirect()->route('tim')->with(['message' => 'Berhasil mengupdate data.']);
    }


    public function delete(Request $request)
    {
        $data = Tim::find($request->id);
        $data->delete();
        return redirect()->route('tim')->with(['message' => 'Berhasil menghapus data.']);
    }
}
