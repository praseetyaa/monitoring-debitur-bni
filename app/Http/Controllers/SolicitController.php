<?php

namespace App\Http\Controllers;

use App\Models\DataDebitur;
use App\Models\Sektor;
use App\Models\Sumber;
use App\Models\User;
use App\Models\Wilayah\Desa;
use App\Models\Wilayah\Kecamatan;
use App\Models\Wilayah\Kodepos;
use App\Models\Wilayah\Kota;
use App\Models\Wilayah\Provinsi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SolicitController extends Controller
{
    public function index()
    {
        $data = DataDebitur::get();
        return view('debitur/solicit/solicit',[
            'data'  => $data,
        ]);
    }

    public function getchilddata(Request $request)
    {
        $data = '';
        if($request->parent == 'provinsi')
        {
            $data = Kota::where('id_provinsi', $request->idparent)->get();
        }
        else if($request->parent == 'kota')
        {
            $data = Kecamatan::where('id_kota', $request->idparent)->get();
        }
        else if($request->parent == 'kecamatan')
        {
            $data = Desa::where('id_kecamatan', $request->idparent)->get();
        }
        else if($request->parent == 'desa')
        {
            $data = Kodepos::where('id_desa', $request->idparent)->get();
        }
        return response()->json(array(
            'idparent'          => $request->idparent,
            'parent'            => $request->parent,
            'data'              => $data
        ));
    }

    public function create()
    {
        $Provinsi = Provinsi::get();
        $sumber = Sumber::get();
        $sektor = Sektor::get();
        return view('debitur/solicit/solicitcreate', [
            'sumber'  => $sumber,
            'sektor'  => $sektor,
            'Provinsi'  => $Provinsi,
        ]);
    }


    public function store(Request $request)
    {
        $user = User::with('attribute')->where('id', Auth::user()->id)->first();
        $data = new DataDebitur();
        $data->nama_debitur                 = $request->nama_debitur;
        $data->provinsi                     = explode('_', $request->provinsi)[1];
        $data->id_provinsi                  = explode('_', $request->provinsi)[0];
        $data->kota                         = explode('_', $request->kota)[1];
        $data->id_kota                      = explode('_', $request->kota)[0];
        $data->kecamatan                    = explode('_', $request->kecamatan)[1];
        $data->id_kecamatan                 = explode('_', $request->kecamatan)[0];
        $data->desa                         = explode('_', $request->desa)[1];
        $data->id_desa                      = explode('_', $request->desa)[0];
        $data->kodepos                      = explode('_', $request->kodepos)[1];
        $data->id_kodepos                   = explode('_', $request->kodepos)[0];
        $data->detail_alamat                = $request->detail_alamat;
        $data->sektor                       = $request->sektor;
        $data->bidang_usaha                 = $request->bidang_usaha;
        $data->kategori                     = $request->kategori;
        $data->orientasiekspor              = $request->orientasiekspor;
        $data->indikasi_kebutuhan_produk    = $request->indikasi_kebutuhan_produk;
        $data->sumber                       = $request->sumber;
        $data->dataleads                    = $request->dataleads;
        $data->id_input                     = $user->id;
        $data->nama_input                   = $user->name;
        $data->npp_input                    = $user->attribute->npp;

        $data->save();

        return redirect()->route('solicit')->with(['message' => 'Berhasil menambah data.']);
    }

    public function edit($id)
    {
        $data       = DataDebitur::findOrFail($id);
        $Provinsi   = Provinsi::get();
        $sumber     = Sumber::get();
        $sektor     = Sektor::get();
        return view('debitur/solicit/solicitedit', [
            'sumber'  => $sumber,
            'sektor'  => $sektor,
            'Provinsi'  => $Provinsi,
            'data'      => $data
        ]);
    }

    public function update(Request $request)
    {

        $user = User::with('attribute')->where('id', Auth::user()->id)->first();
        $data                               = DataDebitur::find($request->id);
        $data->nama_debitur                 = $request->nama_debitur;
        $data->provinsi                     = explode('_', $request->provinsi)[1];
        $data->id_provinsi                  = explode('_', $request->provinsi)[0];
        $data->kota                         = explode('_', $request->kota)[1];
        $data->id_kota                      = explode('_', $request->kota)[0];
        $data->kecamatan                    = explode('_', $request->kecamatan)[1];
        $data->id_kecamatan                 = explode('_', $request->kecamatan)[0];
        $data->desa                         = explode('_', $request->desa)[1];
        $data->id_desa                      = explode('_', $request->desa)[0];
        $data->kodepos                      = explode('_', $request->kodepos)[1];
        $data->id_kodepos                   = explode('_', $request->kodepos)[0];
        $data->detail_alamat                = $request->detail_alamat;
        $data->sektor                       = $request->sektor;
        $data->bidang_usaha                 = $request->bidang_usaha;
        $data->kategori                     = $request->kategori;
        $data->orientasiekspor              = $request->orientasiekspor;
        $data->indikasi_kebutuhan_produk    = $request->indikasi_kebutuhan_produk;
        $data->sumber                       = $request->sumber;
        $data->dataleads                    = $request->dataleads;
        $data->id_update                     = $user->id;
        $data->nama_update                   = $user->name;
        $data->npp_update                    = $user->attribute->npp;
        $data->save();
        return redirect()->route('solicit')->with(['message' => 'Berhasil mengupdate data.']);
    }


    public function delete(Request $request)
    {
        $data = DataDebitur::find($request->id);
        $data->delete();
        return redirect()->route('solicit')->with(['message' => 'Berhasil menghapus data.']);
    }
}
