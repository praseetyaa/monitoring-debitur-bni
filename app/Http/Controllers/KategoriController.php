<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Kategori;
use App\Models\Jenis;
use App\Models\Tipe;

class KategoriController extends Controller
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

        // Get kategori
        $kategori = Kategori::has('jenis')->get();

        // Get tipe
        $tipe = Tipe::all();

        // View
        return view('admin/kategori/index', [
            'kategori' => $kategori,
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

        // Get jenis
        $jenis = Jenis::get();

        // View
        return view('admin/kategori/create', [
            'jenis' => $jenis
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
            'awal' => 'required',
            'akhir' => 'required',
            'opsi_ya' => 'required',
            'opsi_tidak' => 'required',
            'is_reverse' => 'required',
            'jenis' => 'required',
        ]);
        
        // Check errors
        if($validator->fails()) {
            // Back to form page with validation error messages
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        else {
            // Save the kategori
            $kategori = new Kategori;
            $kategori->jenis_id = $request->jenis;
            $kategori->awal = $request->awal;
            $kategori->akhir = $request->akhir;
            $kategori->opsi_ya = $request->opsi_ya;
            $kategori->opsi_tidak = $request->opsi_tidak;
            $kategori->is_reverse = $request->is_reverse;
            $kategori->contoh = $request->contoh;
            $kategori->save();

            // Redirect
            return redirect()->route('admin.kategori.index')->with(['message' => 'Berhasil menambah data.']);
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

        // Get the kategori
        $kategori = Kategori::findOrFail($id);
        
        // Get jenis
        $jenis = Jenis::get();

        // View
        return view('admin/kategori/edit', [
            'kategori' => $kategori,
            'jenis' => $jenis
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
            'awal' => 'required',
            'akhir' => 'required',
            'opsi_ya' => 'required',
            'opsi_tidak' => 'required',
            'is_reverse' => 'required',
            'jenis' => 'required',
        ]);
        
        // Check errors
        if($validator->fails()) {
            // Back to form page with validation error messages
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        else {
            // Update the kategori
            $kategori = Kategori::find($request->id);
            $kategori->jenis_id = $request->jenis;
            $kategori->awal = $request->awal;
            $kategori->akhir = $request->akhir;
            $kategori->opsi_ya = $request->opsi_ya;
            $kategori->opsi_tidak = $request->opsi_tidak;
            $kategori->is_reverse = $request->is_reverse;
            $kategori->contoh = $request->contoh;
            $kategori->save();

            // Redirect
            return redirect()->route('admin.kategori.index')->with(['message' => 'Berhasil mengupdate data.']);
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
        
        // Get the kategori
        $kategori = Kategori::find($request->id);

        // Delete the kategori
        $kategori->delete();

        // Redirect
        return redirect()->route('admin.kategori.index')->with(['message' => 'Berhasil menghapus data.']);
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

        // Get the kategori
        $kategori = Kategori::find($request->kategori);

        // Change tipe
        if($kategori) {
            $kategori->tipe()->toggle($request->tipe);
            echo 'Berhasil mengganti tipe kategori.';
        }
        else {
            echo 'Terjadi kesalahan dalam mengganti tipe kategori.';
        }
    }
}
