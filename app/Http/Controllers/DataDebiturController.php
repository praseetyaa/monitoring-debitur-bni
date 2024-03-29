<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Tim;
use App\Models\DataDebitur;
use App\Models\Jenis_Fasilitas;
use App\Models\Sektor;
use App\Models\SKIM;
use App\Models\StatusDebitur;
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
use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\DomPDF\PDF as DomPDFPDF;

class DataDebiturController extends Controller
{
    public function index($startd = '', $endd = '', $status_deb = '', $cabang = '', $tim = '')
    {
        $startdxx = 'null';
        $enddxx = 'null';
        if($startd !== '' && $startd != 'null')
        {
            $startdxx = date('Y-m-d', strtotime($startd));
        }
        if($endd !== '' && $endd != 'null')
        {
            $enddxx = date('Y-m-d', strtotime($endd));
        }

        $cabang   = Auth::user()->role_id == role('approval') || Auth::user()->role_id == role('verifikator') || Auth::user()->role_id == role('monitoring') ? Auth::user()->attribute->cabang_id : $cabang;
        $tim   = Auth::user()->role_id == role('approval') || Auth::user()->role_id == role('verifikator') || Auth::user()->role_id == role('monitoring') ? Auth::user()->attribute->tim_id : $tim;
        $DCabang        = Cabang::get();
        $DTim    = Tim::get();
        $StatusDebitur  = StatusDebitur::where('status_debitur', '<', 3)->where('status_debitur', '>=', 1)->get();
        $data = DataDebitur::with('statusdebitur', 'picinputer.attribute.cabang', 'picinputer.attribute.tim')
                ->when(Auth::user()->role_id == 4, function($query){
                    $query->where('id_input', Auth::user()->id);
                })
                ->when($status_deb !== '' && $status_deb !== 'null', function($query) use($status_deb){
                    $query->where('status_debitur', $status_deb);
                })
                ->when($startdxx !== '' && $startdxx !== 'null', function($query) use($startdxx){
                    $query->whereDate('created_at', '>=' ,$startdxx);
                })
                ->when($enddxx !== '' && $enddxx !== 'null', function($query) use($enddxx){
                    $query->whereDate('created_at', '<=' ,$enddxx);
                })
                ->when($cabang !== '' && $cabang !== 'null', function($query) use($cabang){
                    $query->whereRelation('picinputer.attribute', 'cabang_id' ,$cabang);
                })
                ->when($tim !== '' && $tim !== 'null', function($query) use($tim){
                    $query->whereRelation('picinputer.attribute', 'tim_id' ,$tim);
                })
                ->where('status_debitur', '>=', 1)
                ->where('status_debitur', '<', 3)
                ->get();
        return view('debitur/datadeb',[
            'data'              => $data,
            'startd'            => $startd,
            'endd'              => $endd,
            'status'            => $status_deb,
            'cabang'            => $cabang,
            'tim'               => $tim,
            'StatusDebitur'     => $StatusDebitur,
            'DCabang'           => $DCabang,
            'DTim'              => $DTim,
            'title'             => 'Data Solicit'
        ]);
    }

    public function MasterData($startd = '', $endd = '', $status_deb = '', $cabang = '', $tim = '')
    {
        $startdxx = 'null';
        $enddxx = 'null';
        if($startd !== '' && $startd != 'null')
        {
            $startdxx = date('Y-m-d', strtotime($startd));
        }
        if($endd !== '' && $endd != 'null')
        {
            $enddxx = date('Y-m-d', strtotime($endd));
        }

        $cabang   = Auth::user()->role_id == role('approval') || Auth::user()->role_id == role('verifikator') || Auth::user()->role_id == role('monitoring') ? Auth::user()->attribute->cabang_id : $cabang;
        $tim   = Auth::user()->role_id == role('approval') || Auth::user()->role_id == role('verifikator') || Auth::user()->role_id == role('monitoring') ? Auth::user()->attribute->tim_id : $tim;
        $DCabang        = Cabang::get();
        $DTim          = Tim::get();
        $StatusDebitur  = StatusDebitur::get();
        $data = DataDebitur::with('statusdebitur', 'picinputer.attribute.cabang', 'picinputer.attribute.tim')
                ->when(Auth::user()->role_id == 4, function($query){
                    $query->where('id_input', Auth::user()->id);
                })
                ->when($status_deb !== '' && $status_deb !== 'null', function($query) use($status_deb){
                    $query->where('status_debitur', $status_deb);
                })
                ->when($startdxx !== '' && $startdxx !== 'null', function($query) use($startdxx){
                    $query->whereDate('created_at', '>=' ,$startdxx);
                })
                ->when($enddxx !== '' && $enddxx !== 'null', function($query) use($enddxx){
                    $query->whereDate('created_at', '<=' ,$enddxx);
                })
                ->when($cabang !== '' && $cabang !== 'null', function($query) use($cabang){
                    $query->whereRelation('picinputer.attribute', 'cabang_id' ,$cabang);
                })
                ->when($tim !== '' && $tim !== 'null', function($query) use($tim){
                    $query->whereRelation('picinputer.attribute', 'tim_id' ,$tim);
                })
                ->get();
        return view('debitur/datadeb',[
            'data'              => $data,
            'startd'            => $startd,
            'endd'              => $endd,
            'status'            => $status_deb,
            'cabang'            => $cabang,
            'tim'               => $tim,
            'StatusDebitur'     => $StatusDebitur,
            'DCabang'           => $DCabang,
            'DTim'              => $DTim,
            'title'             => 'Semua Data'
        ]);
    }

