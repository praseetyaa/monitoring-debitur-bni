<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Unit;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Models\User;

class MonitoringController extends Controller
{
    public function index($cabang ='', $unit ='', $role ='')
    {
        $DCabang = Cabang::get();
        $DUnit = Unit::get();
        $DRoles = Role::whereIn('id', [3,4,6])->get();

        $user    = User::with('role', 'attribute.cabang', 'attribute.jabatan', 'attribute.unit')
                ->whereIn('role_id', [3,4,6])
                ->withCount('datainput')
                ->withCount('dataverif')
                ->withCount('dataapp')
                ->withCount('dataapppros')
                ->withCount('totalpipeline')
                ->when($cabang !== '' && $cabang !== 'null', function($query) use($cabang){
                    $query->whereRelation('attribute.cabang', 'id' ,$cabang);
                })
                ->when($unit !== '' && $unit !== 'null', function($query) use($unit){
                    $query->whereRelation('attribute.unit', 'id' ,$unit);
                })
                ->when($role !== '' && $role !== 'null', function($query) use($role){
                    $query->where('role_id' ,$role);
                })
                ->get();
                // dd($user);
        return view('monitoring/monitoring',[
            'user'      => $user,
            'DCabang'   => $DCabang,
            'DUnit'     => $DUnit,
            'DRoles'    => $DRoles,
            'cabang'    => $cabang,
            'unit'      => $unit,
            'role'      => $role,
        ]);
    }

}
