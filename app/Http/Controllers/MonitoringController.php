<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Models\User;

class MonitoringController extends Controller
{
    public function index($cabang ='', $role = '')
    {
        $DCabang = Cabang::get();
        $DRoles = Role::whereIn('id', [3,4,6])->get();

        $user    = User::with('role', 'attribute.cabang', 'attribute.jabatan')
                ->whereIn('role_id', [3,4,6])
                ->withCount('datainput')
                ->withCount('dataverif')
                ->withCount('dataapp')
                ->withCount('dataapppros')
                ->withCount('totalpipeline')
                ->when($cabang !== '' && $cabang !== 'null', function($query) use($cabang){
                    $query->whereRelation('attribute.cabang', 'id' ,$cabang);
                })
                ->when($role !== '' && $role !== 'null', function($query) use($role){
                    $query->where('role_id' ,$role);
                })
                ->get();
                // dd($user);
        return view('monitoring/monitoring',[
            'user'      => $user,
            'DCabang'   => $DCabang,
            'DRoles'    => $DRoles,
            'cabang'    => $cabang,
            'role'      => $role,
        ]);
    }

}
