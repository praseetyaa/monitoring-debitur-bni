<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Cabang;
use Ajifatur\FaturHelper\Models\UserAttribute;

class AdminCabangController extends Controller
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

        // Get admin cabang
        $admin_cabang = User::where('role_id','=',role('admin-cabang'))->get();

        // View
        return view('admin/admin-cabang/index', [
            'admin_cabang' => $admin_cabang
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

        // View
        return view('admin/admin-cabang/create', [
            'cabang' => $cabang
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
        // Validation
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'npp' => 'required',
            'cabang' => 'required',
            // 'email' => 'required|email|unique:users',
            'username' => 'required|alpha_dash|min:4|unique:users',
            'password' => 'required|min:6',
        ]);
        
        // Check errors
        if($validator->fails()) {
            // Back to form page with validation error messages
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        else {
            // Save the admin cabang
            $admin_cabang = new User;
            $admin_cabang->role_id = role('admin-cabang');
            $admin_cabang->name = $request->nama;
            $admin_cabang->email = $admin_cabang->email;
            $admin_cabang->email = $request->email;
            $admin_cabang->username = $request->username;
            $admin_cabang->email_verified_at = null;
            $admin_cabang->password = bcrypt($request->password);
            $admin_cabang->remember_token = null;
            $admin_cabang->access_token = access_token();
            $admin_cabang->avatar = null;
            $admin_cabang->status = 1;
            $admin_cabang->last_visit = null;
            $admin_cabang->save();

            // Save the admin cabang attribute
            $admin_cabang_attribute = new UserAttribute;
            $admin_cabang_attribute->user_id = $admin_cabang->id;
            $admin_cabang_attribute->cabang_id = $request->cabang;
            $admin_cabang_attribute->npp = $request->npp;
            $admin_cabang_attribute->save();

            // Redirect
            return redirect()->route('admin.admin-cabang.index')->with(['message' => 'Berhasil menambah data.']);
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
        $admin_cabang = User::findOrFail($id);
        
        // Get cabang
        $cabang = Cabang::orderBy('nama','asc')->get();

        // View
        return view('admin/admin-cabang/edit', [
            'admin_cabang' => $admin_cabang,
            'cabang' => $cabang
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
        // Validation
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'npp' => 'required',
            'cabang' => 'required',
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
            $admin_cabang = User::find($request->id);
            $admin_cabang->name = $request->nama;
            $admin_cabang->email = $request->email;
            $admin_cabang->username = $request->username;
            $admin_cabang->password = $request->password != '' ? bcrypt($request->password) : $admin_cabang->password;
            $admin_cabang->save();

            // Update the admin cabang attribute
            $admin_cabang_attribute = UserAttribute::where('user_id','=',$admin_cabang->id)->first();
            $admin_cabang_attribute->cabang_id = $request->cabang;
            $admin_cabang_attribute->npp = $request->npp;
            $admin_cabang_attribute->save();

            // Redirect
            return redirect()->route('admin.admin-cabang.index')->with(['message' => 'Berhasil mengupdate data.']);
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
        $admin_cabang = User::find($request->id);

        // Delete the admin cabang
        $admin_cabang->delete();

        // Get the admin cabang attribute
        $admin_cabang_attribute = UserAttribute::where('user_id','=',$admin_cabang->id)->first();

        // Delete the admin cabang attribute
        $admin_cabang_attribute->delete();

        // Redirect
        return redirect()->route('admin.admin-cabang.index')->with(['message' => 'Berhasil menghapus data.']);
    }
}
