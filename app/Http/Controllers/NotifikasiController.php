<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\User;
use App\Models\DataDebitur;

class NotifikasiController extends Controller
{
    public function index()
    {
        $verifsolicit       = DataDebitur::with('statusdebitur')->whereRelation('picinputer.attribute', 'cabang_id', '=', Auth::user()->attribute->cabang_id)->where('status_debitur','=',1)->get();
        $appsolicit         = DataDebitur::with('statusdebitur')->whereRelation('picinputer.attribute', 'cabang_id', '=', Auth::user()->attribute->cabang_id)->where('status_debitur','=',2)->get();
        $needprospek        = DataDebitur::with('statusdebitur')->where('status_debitur','=',3)->where('id_input','=',Auth::user()->id)->get();
        $appprospek         = DataDebitur::with('statusdebitur')->whereRelation('picinputer.attribute', 'cabang_id', '=', Auth::user()->attribute->cabang_id)->where('status_debitur','=',4)->get();
        $needpipeline       = DataDebitur::with('statusdebitur')->where('status_debitur','=',5)->where('id_input','=',Auth::user()->id)->get();

        return view('notifikasi/notifikasi',[
            'verifsolicit'      => $verifsolicit,
            'appsolicit'        => $appsolicit,
            'needprospek'       => $needprospek,
            'appprospek'        => $appprospek,
            'needpipeline'      => $needpipeline,
        ]);
    }

}