    public function DataPros($startd = '', $endd = '', $status_deb = '', $cabang = '', $tim = '')
    {
        $startdxx = 'null';
        $enddxx = 'null';
        if($startd !== '' && $startd != 'null')
        {
            $startdxx = date('Y-m-d', strtotime($startd));
        }
        if($endd !== '' && $endd != 'null')
        {
            $enddxx = date('Y-m-d', strtotime($endd));
        }

        $cabang   = Auth::user()->role_id == role('approval') || Auth::user()->role_id == role('verifikator') || Auth::user()->role_id == role('monitoring') ? Auth::user()->attribute->cabang_id : $cabang;
        $tim   = Auth::user()->role_id == role('approval') || Auth::user()->role_id == role('verifikator') || Auth::user()->role_id == role('monitoring') ? Auth::user()->attribute->tim_id : $tim;
        $DCabang  = Cabang::get();
        $DTim    = Tim::get();
        $StatusDebitur  = StatusDebitur::where('status_debitur', 3)->orwhere('status_debitur', 4)->get();
        $data = DataDebitur::with('statusdebitur', 'picinputer.attribute.cabang', 'picinputer.attribute.tim')
                ->when(Auth::user()->role_id == 4, function($query){
                    $query->where('id_input', Auth::user()->id);
                })
                ->when($status_deb !== '' && $status_deb !== 'null', function($query) use($status_deb){
                    $query->where('status_debitur', $status_deb);
                })
                ->when($startdxx !== '' && $startdxx !== 'null', function($query) use($startdxx){
                    $query->whereDate('created_at', '>=' ,$startdxx);
                })
                ->when($enddxx !== '' && $enddxx !== 'null', function($query) use($enddxx){
                    $query->whereDate('created_at', '<=' ,$enddxx);
                })
                ->when($cabang !== '' && $cabang !== 'null', function($query) use($cabang){
                    $query->whereRelation('picinputer.attribute', 'cabang_id' ,$cabang);
                })
                ->when($tim !== '' && $tim !== 'null', function($query) use($tim){
                    $query->whereRelation('picinputer.attribute', 'tim_id' ,$tim);
                })
                ->where('status_debitur', '>=', 3)
                ->where('status_debitur', '<=', 4)
                ->get();
        return view('debitur/datadeb',[
            'data'          => $data,
            'startd'        => $startd,
            'endd'          => $endd,
            'status'        => $status_deb,
            'cabang'        => $cabang,
            'tim'           => $tim,
            'StatusDebitur' => $StatusDebitur,
            'DCabang'       => $DCabang,
            'DTim'          => $DTim,
            'title'         => 'Data Prospek'
        ]);
    }

