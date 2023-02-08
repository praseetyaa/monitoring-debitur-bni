<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Cabang;
use Ajifatur\FaturHelper\Models\UserAttribute;
use App\Models\Jabatan;

class MstUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $role = array_reverse(explode('/', $request->fullUrl()));
        $role = str_replace('pic', '', $role[0]);
        $user = User::with('role', 'attribute.cabang', 'attribute.jabatan')->where('role_id','=',role($role))->get();
        return view('master/mstuser/mstuser', [
            'user'  => $user,
            'role'  => $role
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Check the access
        // has_access(method(__METHOD__), Auth::user()->role_id);

        // Get cabang
        $cabang = Cabang::orderBy('nama','asc')->get();
        $jabatan = Jabatan::orderBy('nama','asc')->get();

        // View
        return view('master/mstuser/mstusercreate', [
            'cabang' => $cabang,
            'jabatan' => $jabatan,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'npp' => 'required|alpha_dash|unique:user_attributes',
            'phone_number' => 'required',
            'cabang' => 'required',
            'jabatan' => 'required',

            'email' => 'required|email|unique:users',
            'username' => 'required|alpha_dash|min:4|unique:users',
            'password' => 'required|min:6',
        ]);

        if($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        else {
            $pengguna = new User;
            $pengguna->role_id = role($request->route);
            $pengguna->name = $request->nama;
            $pengguna->email = $request->email;
            $pengguna->username = $request->username;
            $pengguna->email_verified_at = null;
            $pengguna->password = bcrypt($request->password);
            $pengguna->remember_token = null;
            $pengguna->access_token = access_token();
            $pengguna->avatar = null;
            $pengguna->status = 1;
            $pengguna->last_visit = null;
            $pengguna->save();

            $pengguna_attribute = new UserAttribute;
            $pengguna_attribute->user_id = $pengguna->id;
            $pengguna_attribute->npp = $request->npp;
            $pengguna_attribute->phone_number = $request->phone_number;
            $pengguna_attribute->cabang_id = $request->cabang;
            $pengguna_attribute->jabatan_id = $request->jabatan;
            $pengguna_attribute->save();

            return redirect()->route('pic'.$request->route)->with(['message' => 'Berhasil menambah data.']);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Check the access
        // has_access(method(__METHOD__), Auth::user()->role_id);

        // Get the admin cabang
        $pengguna = User::with('role')->findOrFail($id);

        // Get cabang
        $cabang = Cabang::orderBy('nama','asc')->get();
        $jabatan = Jabatan::orderBy('nama','asc')->get();
        // View
        return view('master/mstuser/mstuseredit', [
            'pengguna' => $pengguna,
            'cabang' => $cabang,
            'jabatan' => $jabatan,
            'role' => $pengguna->role->code,
        ]);
}

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
        // Validation
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'npp' => [
                'required', 'alpha_dash', Rule::unique('user_attributes')->ignore($request->id, 'user_id')
            ],
            'phone_number' => 'required',
            'cabang' => 'required',
            'jabatan' => 'required',
            'email' => [
                'required', 'email', Rule::unique('users')->ignore($request->id, 'id')
            ],
            'username' => [
                'required', 'alpha_dash', 'min:4', Rule::unique('users')->ignore($request->id, 'id')
            ],
            'password' => $request->password != '' ? 'required|min:6' : '',
        ]);
        // Check errors
        if($validator->fails()) {
            // Back to form page with validation error messages
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        else {

            // Update the admin cabang
            $pengguna = User::find($request->id);
            $pengguna->name = $request->nama;
            $pengguna->email = $request->email;
            $pengguna->username = $request->username;
            $pengguna->password = $request->password != '' ? bcrypt($request->password) : $pengguna->password;
            $pengguna->save();

            // Update the admin cabang attribute
            $pengguna_attribute = UserAttribute::where('user_id','=',$pengguna->id)->first();
            $pengguna_attribute->npp = $request->npp;
            $pengguna_attribute->phone_number = $request->phone_number;
            $pengguna_attribute->cabang_id = $request->cabang;
            $pengguna_attribute->jabatan_id = $request->jabatan;
            $pengguna_attribute->save();

            // Redirect
            return redirect()->route('pic'.$request->route)->with(['message' => 'Berhasil mengupdate data.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        // Check the access
        // has_access(method(__METHOD__), Auth::user()->role_id);

        // Get the admin cabang
        $pengguna = User::find($request->id);

        // Delete the admin cabang
        $pengguna->delete();

        // Get the admin cabang attribute
        $pengguna_attribute = UserAttribute::where('user_id','=',$pengguna->id)->first();

        // Delete the admin cabang attribute
        $pengguna_attribute->delete();

        // // Redirect
        return redirect()->route($request->routename)->with(['message' => 'Berhasil menghapus data.']);
    }
}
