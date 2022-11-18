<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ATMImport;
use App\Models\ATM;
use App\Models\Cabang;
use App\Models\Vendor;

class ATMController extends Controller
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

        // Get ATM
        $atm = ATM::has('cabang')->get();

        // View
        return view('admin/atm/index', [
            'atm' => $atm
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

        // Get vendor
        $vendor = Vendor::orderBy('nama','asc')->get();

        // View
        return view('admin/atm/create', [
            'cabang' => $cabang,
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
            'cabang' => 'required',
            'vendor' => 'required',
        ]);
        
        // Check errors
        if($validator->fails()) {
            // Back to form page with validation error messages
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        else {
            // Save the ATM
            $atm = new ATM;
            $atm->cabang_id = $request->cabang;
            $atm->vendor_id = $request->vendor;
            $atm->id_atm = $request->id_atm;
            $atm->nama = $request->nama;
            // $atm->alamat = $request->alamat;
            $atm->save();

            // Redirect
            return redirect()->route('admin.atm.index')->with(['message' => 'Berhasil menambah data.']);
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

        // Get the ATM
        $atm = ATM::findOrFail($id);
        
        // Get cabang
        $cabang = Cabang::orderBy('nama','asc')->get();

        // Get vendor
        $vendor = Vendor::orderBy('nama','asc')->get();

        // View
        return view('admin/atm/edit', [
            'atm' => $atm,
            'cabang' => $cabang,
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
            'cabang' => 'required',
            'vendor' => 'required',
        ]);
        
        // Check errors
        if($validator->fails()) {
            // Back to form page with validation error messages
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        else {
            // Update the ATM
            $atm = ATM::find($request->id);
            $atm->cabang_id = $request->cabang;
            $atm->vendor_id = $request->vendor;
            $atm->id_atm = $request->id_atm;
            $atm->nama = $request->nama;
            // $atm->alamat = $request->alamat;
            $atm->save();

            // Redirect
            return redirect()->route('admin.atm.index')->with(['message' => 'Berhasil mengupdate data.']);
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
        
        // Get the ATM
        $atm = ATM::find($request->id);

        // Delete the ATM
        $atm->delete();

        // Redirect
        return redirect()->route('admin.atm.index')->with(['message' => 'Berhasil menghapus data.']);
    }
 
    /**
     * Import from Excel
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
	public function import(Request $request) 
	{
        // Ini set
        ini_set('max_execution_time', 600);

        // Get rows from array
        $rows = Excel::toArray(new ATMImport, public_path('excel/ATM.xlsx'));

        if(count($rows) > 0) {
            foreach($rows[0] as $cells) {
                // Update or create the cabang
                $cabang = Cabang::where('nama','=',$cells[2])->first();
                if(!$cabang) $cabang = new Cabang;
                $cabang->nama = $cells[2];
                $cabang->save();

                // Update or create the vendor
                $vendor = Vendor::where('nama','=',$cells[4])->first();
                if(!$vendor) $vendor = new Vendor;
                $vendor->nama = $cells[4];
                $vendor->save();

                // Update or create the ATM
                $atm = ATM::where('id_atm','=',$cells[1])->first();
                if(!$atm) $atm = new ATM;
                $atm->cabang_id = $cabang->id;
                $atm->vendor_id = $vendor->id;
                $atm->id_atm = $cells[1];
                $atm->nama = $cells[3];
                $atm->save();
            }
        }
    }
}