    public function DataPipe($startd = '', $endd = '', $status_deb = '', $cabang = '', $tim = '')
    {
        $startdxx = 'null';
        $enddxx = 'null';
        if($startd !== '' && $startd != 'null')
        {
            $startdxx = date('Y-m-d', strtotime($startd));
        }
        if($endd !== '' && $endd != 'null')
        {
            $enddxx = date('Y-m-d', strtotime($endd));
        }

        $cabang   = Auth::user()->role_id == role('approval') || Auth::user()->role_id == role('verifikator') || Auth::user()->role_id == role('monitoring') ? Auth::user()->attribute->cabang_id : $cabang;
        $tim   = Auth::user()->role_id == role('approval') || Auth::user()->role_id == role('verifikator') || Auth::user()->role_id == role('monitoring') ? Auth::user()->attribute->tim_id : $tim;
        $DCabang  = Cabang::get();
        $DTim    = Tim::get();
        $StatusDebitur = StatusDebitur::get();
        $data = DataDebitur::with('statusdebitur', 'picinputer.attribute.cabang', 'picinputer.attribute.tim')
                ->when(Auth::user()->role_id == 4, function($query){
                    $query->where('id_input', Auth::user()->id);
                })
                ->when($startdxx !== '' && $startdxx !== 'null', function($query) use($startdxx){
                    $query->whereDate('created_at', '>=' ,$startdxx);
                })
                ->when($enddxx !== '' && $enddxx !== 'null', function($query) use($enddxx){
                    $query->whereDate('created_at', '<=' ,$enddxx);
                })
                ->when($cabang !== '' && $cabang !== 'null', function($query) use($cabang){
                    $query->whereRelation('picinputer.attribute', 'cabang_id' ,$cabang);
                })
                ->when($tim !== '' && $tim !== 'null', function($query) use($tim){
                    $query->whereRelation('picinputer.attribute', 'tim_id' ,$tim);
                })
                ->where('status_debitur', 5)
                ->get();
        return view('debitur/datadeb',[
            'data'              => $data,
            'startd'            => $startd,
            'endd'              => $endd,
            'status'            => 5,
            'cabang'            => $cabang,
            'tim'               => $tim,
            'StatusDebitur'     => $StatusDebitur,
            'DCabang'           => $DCabang,
            'DTim'              => $DTim,
            'title'             => 'Data Pipeline'
        ]);
    }

    public function CloseDeb($startd = '', $endd = '', $status_deb = '', $cabang = '', $tim = '')
    {
        $startdxx = 'null';
        $enddxx = 'null';
        if($startd !== '' && $startd != 'null')
        {
            $startdxx = date('Y-m-d', strtotime($startd));
        }
        if($endd !== '' && $endd != 'null')
        {
            $enddxx = date('Y-m-d', strtotime($endd));
        }

        $cabang   = Auth::user()->role_id == role('approval') || Auth::user()->role_id == role('verifikator') || Auth::user()->role_id == role('monitoring') ? Auth::user()->attribute->cabang_id : $cabang;
        $tim   = Auth::user()->role_id == role('approval') || Auth::user()->role_id == role('verifikator') || Auth::user()->role_id == role('monitoring') ? Auth::user()->attribute->tim_id : $tim;
        $DCabang  = Cabang::get();
        $DTim    = Tim::get();
        $StatusDebitur = StatusDebitur::get();
        $data = DataDebitur::with('statusdebitur', 'picinputer.attribute.cabang', 'picinputer.attribute.tim')
                ->when(Auth::user()->role_id == 4, function($query){
                    $query->where('id_input', Auth::user()->id);
                })
                ->when($startdxx !== '' && $startdxx !== 'null', function($query) use($startdxx){
                    $query->whereDate('created_at', '>=' ,$startdxx);
                })
                ->when($enddxx !== '' && $enddxx !== 'null', function($query) use($enddxx){
                    $query->whereDate('created_at', '<=' ,$enddxx);
                })
                ->when($cabang !== '' && $cabang !== 'null', function($query) use($cabang){
                    $query->whereRelation('picinputer.attribute', 'cabang_id' ,$cabang);
                })
                ->when($tim !== '' && $tim !== 'null', function($query) use($tim){
                    $query->whereRelation('picinputer.attribute', 'tim_id' ,$tim);
                })
                ->where('status_debitur', 6)
                ->get();
        return view('debitur/datadeb',[
            'data'              => $data,
            'startd'            => $startd,
            'endd'              => $endd,
            'status'            => 6,
            'cabang'            => $cabang,
            'tim'               => $tim,
            'StatusDebitur'     => $StatusDebitur,
            'DCabang'           => $DCabang,
            'DTim'              => $DTim,
            'title'             => 'Data Booking'
        ]);
    }

