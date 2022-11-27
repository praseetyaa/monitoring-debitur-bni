<?php

namespace App\Http\Controllers;

use App\Models\Sektor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MstSektorController extends Controller
{

    public function index()
    {
        $sektor = Sektor::get();
        return view('master/mstsektor/mstsektor', [
            'data'  => $sektor
        ]);
    }
    public function create()
    {
        return view('master/mstsektor/mstsektorcreate');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_sektor' => 'required',
        ]);

        if($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        else {
            $data = new Sektor();
            $data->nama_sektor = $request->nama_sektor;
            $data->save();
            return redirect()->route('sektor')->with(['message' => 'Berhasil menambah data.']);
        }
    }

    public function edit($id)
    {
        $data = Sektor::findOrFail($id);

        return view('master/mstsektor/mstsektoredit', [
            'data' => $data,
        ]);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_sektor' => 'required',
        ]);
        if($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        else {

            $data = Sektor::find($request->id);
            $data->nama_sektor = $request->nama_sektor;
            $data->save();
            return redirect()->route('sektor')->with(['message' => 'Berhasil mengupdate data.']);
        }
    }


    public function delete(Request $request)
    {
        $data = Sektor::find($request->id);
        $data->delete();
        return redirect()->route('sektor')->with(['message' => 'Berhasil menghapus data.']);
    }
}
