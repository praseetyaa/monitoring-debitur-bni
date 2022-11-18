<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Ajifatur\Helpers\DateTimeExt;
use App\Models\Cabang;
use App\Models\Kategori;
use App\Models\MonitoringDetail;

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
        // Check the access
        // has_access(method(__METHOD__), Auth::user()->role_id);

        $t1 = $request->query('t1') != null ? DateTimeExt::change($request->query('t1')) : date('Y-m-d');
        $t2 = $request->query('t2') != null ? DateTimeExt::change($request->query('t2')) : date('Y-m-d');
        $cabang_id = $request->query('cabang');
        $cabang = Cabang::orderBy('nama','asc')->get();
        $is_global = Auth::user()->role->is_global;
        $user_cabang = Auth::user()->attribute ? Auth::user()->attribute->cabang_id : 0;
        $user_vendor = Auth::user()->attribute ? Auth::user()->attribute->vendor_id : 0;

        // Get kategori interior
        $interior = Kategori::where('jenis_id','=',1)->get();
        foreach($interior as $key=>$i) {
            // Count
            $count = MonitoringDetail::whereHas('monitoring', function (Builder $query) use ($is_global, $user_cabang, $user_vendor, $cabang_id, $t1, $t2) {
                if($is_global == 1) {
                    if($cabang_id != 0)
                        return $query->has('user')->where('cabang_id','=',$cabang_id)->whereDate('created_at','>=',$t1)->whereDate('created_at','<=',$t2);
                    else
                        return $query->has('user')->whereDate('created_at','>=',$t1)->whereDate('created_at','<=',$t2);
                }
                else {
                    if($user_cabang != 0)
                        return $query->has('user')->where('cabang_id','=',$user_cabang)->whereDate('created_at','>=',$t1)->whereDate('created_at','<=',$t2);
                    elseif($user_vendor != 0)
                        return $query->has('user')->where('vendor_id','=',$user_vendor)->whereDate('created_at','>=',$t1)->whereDate('created_at','<=',$t2);
                }
            })->has('kategori')->where('kategori_id','=',$i->id)->where('jawaban','=',$i->is_reverse)->count();
            $interior[$key]->count = $count;
        }

        // Get kategori eksterior
        $eksterior = Kategori::where('jenis_id','=',2)->get();
        foreach($eksterior as $key=>$e) {
            // Count
            $count = MonitoringDetail::whereHas('monitoring', function (Builder $query) use ($is_global, $user_cabang, $user_vendor, $cabang_id, $t1, $t2) {
                if($is_global == 1) {
                    if($cabang_id != 0)
                        return $query->has('user')->where('cabang_id','=',$cabang_id)->whereDate('created_at','>=',$t1)->whereDate('created_at','<=',$t2);
                    else
                        return $query->has('user')->whereDate('created_at','>=',$t1)->whereDate('created_at','<=',$t2);
                }
                else {
                    if($user_cabang != 0)
                        return $query->has('user')->where('cabang_id','=',$user_cabang)->whereDate('created_at','>=',$t1)->whereDate('created_at','<=',$t2);
                    elseif($user_vendor != 0)
                        return $query->has('user')->where('vendor_id','=',$user_vendor)->whereDate('created_at','>=',$t1)->whereDate('created_at','<=',$t2);
                }
            })->has('kategori')->where('kategori_id','=',$e->id)->where('jawaban','=',$e->is_reverse)->count();
            $eksterior[$key]->count = $count;
        }

        // View
        return view('admin/dashboard/index', [
            'cabang' => $cabang,
            't1' => $t1,
            't2' => $t2,
            'interior' => $interior,
            'eksterior' => $eksterior
        ]);
    }
}
