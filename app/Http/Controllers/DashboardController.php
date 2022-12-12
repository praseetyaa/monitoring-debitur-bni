<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Ajifatur\Helpers\DateTimeExt;
use App\Models\Cabang;
use App\Models\DataDebitur;
use App\Models\Kategori;
use App\Models\MonitoringDetail;
use App\Models\Pengumuman;
use App\Models\User;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index($tahun = '')
    {
        // if($tahun == '')
        // {
        //     $tahun =
        // }

        $verifsolicit       = DataDebitur::with('statusdebitur')->where('status_debitur','=',1)->get();
        $appsolicit         = DataDebitur::with('statusdebitur')->where('status_debitur','=',2)->get();
        $needprospek        = DataDebitur::with('statusdebitur')->where('status_debitur','=',3)->where('id_input','=',Auth::user()->id)->get();
        $appprospek         = DataDebitur::with('statusdebitur')->where('status_debitur','=',4)->get();
        $needpipeline       = DataDebitur::with('statusdebitur')->where('status_debitur','=',5)->where('id_input','=',Auth::user()->id)->get();
        $user               = User::with('role', 'attribute.cabang', 'attribute.jabatan')->where('id','=',Auth::user()->id)->first();
        $pengumuman         = Pengumuman::whereDate('expired', '>=' ,date('Y-m-d'))->orderBy("tanggal_pebuatan", "desc")->get();

        if(Auth::user()->role_id == 4)
        {
            $jumlahsolicit      = DataDebitur::with('statusdebitur')->where('status_debitur','>=',1)->where('status_debitur','<=',2)->where('id_input','=',Auth::user()->id)->get();
            $jumlahprospek      = DataDebitur::with('statusdebitur')->where('status_debitur','>=',3)->where('status_debitur','<=',4)->where('id_input','=',Auth::user()->id)->get();
            $jumlahpipeline     = DataDebitur::with('statusdebitur')->where('status_debitur','=',5)->where('id_input','=',Auth::user()->id)->get();
            $jumlahclose        = DataDebitur::with('statusdebitur')->where('status_debitur','=',6)->where('id_input','=',Auth::user()->id)->get();
            $jumlahreject        = DataDebitur::with('statusdebitur')->where('status_debitur','<',1)->where('id_input','=',Auth::user()->id)->get();

        }
        else
        {
            $jumlahsolicit      = DataDebitur::with('statusdebitur')->where('status_debitur','>=',1)->where('status_debitur','<=',2)->get();
            $jumlahprospek      = DataDebitur::with('statusdebitur')->where('status_debitur','>=',3)->where('status_debitur','<=',4)->get();
            $jumlahpipeline     = DataDebitur::with('statusdebitur')->where('status_debitur','=',5)->get();
            $jumlahclose        = DataDebitur::with('statusdebitur')->where('status_debitur','=',6)->get();
            $jumlahreject       = DataDebitur::with('statusdebitur')->where('status_debitur','<',1)->get();
        }

        return view('admin/dashboard/index', [
            'user'              => $user,
            'verifsolicit'      => $verifsolicit,
            'appsolicit'        => $appsolicit,
            'needprospek'       => $needprospek,
            'appprospek'        => $appprospek,
            'needpipeline'      => $needpipeline,
            'pengumuman'        => $pengumuman,

            'jumlahsolicit'     => $jumlahsolicit,
            'jumlahprospek'     => $jumlahprospek,
            'jumlahpipeline'    => $jumlahpipeline,
            'jumlahclose'       => $jumlahclose,
            'jumlahreject'      => $jumlahreject
        ]);
    }
}
