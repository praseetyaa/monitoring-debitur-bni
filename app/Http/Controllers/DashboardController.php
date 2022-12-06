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
    public function index(Request $request)
    {
        $verifsolicit       = DataDebitur::with('statusdebitur')->where('status_debitur','=',1)->get();
        $appsolicit         = DataDebitur::with('statusdebitur')->where('status_debitur','=',2)->get();
        $needprospek        = DataDebitur::with('statusdebitur')->where('status_debitur','=',3)->get();
        $user               = User::with('role', 'attribute.cabang', 'attribute.jabatan')->where('id','=',Auth::user()->id)->first();
        $pengumuman         = Pengumuman::whereDate('expired', '>=' ,date('Y-m-d'))->orderBy("tanggal_pebuatan", "desc")->paginate(2);
        return view('admin/dashboard/index', [
            'user'              => $user,
            'verifsolicit'      => $verifsolicit,
            'appsolicit'        => $appsolicit,
            'needprospek'       => $needprospek,
            'pengumuman'        => $pengumuman
        ]);
    }
}