    public function RejectDeb($startd = '', $endd = '', $status_deb = '', $cabang = '', $tim = '')
    {
        $startdxx = 'null';
        $enddxx = 'null';
        if($startd !== '' && $startd != 'null')
        {
            $startdxx = date('Y-m-d', strtotime($startd));
        }
        if($endd !== '' && $endd != 'null')
        {
            $enddxx = date('Y-m-d', strtotime($endd));
        }

        $cabang   = Auth::user()->role_id == role('approval') || Auth::user()->role_id == role('verifikator') || Auth::user()->role_id == role('monitoring') ? Auth::user()->attribute->cabang_id : $cabang;
        $tim   = Auth::user()->role_id == role('approval') || Auth::user()->role_id == role('verifikator') || Auth::user()->role_id == role('monitoring') ? Auth::user()->attribute->tim_id : $tim;
        $DCabang  = Cabang::get();
        $DTim    = Tim::get();
        $StatusDebitur = StatusDebitur::get();
        $data = DataDebitur::with('statusdebitur', 'picinputer.attribute.cabang', 'picinputer.attribute.tim')
                ->when(Auth::user()->role_id == 4, function($query){
                    $query->where('id_input', Auth::user()->id);
                })
                ->when($startdxx !== '' && $startdxx !== 'null', function($query) use($startdxx){
                    $query->whereDate('created_at', '>=' ,$startdxx);
                })
                ->when($enddxx !== '' && $enddxx !== 'null', function($query) use($enddxx){
                    $query->whereDate('created_at', '<=' ,$enddxx);
                })
                ->when($cabang !== '' && $cabang !== 'null', function($query) use($cabang){
                    $query->whereRelation('picinputer.attribute', 'cabang_id' ,$cabang);
                })
                ->when($tim !== '' && $tim !== 'null', function($query) use($tim){
                    $query->whereRelation('picinputer.attribute', 'tim_id' ,$tim);
                })
                ->where('status_debitur', '<', 1)
                ->get();
        return view('debitur/datadeb',[
            'data'              => $data,
            'startd'            => $startd,
            'endd'              => $endd,
            'status'            => '',
            'cabang'            => $cabang,
            'tim'               => $tim,
            'StatusDebitur'     => $StatusDebitur,
            'DCabang'           => $DCabang,
            'DTim'              => $DTim,
            'title'             => 'Data Rejected'
        ]);
    }

    public function daftarmonitoring($id_user = '', $status = '', $startd = '', $endd = '')
    {
        $startdxx   = 'null';
        $enddxx     = 'null';
        if($startd !== '' && $startd != 'null')
        {
            $startdxx = date('Y-m-d', strtotime($startd));
        }
        if($endd !== '' && $endd != 'null')
        {
            $enddxx = date('Y-m-d', strtotime($endd));
        }
        if($id_user ==! '' && $status !== '')
        {
            $DCabang = Cabang::get();
            $StatusDebitur = StatusDebitur::get();
            $data = DataDebitur::with('statusdebitur', 'picinputer.attribute.cabang', 'picinputer.attribute.tim')
                    ->when($status == '1', function($query) use ($id_user){
                        $query->where('id_input', $id_user);
                    })
                    ->when($status == '2', function($query) use ($id_user){
                        $query->where('id_verif', $id_user);
                    })
                    ->when($status == '3', function($query) use ($id_user){
                        $query->where('id_approve', $id_user);
                    })
                    ->when($status == '4', function($query) use ($id_user){
                        $query->where('id_app_prospek', $id_user);
                    })
                    ->when($status == '5', function($query) use ($id_user){
                        $query->where('id_update_pipeline', $id_user);
                    })
                    ->when($status == 'rejected', function($query) use ($id_user){
                        $query->whereIn('status_debitur', array(0.61,0.32,0.34))->where('id_input', $id_user);;
                    })
                    ->when($startdxx !== '' && $startdxx !== 'null', function($query) use($startdxx, $status){
                        $query->when($status == '1', function($query2) use ($startdxx){
                            $query2->whereDate('created_at', '>=' ,$startdxx);
                        })->when($status == '2', function($query2) use ($startdxx){
                            $query2->whereDate('tanggal_verif', '>=' ,$startdxx);
                        })->when($status == '3', function($query2) use ($startdxx){
                            $query2->whereDate('tanggal_approve', '>=' ,$startdxx);
                        })->when($status == '4', function($query2) use ($startdxx){
                            $query2->whereDate('tanggal_app_prospek', '>=' ,$startdxx);
                        })->when($status == '5', function($query2) use ($startdxx){
                            $query2->whereDate('tanggal_update_pipeline', '>=' ,$startdxx);
                        });
                    })
                    ->when($enddxx !== '' && $enddxx !== 'null', function($query) use($enddxx, $status){
                        $query->when($status == '1', function($query2) use ($enddxx){
                            $query2->whereDate('created_at', '<=' ,$enddxx);
                        })->when($status == '2', function($query2) use ($enddxx){
                            $query2->whereDate('tanggal_verif', '<=' ,$enddxx);
                        })->when($status == '3', function($query2) use ($enddxx){
                            $query2->whereDate('tanggal_approve', '<=' ,$enddxx);
                        })->when($status == '4', function($query2) use ($enddxx){
                            $query2->whereDate('tanggal_app_prospek', '<=' ,$enddxx);
                        })->when($status == '5', function($query2) use ($enddxx){
                            $query2->whereDate('tanggal_update_pipeline', '<=' ,$enddxx);
                        });
                    })
                    ->get();
            return view('debitur/datadeb',[
                'data'          => $data,
                'startd'        =>'',
                'endd'          =>'',
                'status'        =>'',
                'cabang'        => '',
                'StatusDebitur' => $StatusDebitur,
                'DCabang'       => $DCabang,
                'title'         => 'Data Monitoring'
            ]);
        }
        else
        {
            return redirect()->route('monitoring');
        }
    }

