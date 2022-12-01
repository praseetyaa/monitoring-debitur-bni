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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SolicitController extends Controller
{
    public function index()
    {
        if(Auth::user()->role_id == 4)
        {
            $data = DataDebitur::where('id_input', Auth::user()->id)->with('statusdebitur')->get();
        }
        else
        {
            $data = DataDebitur::with('statusdebitur')->get();
        }
        return view('debitur/solicit/solicit',[
            'data'  => $data,
        ]);
    }

    public function GetParentByChild(Request $request)
    {
        $parentparam    = '';
        if($request->parent == 'provinsi')
        {
            $datachild = Kota::where('nama_kota', $request->childparam)->first();
            if(!empty($datachild))
            {
                $data = Provinsi::where('id_provinsi', $datachild->id_provinsi)->first();
                $parentparam = $data->nama_provinsi;
            }
        }
        else if($request->parent == 'kecamatan')
        {
            $grandparentparam= $request->grandparentparam;
            $datachild = Desa::with(['kecamatan.kota'])->whereHas('kecamatan.kota', function($q) use($grandparentparam) {$q->where('nama_kota', '=', $grandparentparam);})->where('nama_desa', $request->childparam)->first();
            if(!empty($datachild))
            {
                $data = Kecamatan::where('id_kecamatan', $datachild->id_kecamatan)->first();
                $parentparam = $data->nama_kecamatan;
            }
        }
        else if($request->parent == 'desa')
        {
            $grandparentparam= $request->grandparentparam;
            $datachild = Kodepos::with(['desa.kecamatan.kota'])->whereHas('desa.kecamatan.kota', function($q) use($grandparentparam) {$q->where('nama_kota', '=', $grandparentparam);})->where('kodepos', $request->childparam)->first();
            if(!empty($datachild))
            {
                $data = Desa::where('id_desa', $datachild->id_desa)->first();
                $parentparam = $data->nama_desa;
            }
        }
        else if($request->parent == 'kodepos')
        {
            $grandparentparam= $request->grandparentparam;
            $datachild = Desa::with(['kecamatan.kota', 'kodepos'])->whereHas('kecamatan.kota', function($q) use($grandparentparam) {$q->where('nama_kota', '=', $grandparentparam);})->where('nama_desa', $request->childparam)->first();
            if(!empty($datachild))
            {
                $data = Kodepos::where('id_desa', $datachild->id_desa)->first();
                $parentparam = $data->kodepos;
            }
        }
        return response()->json(array(
            'parent'            => $request->parent,
            'parentparam'       => $parentparam
        ));
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

    public function openfile($path, $name)
    {
        return response()->file(Storage::path("$path/$name"));
    }

    public function store(Request $request)
    {
        $nama   = "Document_Location_".rand().".".$request->file('foto_lokasi')->extension();;
        Storage::putFileAs('Dokumen Lokasi', $request->file('foto_lokasi'), $nama);
        $user = User::with('attribute')->where('id', Auth::user()->id)->first();
        $data = new DataDebitur();
        $data->nama_debitur                 = $request->nama_debitur;
        $data->latitude                     = $request->latitude;
        $data->longitude                    = $request->longitude;
        $data->provinsi                     = $request->provinsi;
        $data->id_provinsi                  = 0;
        $data->kota                         = $request->kota;
        $data->id_kota                      = 0;
        $data->kecamatan                    = $request->kecamatan;
        $data->id_kecamatan                 = 0;
        $data->desa                         = $request->desa;
        $data->id_desa                      = 0;
        $data->kodepos                      = $request->kodepos;
        $data->id_kodepos                   = 0;
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
        $data->dokumen_lokasi               = 'Dokumen Lokasi/'.$nama;

        $data->save();

        return redirect()->route('solicit')->with(['message' => 'Berhasil menambah data.']);
    }

    public function detail($id)
    {
        $data       = DataDebitur::with('statusdebitur')->findOrFail($id);
        return view('debitur/solicit/solicitdetail', [
            'data'      => $data
        ]);

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
        $user              = User::with('attribute')->where('id', Auth::user()->id)->first();
        $data              = DataDebitur::find($request->id);

        if ($request->hasFile('foto_lokasi')) {
            $datadebitur       = DataDebitur::where('id', $request->id)->first();
            if (Storage::exists($datadebitur->dokumen_lokasi)) {
                Storage::delete($datadebitur->dokumen_lokasi);
            }
            $nama   = "Document_Location_".rand().".".$request->file('foto_lokasi')->extension();;
            Storage::putFileAs('Dokumen Lokasi', $request->file('foto_lokasi'), $nama);
            $data->dokumen_lokasi               = 'Dokumen Lokasi/'.$nama;
        }

        $data->nama_debitur                 = $request->nama_debitur;
        $data->latitude                     = $request->latitude;
        $data->longitude                    = $request->longitude;
        $data->provinsi                     = $request->provinsi;
        $data->kota                         = $request->kota;
        $data->kecamatan                    = $request->kecamatan;
        $data->desa                         = $request->desa;
        $data->kodepos                      = $request->kodepos;
        $data->detail_alamat                = $request->detail_alamat;
        $data->sektor                       = $request->sektor;
        $data->bidang_usaha                 = $request->bidang_usaha;
        $data->kategori                     = $request->kategori;
        $data->orientasiekspor              = $request->orientasiekspor;
        $data->indikasi_kebutuhan_produk    = $request->indikasi_kebutuhan_produk;
        $data->sumber                       = $request->sumber;
        $data->dataleads                    = $request->dataleads;
        $data->id_update                    = $user->id;
        $data->nama_update                  = $user->name;
        $data->npp_update                   = $user->attribute->npp;
        $data->tanggal_update               = date('Y-m-d H:i:s');

        $data->save();
        return redirect()->route('solicit')->with(['message' => 'Berhasil mengupdate data.']);
    }

    public function verifisolicit(Request $request)
    {
        $user                   = User::with('attribute')->where('id', Auth::user()->id)->first();
        $data                   = DataDebitur::find($request->id);
        $data->pre_screen       = @$request->pre_screen == null ? 0 : 1;
        $data->ots_penyelia     = @$request->ots_penyelia == null ? 0 : 1;
        $data->ots_pemimpin     = @$request->ots_pemimpin == null ? 0 : 1;
        $data->id_verif         = $user->id;
        $data->nama_verif       = $user->name;
        $data->npp_verif        = $user->attribute->npp;
        $data->status_debitur   = 2;
        $data->tanggal_verif    = date('Y-m-d H:i:s');
        $data->save();
        return redirect()->route('solicitdetail', ['id'=>$request->id])->with(['message' => 'Berhasil Memverifikasi Data']);
    }

    public function appsolicit(Request $request)
    {
        $user                     = User::with('attribute')->where('id', Auth::user()->id)->first();
        $data                     = DataDebitur::find($request->id);
        $data->id_approve         = $user->id;
        $data->nama_approve       = $user->name;
        $data->npp_approve        = $user->attribute->npp;
        $data->status_debitur     = 3;
        $data->tanggal_approve    = date('Y-m-d H:i:s');
        $data->save();
        return redirect()->route('solicitdetail', ['id'=>$request->id])->with(['message' => 'Berhasil Menyetujui Data']);
    }





    public function delete(Request $request)
    {
        $data = DataDebitur::find($request->id);
        $data->delete();
        return redirect()->route('solicit')->with(['message' => 'Berhasil menghapus data.']);
    }
}
