<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Ajifatur\Helpers\DateTimeExt;
use App\Models\Cabang;
use App\Models\Tim;
use App\Models\Role;
use App\Models\DataDebitur;
use App\Models\Kategori;
use App\Models\MonitoringDetail;
use App\Models\Pengumuman;
use App\Models\Sektor;
use App\Models\User;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index($tahun = '', $startd = '', $endd = '', $cabang ='', $tim ='', $role ='')
    {
        if($tahun == '')
        {
            $tahun = date('Y');
        }

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

        $cabang   = Auth::user()->role_id == role('approval') || Auth::user()->role_id == role('verifikator') ? Auth::user()->attribute->cabang_id : $cabang;
        $DCabang  = Cabang::get();
        $DTim    = Tim::get();
        $DRoles   = Role::whereIn('id', [3,4,6])->get();

        $verifsolicit       = DataDebitur::with('statusdebitur')->whereRelation('picinputer.attribute', 'cabang_id', '=', Auth::user()->attribute->cabang_id)->where('status_debitur','=',1)->get();
        $appsolicit         = DataDebitur::with('statusdebitur')->whereRelation('picinputer.attribute', 'cabang_id', '=', Auth::user()->attribute->cabang_id)->where('status_debitur','=',2)->get();
        $needprospek        = DataDebitur::with('statusdebitur')->where('status_debitur','=',3)->where('id_input','=',Auth::user()->id)->get();
        $appprospek         = DataDebitur::with('statusdebitur')->whereRelation('picinputer.attribute', 'cabang_id', '=', Auth::user()->attribute->cabang_id)->where('status_debitur','=',4)->get();
        $needpipeline       = DataDebitur::with('statusdebitur')->where('status_debitur','=',5)->where('id_input','=',Auth::user()->id)->get();
        $user    = User::with('role', 'attribute.cabang', 'attribute.jabatan', 'attribute.tim')
                ->whereIn('role_id', [3,4,6])
                ->withCount(['datainput' => function ($item) use ($startdxx, $enddxx) {
                    $item->when($startdxx !== '' && $startdxx !== 'null', function($query) use($startdxx){
                        $query->whereDate('created_at', '>=' ,$startdxx);
                    })->when($enddxx !== '' && $enddxx !== 'null', function($query) use($enddxx){
                        $query->whereDate('created_at', '<=' ,$enddxx);
                    });
                }])
                ->withCount(['dataverif' => function ($item) use ($startdxx, $enddxx) {
                    $item->when($startdxx !== '' && $startdxx !== 'null', function($query) use($startdxx){
                        $query->whereDate('tanggal_verif', '>=' ,$startdxx);
                    })->when($enddxx !== '' && $enddxx !== 'null', function($query) use($enddxx){
                        $query->whereDate('tanggal_verif', '<=' ,$enddxx);
                    });
                }])
                ->withCount(['dataapp' => function ($item) use ($startdxx, $enddxx) {
                    $item->when($startdxx !== '' && $startdxx !== 'null', function($query) use($startdxx){
                        $query->whereDate('tanggal_approve', '>=' ,$startdxx);
                    })->when($enddxx !== '' && $enddxx !== 'null', function($query) use($enddxx){
                        $query->whereDate('tanggal_approve', '<=' ,$enddxx);
                    });
                }])
                ->withCount(['dataapppros' => function ($item) use ($startdxx, $enddxx) {
                    $item->when($startdxx !== '' && $startdxx !== 'null', function($query) use($startdxx){
                        $query->whereDate('tanggal_app_prospek', '>=' ,$startdxx);
                    })->when($enddxx !== '' && $enddxx !== 'null', function($query) use($enddxx){
                        $query->whereDate('tanggal_app_prospek', '<=' ,$enddxx);
                    });
                }])
                ->withCount(['totalpipeline' => function ($item) use ($startdxx, $enddxx) {
                    $item->when($startdxx !== '' && $startdxx !== 'null', function($query) use($startdxx){
                        $query->whereDate('tanggal_update_pipeline', '>=' ,$startdxx);
                    })->when($enddxx !== '' && $enddxx !== 'null', function($query) use($enddxx){
                        $query->whereDate('tanggal_update_pipeline', '<=' ,$enddxx);
                    });
                }])
                ->when($cabang !== '' && $cabang !== 'null', function($query) use($cabang){
                    $query->whereRelation('attribute.cabang', 'id' ,$cabang);
                })
                ->when($tim !== '' && $tim !== 'null', function($query) use($tim){
                    $query->whereRelation('attribute.tim', 'id' ,$tim);
                })
                ->when($role !== '' && $role !== 'null', function($query) use($role){
                    $query->where('role_id' ,$role);
                })
                ->get();

        foreach($user as $index=>$item)
        {
            $sum_nom            = 0;
            if($item->role_id == 3)
            {
                $sum_nom            = DataDebitur::where('status_debitur','=',6)
                                            ->where('id_app_prospek','=',$item->id)
                                            ->when($startdxx !== '' && $startdxx !== 'null', function($query) use($startdxx){
                                                $query->whereDate('tanggal_pencairan', '>=' ,$startdxx);
                                            })
                                            ->when($enddxx !== '' && $enddxx !== 'null', function($query) use($enddxx){
                                                $query->whereDate('tanggal_pencairan', '<=' ,$enddxx);
                                            })
                                            ->selectRaw("SUM(REPLACE(nominal_cair, '.', '')) as nom")->get();
            }
            else if($item->role_id == 4)
            {
                $sum_nom            = DataDebitur::where('status_debitur','=',6)
                                            ->where('id_update_pipeline','=',$item->id)
                                            ->when($startdxx !== '' && $startdxx !== 'null', function($query) use($startdxx){
                                                $query->whereDate('tanggal_pencairan', '>=' ,$startdxx);
                                            })
                                            ->when($enddxx !== '' && $enddxx !== 'null', function($query) use($enddxx){
                                                $query->whereDate('tanggal_pencairan', '<=' ,$enddxx);
                                            })
                                            ->selectRaw("SUM(REPLACE(nominal_cair, '.', '')) as nom")->get();
            }
            else if($item->role_id == 6)
            {
                $sum_nom            = DataDebitur::where('status_debitur','=',6)
                                            ->where('id_verif','=',$item->id)
                                            ->when($startdxx !== '' && $startdxx !== 'null', function($query) use($startdxx){
                                                $query->whereDate('tanggal_pencairan', '>=' ,$startdxx);
                                            })
                                            ->when($enddxx !== '' && $enddxx !== 'null', function($query) use($enddxx){
                                                $query->whereDate('tanggal_pencairan', '<=' ,$enddxx);
                                            })
                                            ->selectRaw("SUM(REPLACE(nominal_cair, '.', '')) as nom")->get();
            }
            $user[$index]->total_nom    = number_format($sum_nom[0]->nom,0,',','.');
        }

        $pengumuman         = Pengumuman::orderBy("tanggal_pebuatan", "desc")->get();

        $danacair           = array();
        $dtsolicit          = array();
        $dtprospect         = array();
        $dtsolicitapp       = array();
        $dtclose            = array();
        $dtreject           = array();


        if(Auth::user()->role_id == 4)
        {
            $jumlahsolicit      = DataDebitur::with('statusdebitur')->where('status_debitur','>=',1)->where('status_debitur','<=',2)->where('id_input','=',Auth::user()->id)->get();
            $jumlahprospek      = DataDebitur::with('statusdebitur')->where('status_debitur','>=',3)->where('status_debitur','<=',4)->where('id_input','=',Auth::user()->id)->get();
            $jumlahpipeline     = DataDebitur::with('statusdebitur')->where('status_debitur','=',5)->where('id_input','=',Auth::user()->id)->get();
            $jumlahclose        = DataDebitur::with('statusdebitur')->where('status_debitur','=',6)->where('id_input','=',Auth::user()->id)->get();
            $jumlahreject       = DataDebitur::with('statusdebitur')->where('status_debitur','<',1)->where('id_input','=',Auth::user()->id)->get();

            $jumlahsektor       = DataDebitur::whereRaw('extract(year from created_at) = ?', [$tahun])->where('id_input','=',Auth::user()->id)->select('sektor', DataDebitur::raw('count(*) as total'))
                                    ->groupBy('sektor')
                                    ->pluck('total','sektor')
                                    ;

            for($month=1; $month<=12; $month++)
            {
                $dataclosed     = DataDebitur::with('statusdebitur')->whereRaw('extract(month from tanggal_pencairan) = ? AND extract(year from tanggal_pencairan) = ?', [$month, $tahun])->where('status_debitur','=',6)->where('id_input','=',Auth::user()->id)->get();
                $dtsolicit[]    = count(DataDebitur::with('statusdebitur')->whereRaw('extract(month from created_at) = ? AND extract(year from created_at) = ?', [$month, $tahun])->where('status_debitur','>=',1)->where('id_input','=',Auth::user()->id)->get());
                $dtsolicitapp[] = count(DataDebitur::with('statusdebitur')->whereRaw('extract(month from tanggal_approve) = ? AND extract(year from tanggal_approve) = ?', [$month, $tahun])->where('status_debitur','>=',1)->where('id_input','=',Auth::user()->id)->get());
                $dtprospect[]   = count(DataDebitur::with('statusdebitur')->whereRaw('extract(month from tanggal_app_prospek) = ? AND extract(year from tanggal_app_prospek) = ?', [$month, $tahun])->where('status_debitur','>=',1)->where('id_input','=',Auth::user()->id)->get());
                $dtclose[]      = count(DataDebitur::with('statusdebitur')->whereRaw('extract(month from tanggal_update_pipeline) = ? AND extract(year from tanggal_update_pipeline) = ?', [$month, $tahun])->where('status_debitur','>=',1)->where('id_input','=',Auth::user()->id)->get());
                $dtreject[]     = count(DataDebitur::with('statusdebitur')->whereRaw('extract(month from tanggal_penolakan) = ? AND extract(year from tanggal_penolakan) = ?', [$month, $tahun])->where('status_debitur','<',1)->where('id_input','=',Auth::user()->id)->get());

                if(count($dataclosed)>0)
                {
                    $nominalcair = 0;
                    foreach($dataclosed as $item)
                    {
                        $num = (float) str_replace(',', '.', str_replace('.', '', $item->nominal_cair));
                        $nominalcair += $num;
                    }
                    $danacair[] = $nominalcair;
                }
                else
                {
                    $danacair[] = 0;
                }
            }
        }
        else
        {
            if(Auth::user()->role_id == role('approval') || Auth::user()->role_id == role('verifikator'))
            {
                $jumlahsolicit    = DataDebitur::with('statusdebitur')->whereRelation('picinputer.attribute', 'cabang_id', '=', Auth::user()->attribute->cabang_id)->where('status_debitur','>=',1)->where('status_debitur','<=',2)->get();
                $jumlahprospek    = DataDebitur::with('statusdebitur')->whereRelation('picinputer.attribute', 'cabang_id', '=', Auth::user()->attribute->cabang_id)->where('status_debitur','>=',3)->where('status_debitur','<=',4)->get();
                $jumlahpipeline   = DataDebitur::with('statusdebitur')->whereRelation('picinputer.attribute', 'cabang_id', '=', Auth::user()->attribute->cabang_id)->where('status_debitur','=',5)->get();
                $jumlahclose      = DataDebitur::with('statusdebitur')->whereRelation('picinputer.attribute', 'cabang_id', '=', Auth::user()->attribute->cabang_id)->where('status_debitur','=',6)->get();
                $jumlahreject     = DataDebitur::with('statusdebitur')->whereRelation('picinputer.attribute', 'cabang_id', '=', Auth::user()->attribute->cabang_id)->where('status_debitur','<',1)->get();
            }
            else
            {
                $jumlahsolicit    = DataDebitur::with('statusdebitur')->where('status_debitur','>=',1)->where('status_debitur','<=',2)->get();
                $jumlahprospek    = DataDebitur::with('statusdebitur')->where('status_debitur','>=',3)->where('status_debitur','<=',4)->get();
                $jumlahpipeline   = DataDebitur::with('statusdebitur')->where('status_debitur','=',5)->get();
                $jumlahclose      = DataDebitur::with('statusdebitur')->where('status_debitur','=',6)->get();
                $jumlahreject     = DataDebitur::with('statusdebitur')->where('status_debitur','<',1)->get();
            }


            $jumlahsektor         = DataDebitur::whereRaw('extract(year from created_at) = ?', [$tahun])->select('sektor', DataDebitur::raw('count(*) as total'))
                                    ->groupBy('sektor')
                                    ->pluck('total','sektor');

            for($month=1; $month<=12; $month++)
            {
                $dataclosed       = DataDebitur::with('statusdebitur')->whereRaw('extract(month from tanggal_pencairan) = ? AND extract(year from tanggal_pencairan) = ?', [$month, $tahun])->where('status_debitur','=',6)->get();
                $dtsolicit[]      = count(DataDebitur::with('statusdebitur')->whereRaw('extract(month from created_at) = ? AND extract(year from created_at) = ?', [$month, $tahun])->where('status_debitur','>=',1)->get());
                $dtsolicitapp[]   = count(DataDebitur::with('statusdebitur')->whereRaw('extract(month from tanggal_approve) = ? AND extract(year from tanggal_approve) = ?', [$month, $tahun])->where('status_debitur','>=',1)->get());
                $dtprospect[]     = count(DataDebitur::with('statusdebitur')->whereRaw('extract(month from tanggal_app_prospek) = ? AND extract(year from tanggal_app_prospek) = ?', [$month, $tahun])->where('status_debitur','>=',1)->get());
                $dtclose[]        = count(DataDebitur::with('statusdebitur')->whereRaw('extract(month from tanggal_update_pipeline) = ? AND extract(year from tanggal_update_pipeline) = ?', [$month, $tahun])->where('status_debitur','>=',1)->get());
                $dtreject[]       = count(DataDebitur::with('statusdebitur')->whereRaw('extract(month from tanggal_penolakan) = ? AND extract(year from tanggal_penolakan) = ?', [$month, $tahun])->where('status_debitur','<',1)->get());

                if(count($dataclosed)>0)
                {
                    $nominalcair = 0;
                    foreach($dataclosed as $item)
                    {
                        $num = (float) str_replace(',', '.', str_replace('.', '', $item->nominal_cair));
                        $nominalcair += $num;
                    }
                    $danacair[] = (int) $nominalcair;
                }
                else
                {
                    $danacair[] = 0;
                }
            }
        }

        $datasektor = array();
        if(count($jumlahsektor)>0)
        {
            foreach($jumlahsektor as $index=>$item)
            {
                $datasektor[]=(object) array('name' => $index, 'y' => $item);
            }
        }
        else
        {
            $datasektor[]=(object) array('name' => 'Belum Ada Data', 'y' => 0);
        }
        return view('admin/dashboard/index', [
            'user'              => $user,
            'tahun'             => $tahun,
            'verifsolicit'      => $verifsolicit,
            'appsolicit'        => $appsolicit,
            'needprospek'       => $needprospek,
            'appprospek'        => $appprospek,
            'needpipeline'      => $needpipeline,
            'pengumuman'        => $pengumuman,
            'danacair'          => $danacair,
            'dtsolicit'         => $dtsolicit,
            'dtsolicitapp'      => $dtsolicitapp,
            'dtprospect'        => $dtprospect,
            'dtclose'           => $dtclose,
            'dtreject'          => $dtreject,

            'jumlahsolicit'     => $jumlahsolicit,
            'jumlahprospek'     => $jumlahprospek,
            'jumlahpipeline'    => $jumlahpipeline,
            'jumlahclose'       => $jumlahclose,
            'jumlahreject'      => $jumlahreject,
            'datasektor'        => $datasektor,

            'DCabang'   => $DCabang,
            'DTim'      => $DTim,
            'DRoles'    => $DRoles,
            'cabang'    => $cabang,
            'tim'       => $tim,
            'role'      => $role,
            'startd'    => $startd,
            'endd'      => $endd,
        ]);
    }
}
