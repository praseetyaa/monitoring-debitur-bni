<?php

namespace App\Http\Controllers;

use App\Models\Sumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MstSumberController extends Controller
{

    public function index()
    {
        $sumber = Sumber::get();
        return view('master/mstsumber/mstsumber', [
            'data'  => $sumber
        ]);
    }
    public function create()
    {
        return view('master/mstsumber/mstsumbercreate');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_sumber' => 'required',
        ]);

        if($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        else {
            $data = new Sumber();
            $data->nama_sumber = $request->nama_sumber;
            $data->save();
            return redirect()->route('sumber')->with(['message' => 'Berhasil menambah data.']);
        }
    }

    public function edit($id)
    {
        $data = Sumber::findOrFail($id);

        return view('master/mstsumber/mstsumberedit', [
            'data' => $data,
        ]);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_sumber' => 'required',
        ]);
        if($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        else {

            $data = Sumber::find($request->id);
            $data->nama_sumber = $request->nama_sumber;
            $data->save();
            return redirect()->route('sumber')->with(['message' => 'Berhasil mengupdate data.']);
        }
    }


    public function delete(Request $request)
    {
        $data = Sumber::find($request->id);
        $data->delete();
        return redirect()->route('sumber')->with(['message' => 'Berhasil menghapus data.']);
    }
}