    public function GetDataByCodePos(Request $request)
    {
        $nama_desa = '';
        $nama_kecamatan = '';
        $nama_kota = '';
        $nama_provinsi = '';

        $data = Kodepos::with('desa', 'kecamatan', 'kota', 'provinsi')->where('kodepos', $request->kodepos)->first();
        if(!empty($data))
        {
            $nama_desa      = ($data->desa != null ? $data->desa->nama_desa : '');
            $nama_kecamatan = ($data->kecamatan != null ? $data->kecamatan->nama_kecamatan : '');
            $nama_kota      = ($data->kota != null ? $data->kota->nama_kota : '');
            $nama_provinsi  = ($data->provinsi != null ? $data->provinsi->nama_provinsi : '');
        }
        return response()->json(array(
            'desa'              => $nama_desa,
            'kecamatan'         => $nama_kecamatan,
            'kota'              => $nama_kota,
            'provinsi'          => $nama_provinsi,
            'finddata'          => $request->finddata
        ));
    }

    public function create()
    {
        $Provinsi = Provinsi::get();
        $sumber = Sumber::get();
        $sektor = Sektor::get();
        return view('debitur/datadebcreate', [
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
        // multiple image
        $dokumen_lokasi = array();
        for($i=1; $i<=$request->jumlah_foto; $i++)
        {
            $nama   = "Document_Location_".rand().".".$request->file('foto_lokasi_rep_'.$i)->extension();;
            Storage::putFileAs('Dokumen Lokasi', $request->file('foto_lokasi_rep_'.$i), $nama);

            $dokumen_lokasi[] = 'Dokumen Lokasi/'.$nama;
        }

        // doc pre screen
        $dokumen_prescreen = array();
        for($i=1; $i<=$request->jumlah_dok; $i++)
        {
            $nama   = "Document_Prescreen_".rand().".".$request->file('foto_dok_rep_'.$i)->extension();;
            Storage::putFileAs('Dokumen Prescreen', $request->file('foto_dok_rep_'.$i), $nama);

            $dokumen_prescreen[] = 'Dokumen Prescreen/'.$nama;
        }

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
        $data->layanantransaksilain         = $request->layanantransaksilain;
        $data->sumber                       = $request->sumber;
        $data->dataleads                    = $request->dataleads;
        $data->id_input                     = $user->id;
        $data->nama_input                   = $user->name;
        $data->npp_input                    = $user->attribute->npp;
        $data->dokumen_lokasi               = implode(';',$dokumen_lokasi);
        $data->dokumen_prescreen            = implode(';',$dokumen_prescreen);
        $data->save();

        return redirect()->route('DataSol')->with(['message' => 'Berhasil menambah data.']);
    }

    public function detail($id)
    {
        $mst_jenis_fasilitas    = Jenis_Fasilitas::get();
        $mst_skim               = SKIM::get();

        if(Auth::user()->role_id == role('approval') || Auth::user()->role_id == role('verifikator') || Auth::user()->role_id == role('Inputer'))
        {
            $data                   = DataDebitur::with('statusdebitur', 'picinputer.attribute')->whereRelation('picinputer.attribute', 'cabang_id', '=', Auth::user()->attribute->cabang_id)->findOrFail($id);
        }
        else
        {
            $data                   = DataDebitur::with('statusdebitur', 'picinputer.attribute')->findOrFail($id);
        }

        return view('debitur/datadebdetail', [
            'data'                      => $data,
            'mst_skim'                  => $mst_skim,
            'mst_jenis_fasilitas'       => $mst_jenis_fasilitas
        ]);
    }

    public function printdata($id)
    {
        $data                   = DataDebitur::with('statusdebitur', 'picinputer.attribute')->findOrFail($id);
        pdf::setOption("isPhpEnabled", true);

        $pdf = Pdf::loadView('debitur.printdata', array('data' => $data))->setOptions(['defaultFont' => 'sans-serif']);
        $pdf->set_option("isPhpEnabled", true);
        return $pdf->stream('Data Debitur.pdf');
    }


    public function edit($id)
    {
        $data       = DataDebitur::findOrFail($id);
        $Provinsi   = Provinsi::get();
        $sumber     = Sumber::get();
        $sektor     = Sektor::get();
        return view('debitur/datadebedit', [
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

        $datadebitur        = DataDebitur::where('id', $request->id)->first();
        //////////////////////// GAMBAR LOKASI
                if($datadebitur->dokumen_lokasi != '')
                {
                    $listfile_lok          = explode(';', $datadebitur->dokumen_lokasi);
                }
                else
                {
                    $listfile_lok          = array();
                }
            /////////////////////// UPDATE ///////////////////////
                foreach($listfile_lok as $index=>$filex)
                {
                    $i = $index+1;
                    if($request->has('foto_lokasi_rep_'.$i))
                    {
                        if ($request->hasFile('foto_lokasi_rep_'.$i)) {
                            if (Storage::exists($filex)) {
                                Storage::delete($filex);
                            }
                            $nama   = "Document_Location_".rand().".".$request->file('foto_lokasi_rep_'.$i)->extension();
                            Storage::putFileAs('Dokumen Lokasi', $request->file('foto_lokasi_rep_'.$i), $nama);
                            $listfile_lok[$index] = 'Dokumen Lokasi/'.$nama;
                        }
                    }
                }
            /////////////////////// DELETE ///////////////////////
                if($request->hapus_foto != '')
                {
                    foreach(explode(';', $request->hapus_foto) as $item)
                    {
                        if($item != '')
                        {
                            if (Storage::exists($listfile_lok[$item-1])) {
                                Storage::delete($listfile_lok[$item-1]);
                            }
                            unset($listfile_lok[$item-1]);
                        }
                    }
                }

            /////////////////////// ADD ///////////////////////
                if($request->jumlah_foto_baru > 0)
                {
                    for($i=1; $i<=$request->jumlah_foto_baru; $i++)
                    {
                        if ($request->hasFile('foto_lokasi_baru_rep_'.$i)) {
                            $nama   = "Document_Location_".rand().".".$request->file('foto_lokasi_baru_rep_'.$i)->extension();
                            Storage::putFileAs('Dokumen Lokasi', $request->file('foto_lokasi_baru_rep_'.$i), $nama);
                            $listfile_lok[] = 'Dokumen Lokasi/'.$nama;
                        }
                    }
                }

        //////////////////////// DOKUMEN PRESCREEN
                if($datadebitur->dokumen_prescreen != '')
                {
                    $listfile_pre          = explode(';', $datadebitur->dokumen_prescreen);
                }
                else
                {
                    $listfile_pre          = array();
                }
            /////////////////////// UPDATE ///////////////////////
                foreach($listfile_pre as $index=>$filex)
                {
                    $i = $index+1;
                    if($request->has('foto_dok_rep_'.$i))
                    {
                        if ($request->hasFile('foto_dok_rep_'.$i)) {
                            if (Storage::exists($filex)) {
                                Storage::delete($filex);
                            }
                            $nama   = "Document_".rand().".".$request->file('foto_dok_rep_'.$i)->extension();
                            Storage::putFileAs('Dokumen Prescreen', $request->file('foto_dok_rep_'.$i), $nama);
                            $listfile_pre[$index] = 'Dokumen Prescreen/'.$nama;
                        }
                    }
                }
            /////////////////////// DELETE ///////////////////////
                if($request->hapus_dok != '')
                {
                    foreach(explode(';', $request->hapus_dok) as $item)
                    {
                        if($item != '')
                        {
                            if (Storage::exists($listfile_pre[$item-1])) {
                                Storage::delete($listfile_pre[$item-1]);
                            }
                            unset($listfile_pre[$item-1]);
                        }
                    }
                }

            /////////////////////// ADD ///////////////////////
                if($request->jumlah_dok_baru > 0)
                {
                    for($i=1; $i<=$request->jumlah_dok_baru; $i++)
                    {
                        if ($request->hasFile('foto_dok_baru_rep_'.$i)) {
                            $nama   = "Document_Prescreen_".rand().".".$request->file('foto_dok_baru_rep_'.$i)->extension();
                            Storage::putFileAs('Dokumen Prescreen', $request->file('foto_dok_baru_rep_'.$i), $nama);
                            $listfile_pre[] = 'Dokumen Prescreen/'.$nama;
                        }
                    }
                }

        $data->dokumen_lokasi               = implode(';',$listfile_lok);
        $data->dokumen_prescreen            = implode(';',$listfile_pre);

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
        $data->layanantransaksilain         = $request->layanantransaksilain;
        $data->sumber                       = $request->sumber;
        $data->dataleads                    = $request->dataleads;
        $data->id_update                    = $user->id;
        $data->nama_update                  = $user->name;
        $data->npp_update                   = $user->attribute->npp;
        $data->tanggal_update               = date('Y-m-d H:i:s');

        $data->save();

        if(Auth::user()->role_id == 5)
        {
            return redirect()->route('MasterData')->with(['message' => 'Berhasil mengupdate data.']);
        }
        else
        {
            return redirect()->route('DataSol')->with(['message' => 'Berhasil mengupdate data.']);
        }
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
        return redirect()->route('datadebdetail', ['id'=>$request->id])->with(['message' => 'Berhasil Memverifikasi Data']);
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
        return redirect()->route('datadebdetail', ['id'=>$request->id])->with(['message' => 'Berhasil Menyetujui Data']);
    }

    public function solicitdeny(Request $request)
    {
        $user                     = User::with('attribute')->where('id', Auth::user()->id)->first();
        $data                     = DataDebitur::find($request->id);
        $data->alasantolak        = $request->alasantolak;
        $data->id_penolak         = $user->id;
        $data->nama_penolak       = $user->name;
        $data->npp_penolak        = $user->attribute->npp;
        $data->status_debitur     = '0.'.Auth::user()->role_id.$data->status_debitur;
        $data->tanggal_penolakan  = date('Y-m-d H:i:s');
        $data->save();
        return redirect()->route('datadebdetail', ['id'=>$request->id])->with(['message' => 'Berhasil Menolak Data']);
    }

    public function delete(Request $request)
    {
        $user                     = User::with('attribute')->where('id', Auth::user()->id)->first();
        $data                     = DataDebitur::find($request->id);
        $data->id_penghapus         = $user->id;
        $data->nama_penghapus       = $user->name;
        $data->npp_penghapus        = $user->attribute->npp;
        $data->tanggal_penghapusan  = date('Y-m-d H:i:s');
        $data->save();

        $data = DataDebitur::find($request->id);
        $data->delete();
        return redirect()->route($request->routename)->with(['message' => 'Berhasil menghapus data.']);
    }

    public function solicitappall(Request $request)
    {
        $data_id = explode(',',$request->id);
        if(count($data_id)>0)
        {
            $user                     = User::with('attribute')->where('id', Auth::user()->id)->first();
            foreach($data_id as $id)
            {
                if($id != '')
                {
                    $data                     = DataDebitur::find($id);
                    $data->id_approve         = $user->id;
                    $data->nama_approve       = $user->name;
                    $data->npp_approve        = $user->attribute->npp;
                    $data->status_debitur     = 3;
                    $data->tanggal_approve    = date('Y-m-d H:i:s');
                    $data->save();
                }
            }
        }
        return redirect()->route($request->routename, ['id'=>$request->id])->with(['message' => 'Berhasil Menyetujui Data']);
    }

    public function solicitverifall(Request $request)
    {
        $data_id = explode(',',$request->id);
        if(count($data_id)>0)
        {
            $user                     = User::with('attribute')->where('id', Auth::user()->id)->first();
            foreach($data_id as $id)
            {
                if($id != '')
                {
                    $data                   = DataDebitur::find($id);
                    $data->pre_screen       = 1;
                    $data->ots_penyelia     = 1;
                    $data->ots_pemimpin     = 1;
                    $data->id_verif         = $user->id;
                    $data->nama_verif       = $user->name;
                    $data->npp_verif        = $user->attribute->npp;
                    $data->status_debitur   = 2;
                    $data->tanggal_verif    = date('Y-m-d H:i:s');
                    $data->save();
                }
            }
        }
        return redirect()->route($request->routename, ['id'=>$request->id])->with(['message' => 'Berhasil Memverifikasi Data']);
    }

    public function solicitdenyall(Request $request)
    {
        $data_id = explode(',',$request->id);
        if(count($data_id)>0)
        {
            $user                     = User::with('attribute')->where('id', Auth::user()->id)->first();
            foreach($data_id as $id)
            {
                if($id != '')
                {
                    $data                     = DataDebitur::find($id);
                    $data->alasantolak        = $request->alasantolak;
                    $data->id_penolak         = $user->id;
                    $data->nama_penolak       = $user->name;
                    $data->npp_penolak        = $user->attribute->npp;
                    $data->status_debitur     = '0.'.Auth::user()->role_id.$data->status_debitur;
                    $data->tanggal_penolakan  = date('Y-m-d H:i:s');
                    $data->save();
                }
            }
        }
        return redirect()->route($request->routename)->with(['message' => 'Berhasil menolak data.']);
    }

    public function solicitdeleteall(Request $request)
    {
        $data_id = explode(',',$request->id);
        if(count($data_id)>0)
        {
            $user                     = User::with('attribute')->where('id', Auth::user()->id)->first();
            foreach($data_id as $id)
            {
                if($id != '')
                {
                    $data                       = DataDebitur::find($id);
                    $data->id_penghapus         = $user->id;
                    $data->nama_penghapus       = $user->name;
                    $data->npp_penghapus        = $user->attribute->npp;
                    $data->tanggal_penghapusan  = date('Y-m-d H:i:s');
                    $data->save();

                    $data = DataDebitur::find($id);
                    $data->delete();
                }
            }
        }
        return redirect()->route($request->routename)->with(['message' => 'Berhasil menghapus data.']);
    }

    public function prospekdata(Request $request)
    {
        $user              = User::with('attribute')->where('id', Auth::user()->id)->first();
        $data              = DataDebitur::find($request->id);

        $data->nominal_usulan           = $request->nominal_usulan;
        $data->jenis_fasilitas          = $request->jenis_fasilitas;
        $data->skim                     = $request->skim;
        $data->kewenangan_komite        = $request->kewenangan_komite;
        $data->id_update_prospek        = $user->id;
        $data->nama_update_prospek      = $user->name;
        $data->npp_update_prospek       = $user->attribute->npp;
        $data->tanggal_analisa          = $request->tanggal_analisa;
        $data->tanggal_komite           = $request->tanggal_komite;
        $data->status_debitur           = 4;
        $data->tanggal_update_prospek   = date('Y-m-d H:i:s');
        $data->save();
        return redirect()->route('DataPros')->with(['message' => 'Berhasil mengupdate data.']);
    }

    public function prospectappall(Request $request)
    {
        $data_id = explode(',',$request->id);
        if(count($data_id)>0)
        {
            $user                     = User::with('attribute')->where('id', Auth::user()->id)->first();
            foreach($data_id as $id)
            {
                if($id != '')
                {
                    $data                     = DataDebitur::find($id);
                    $data->id_app_prospek        = $user->id;
                    $data->nama_app_prospek      = $user->name;
                    $data->npp_app_prospek       = $user->attribute->npp;
                    $data->status_debitur        = 5;
                    $data->tanggal_app_prospek   = date('Y-m-d H:i:s');
                    $data->save();
                }
            }
        }
        return redirect()->route($request->routename, ['id'=>$request->id])->with(['message' => 'Berhasil Menyetujui Data']);
    }

    public function appprospek(Request $request)
    {
        $user              = User::with('attribute')->where('id', Auth::user()->id)->first();
        $data              = DataDebitur::find($request->id);

        $data->id_app_prospek        = $user->id;
        $data->nama_app_prospek      = $user->name;
        $data->npp_app_prospek       = $user->attribute->npp;
        $data->status_debitur        = 5;
        $data->tanggal_app_prospek   = date('Y-m-d H:i:s');
        $data->save();
        return redirect()->route('DataPros')->with(['message' => 'Berhasil mengupdate data.']);
    }


    public function pipelinedata(Request $request)
    {
        $user              = User::with('attribute')->where('id', Auth::user()->id)->first();
        $data              = DataDebitur::find($request->id);

        $data->nominal_keputusan        = $request->nominal_keputusan;
        $data->tanggal_tanda_tangan_pk  = $request->tanggal_tanda_tangan_pk;
        $data->tanggal_pencairan        = $request->tanggal_pencairan;
        $data->nominal_cair             = $request->nominal_cair;

        $data->id_update_pipeline        = $user->id;
        $data->nama_update_pipeline      = $user->name;
        $data->npp_update_pipeline       = $user->attribute->npp;
        $data->status_debitur            = 6;
        $data->tanggal_update_pipeline   = date('Y-m-d H:i:s');
        $data->save();
        return redirect()->route('DataPipe')->with(['message' => 'Berhasil mengupdate data.']);
    }



}
