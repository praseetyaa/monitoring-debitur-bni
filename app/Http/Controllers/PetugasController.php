<?php

namespace App\Http\Controllers;

use Auth;
use File;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Ajifatur\FaturHelper\Models\UserAvatar;
use App\Models\User;
use App\Models\UserAttribute;
use App\Models\Cabang;
use App\Models\Vendor;
use App\Models\Tipe;

class PetugasController extends Controller
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

        // Get petugas
        if(Auth::user()->role_id == role('super-admin') || Auth::user()->role_id == role('admin-wilayah')) {
            $petugas = User::where('role_id','=',role('petugas'))->get();
        }
        elseif(Auth::user()->role->is_global == 0) {
            if(Auth::user()->role_id == role('admin-cabang')){
                $cabang = Auth::user()->attribute->cabang_id;
                $petugas = User::where('role_id','=',role('petugas'))->whereHas('attribute', function (Builder $query) use ($cabang) {
                    return $query->where('cabang_id','=',$cabang);
                })->get();
            }
            elseif(Auth::user()->role_id == role('admin-vendor')){
                $vendor = Auth::user()->attribute->vendor_id;
                $petugas = User::where('role_id','=',role('petugas'))->whereHas('attribute', function (Builder $query) use ($vendor) {
                    return $query->where('vendor_id','=',$vendor);
                })->get();
            }
        }

        // View
        return view('admin/petugas/index', [
            'petugas' => $petugas
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

        // Get tipe
        $tipe = Tipe::all();

        // Get cabang
        $cabang = Cabang::orderBy('nama','asc')->get();

        // View
        return view('admin/petugas/create', [
            'tipe' => $tipe,
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
            'vendor' => 'required',
            'cabang' => Auth::user()->role_id == role('admin-cabang') ? '' : 'required',
            'username' => 'required|alpha_dash|min:4|unique:users',
            'password' => 'required|min:6',
        ]);
        
        // Check errors
        if($validator->fails()) {
            // Back to form page with validation error messages
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        else {
            // Save the petugas
            $petugas = new User;
            $petugas->role_id = role('petugas');
            $petugas->name = $request->nama;
            $petugas->username = $request->username;
            $petugas->email = null;
            $petugas->email_verified_at = null;
            $petugas->password = bcrypt($request->password);
            $petugas->remember_token = null;
            $petugas->access_token = access_token();
            $petugas->avatar = null;
            $petugas->status = 1;
            $petugas->last_visit = null;
            $petugas->save();

            // Get the vendor
            $vendor = Vendor::find($request->vendor);

            // Save the petugas attribute
            $petugas_attribute = new UserAttribute;
            $petugas_attribute->user_id = $petugas->id;
            $petugas_attribute->cabang_id = Auth::user()->role_id == role('admin-cabang') ? Auth::user()->attribute->cabang_id : $request->cabang;
            $petugas_attribute->vendor_id = $request->vendor_id;
            $petugas_attribute->tipe_id = $request->tipe_id;
            $petugas_attribute->save();
            
            // Upload the image
            if($request->photo_source != '') {
                $image = $request->photo_source;
                $image = str_replace('data:image/png;base64,', '', $image);
                $image = str_replace(' ', '+', $image);
                $imageName = date('Y-m-d-H-i-s').'.'.'png';
                File::put(public_path('assets/images/users'). '/' . $imageName, base64_decode($image));

                // Update the petugas avatar
                $petugas->avatar = $imageName;
                $petugas->save();

                // Save petugas avatar
                $petugas_avatar = new UserAvatar;
                $petugas_avatar->user_id = $petugas->id;
                $petugas_avatar->avatar = $petugas->avatar;
                $petugas_avatar->save();
            }

            // Redirect
            return redirect()->route('admin.petugas.index')->with(['message' => 'Berhasil menambah data.']);
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

        // Get the petugas
        $petugas = User::findOrFail($id);

        // Get tipe
        $tipe = Tipe::all();

        // Get cabang
        $cabang = Cabang::orderBy('nama','asc')->get();

        // View
        return view('admin/petugas/edit', [
            'petugas' => $petugas,
            'tipe' => $tipe,
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
            'vendor' => 'required',
            'cabang' => Auth::user()->role_id == role('admin-cabang') ? '' : 'required',
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
            // Update the petugas
            $petugas = User::find($request->id);
            $petugas->name = $request->nama;
            $petugas->username = $request->username;
            $petugas->password = $request->password != '' ? bcrypt($request->password) : $petugas->password;
            $petugas->save();

            // Get the vendor
            $vendor = Vendor::find($request->vendor);

            // Update the petugas attribute
            $petugas_attribute = UserAttribute::where('user_id','=',$petugas->id)->first();
            $petugas_attribute->vendor_id = $request->vendor_id;
            $petugas_attribute->tipe_id = $request->tipe_id;
            $petugas_attribute->cabang_id = Auth::user()->role_id == role('admin-cabang') ? Auth::user()->attribute->cabang_id : $request->cabang;
            $petugas_attribute->save();
            
            // Upload the image
            if($request->photo_source != '') {
                $image = $request->photo_source;
                $image = str_replace('data:image/png;base64,', '', $image);
                $image = str_replace(' ', '+', $image);
                $imageName = date('Y-m-d-H-i-s').'.'.'png';
                File::put(public_path('assets/images/users'). '/' . $imageName, base64_decode($image));

                // Update the petugas avatar
                $petugas->avatar = $imageName;
                $petugas->save();

                // Save petugas avatar
                $petugas_avatar = new UserAvatar;
                $petugas_avatar->user_id = $petugas->id;
                $petugas_avatar->avatar = $petugas->avatar;
                $petugas_avatar->save();
            }

            // Redirect
            return redirect()->route('admin.petugas.index')->with(['message' => 'Berhasil mengupdate data.']);
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
        
        // Get the petugas
        $petugas = User::find($request->id);

        // Delete the petugas
        $petugas->delete();

        // Get the petugas attribute
        $petugas_attribute = UserAttribute::where('user_id','=',$petugas->id)->first();

        // Delete the petugas attribute
        $petugas_attribute->delete();

        // Redirect
        return redirect()->route('admin.petugas.index')->with(['message' => 'Berhasil menghapus data.']);
    }

    /**
     * Remove the selected resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteBulk(Request $request)
    {
        // Check the access
        // has_access(method(__METHOD__), Auth::user()->role_id);

        // Explode ID
        $ids = explode(',', $request->ids);

        foreach($ids as $id) {
            // Get the petugas
            $petugas = User::find($id);
    
            // Delete the petugas
            $petugas->delete();
    
            // Get the petugas attribute
            $petugas_attribute = UserAttribute::where('user_id','=',$petugas->id)->first();
    
            // Delete the petugas attribute
            $petugas_attribute->delete();
        }
        
        // Redirect
        return redirect()->route('admin.petugas.index')->with(['message' => 'Berhasil menghapus data.']);
    }
}
