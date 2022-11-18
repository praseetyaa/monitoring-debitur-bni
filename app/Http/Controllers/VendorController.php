<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Vendor;
use App\Models\Tipe;

class VendorController extends Controller
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

        // Get vendor
        $vendor = Vendor::orderBy('nama','asc')->get();

        // Get tipe
        $tipe = Tipe::all();

        // View
        return view('admin/vendor/index', [
            'vendor' => $vendor,
            'tipe' => $tipe
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

        // View
        return view('admin/vendor/create');
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
        ]);
        
        // Check errors
        if($validator->fails()) {
            // Back to form page with validation error messages
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        else {
            // Save the vendor
            $vendor = new Vendor;
            $vendor->nama = $request->nama;
            $vendor->save();

            // Redirect
            return redirect()->route('admin.vendor.index')->with(['message' => 'Berhasil menambah data.']);
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

        // Get the vendor
        $vendor = Vendor::findOrFail($id);

        // View
        return view('admin/vendor/edit', [
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
        ]);
        
        // Check errors
        if($validator->fails()) {
            // Back to form page with validation error messages
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        else {
            // Update the vendor
            $vendor = Vendor::find($request->id);
            $vendor->nama = $request->nama;
            $vendor->save();

            // Redirect
            return redirect()->route('admin.vendor.index')->with(['message' => 'Berhasil mengupdate data.']);
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
        
        // Get the vendor
        $vendor = Vendor::find($request->id);

        // Delete the vendor
        $vendor->delete();

        // Redirect
        return redirect()->route('admin.vendor.index')->with(['message' => 'Berhasil menghapus data.']);
    }

    /**
     * Change the resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function change(Request $request)
    {
        // Check the access
        // has_access(method(__METHOD__), Auth::user()->role_id);

        // Get the vendor
        $vendor = Vendor::find($request->vendor);

        // Change tipe
        if($vendor) {
            $vendor->tipe()->toggle($request->tipe);
            echo 'Berhasil mengganti tipe vendor.';
        }
        else {
            echo 'Terjadi kesalahan dalam mengganti tipe vendor.';
        }
    }
}
