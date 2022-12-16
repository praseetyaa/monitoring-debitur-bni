<?php

namespace App\Http\Controllers;

use App\Models\Pengumuman;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class PengumumanController extends Controller
{
    public function index($status = '')
    {
        $data = Pengumuman::
                // when(Auth::user()->role_id != 1 || Auth::user()->role_id != 5, function($query){
                //     $query->where('id_pembuat', Auth::user()->id);
                // })
                when($status !== '' && $status !== 'null' && $status !== 'Act', function($query){
                    $query->whereDate('expired', '<' ,date('Y-m-d'));
                })
                ->when($status !== '' && $status !== 'null' && $status !== 'Exp', function($query){
                    $query->whereDate('expired', '>=' ,date('Y-m-d'));
                })
                ->get();
        return view('master/pengumuman/pengumuman',[
            'data'  => $data,
            'status' => $status
        ]);
    }

    public function create()
    {
        return view('master/pengumuman/pengumumancreate');
    }

    public function store(Request $request)
    {
        if ($request->hasFile('thumbnail'))
        {
            $nama   = "Document_Thumbnail_".rand().".".$request->file('thumbnail')->extension();
            Storage::putFileAs('Thumbnail Pengumuman', $request->file('thumbnail'), $nama);
        }

        $user = User::with('attribute')->where('id', Auth::user()->id)->first();
        $data = new Pengumuman();
        $data->judul                = $request->judul;
        $data->expired              = $request->expired;
        $data->isi                  = $request->isi;
        $data->id_pembuat           = $user->id;
        $data->nama_pembuat         = $user->name;
        $data->npp_pembuat          = $user->attribute->npp;
        $data->tanggal_pebuatan     = date('Y-m-d H:i:s');
        if ($request->hasFile('thumbnail'))
        {
            $data->thumbnail       = 'Thumbnail Pengumuman/'.$nama;
        }

        $data->save();

        return redirect()->route('pengumuman')->with(['message' => 'Berhasil menambah data.']);
    }

    public function pengumumanuploadimg(Request $request){
        $fileName=$request->file('file')->getClientOriginalName();
        $path=$request->file('file')->storeAs('Dokumen Pengumuman', rand().'_'.$fileName, 'public');
        return response()->json(['location' => URL::asset("/storage/$path")]);
    }


    public function edit($id)
    {
        $data       = Pengumuman::findOrFail($id);
        if(!empty($data))
        {
            $data->isi = str_replace('src="../storage', 'src="'.URL::asset('storage/'), $data->isi);
        }
        return view('master/pengumuman/pengumumanedit', [
            'data'      => $data
        ]);
    }

    public function update(Request $request)
    {
        $user              = User::with('attribute')->where('id', Auth::user()->id)->first();
        $data              = Pengumuman::find($request->id);

        if ($request->hasFile('thumbnail')) {
            $Pengumuman       = Pengumuman::where('id', $request->id)->first();
            if (Storage::exists($Pengumuman->thumbnail)) {
                Storage::delete($Pengumuman->thumbnail);
            }
            $nama   = "Document_Thumbnail_".rand().".".$request->file('thumbnail')->extension();;
            Storage::putFileAs('Thumbnail Pengumuman', $request->file('thumbnail'), $nama);
            $data->thumbnail               = 'Thumbnail Pengumuman/'.$nama;
        }

        $data->judul                = $request->judul;
        $data->expired              = $request->expired;
        $data->isi                  = $request->isi;
        $data->id_update            = $user->id;
        $data->nama_update          = $user->name;
        $data->npp_update           = $user->attribute->npp;
        $data->tanggal_update       = date('Y-m-d H:i:s');

        $data->save();
        return redirect()->route('pengumuman')->with(['message' => 'Berhasil mengupdate data.']);
    }

    public function delete(Request $request)
    {
        $data = Pengumuman::find($request->id);
        $data->delete();
        return redirect()->route('pengumuman')->with(['message' => 'Berhasil menghapus data.']);
    }

}
