<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Cabang;
use App\Models\Vendor;
use Ajifatur\FaturHelper\Models\UserAttribute;

class AdminVendorController extends Controller
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

        // Get admin vendor
        $admin_vendor = User::where('role_id','=',role('admin-vendor'))->get();

        // View
        return view('admin/admin-vendor/index', [
            'admin_vendor' => $admin_vendor
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

        // Get vendor
        $vendor = Vendor::orderBy('nama','asc')->get();

        // View
        return view('admin/admin-vendor/create', [
            'vendor' => $vendor
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
            'vendor' => 'required',
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
            // Save the admin vendor
            $admin_vendor = new User;
            $admin_vendor->role_id = role('admin-vendor');
            $admin_vendor->name = $request->nama;
            $admin_vendor->email = $admin_vendor->email;
            $admin_vendor->email = $request->email;
            $admin_vendor->username = $request->username;
            $admin_vendor->email_verified_at = null;
            $admin_vendor->password = bcrypt($request->password);
            $admin_vendor->remember_token = null;
            $admin_vendor->access_token = access_token();
            $admin_vendor->avatar = null;
            $admin_vendor->status = 1;
            $admin_vendor->last_visit = null;
            $admin_vendor->save();

            // Save the admin vendor attribute
            $admin_vendor_attribute = new UserAttribute;
            $admin_vendor_attribute->user_id = $admin_vendor->id;
            $admin_vendor_attribute->vendor_id = $request->vendor;
            $admin_vendor_attribute->npp = $request->npp;
            $admin_vendor_attribute->save();

            // Redirect
            return redirect()->route('admin.admin-vendor.index')->with(['message' => 'Berhasil menambah data.']);
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

        // Get the admin vendor
        $admin_vendor = User::findOrFail($id);
        
        // Get vendor
        $vendor = Vendor::orderBy('nama','asc')->get();

        // View
        return view('admin/admin-vendor/edit', [
            'admin_vendor' => $admin_vendor,
            'vendor' => $vendor
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
            'vendor' => 'required',
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
            // Update the admin vendor
            $admin_vendor = User::find($request->id);
            $admin_vendor->name = $request->nama;
            $admin_vendor->email = $request->email;
            $admin_vendor->username = $request->username;
            $admin_vendor->password = $request->password != '' ? bcrypt($request->password) : $admin_vendor->password;
            $admin_vendor->save();

            // Update the admin vendor attribute
            $admin_vendor_attribute = UserAttribute::where('user_id','=',$admin_vendor->id)->first();
            $admin_vendor_attribute->vendor_id = $request->vendor;
            $admin_vendor_attribute->npp = $request->npp;
            $admin_vendor_attribute->save();

            // Redirect
            return redirect()->route('admin.admin-vendor.index')->with(['message' => 'Berhasil mengupdate data.']);
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
        
        // Get the admin vendor
        $admin_vendor = User::find($request->id);

        // Delete the admin vendor
        $admin_vendor->delete();

        // Get the admin vendor attribute
        $admin_vendor_attribute = UserAttribute::where('user_id','=',$admin_vendor->id)->first();

        // Delete the admin vendor attribute
        $admin_vendor_attribute->delete();

        // Redirect
        return redirect()->route('admin.admin-vendor.index')->with(['message' => 'Berhasil menghapus data.']);
    }
}
