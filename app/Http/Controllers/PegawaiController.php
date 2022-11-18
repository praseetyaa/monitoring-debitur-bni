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

class PegawaiController extends Controller
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

        // Get pegawai
        if(Auth::user()->role_id == role('super-admin') || Auth::user()->role_id == role('admin-wilayah')) {
            $pegawai = User::where('role_id','=',role('pegawai'))->get();
        }
        elseif(Auth::user()->role_id == role('admin-cabang')) {
            $cabang = Auth::user()->attribute->cabang_id;
            $pegawai = User::where('role_id','=',role('pegawai'))->whereHas('attribute', function (Builder $query) use ($cabang) {
                return $query->where('cabang_id','=',$cabang);
            })->get();
        }

        // View
        return view('admin/pegawai/index', [
            'pegawai' => $pegawai
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
        return view('admin/pegawai/create', [
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
            // Save the pegawai
            $pegawai = new User;
            $pegawai->role_id = role('pegawai');
            $pegawai->name = $request->nama;
            $pegawai->username = $request->username;
            $pegawai->email = null;
            $pegawai->email_verified_at = null;
            $pegawai->password = bcrypt($request->password);
            $pegawai->remember_token = null;
            $pegawai->access_token = access_token();
            $pegawai->avatar = null;
            $pegawai->status = 1;
            $pegawai->last_visit = null;
            $pegawai->save();

            // Save the pegawai attribute
            $pegawai_attribute = new UserAttribute;
            $pegawai_attribute->user_id = $pegawai->id;
            $pegawai_attribute->cabang_id = Auth::user()->role_id == role('admin-cabang') ? Auth::user()->attribute->cabang_id : $request->cabang;
            $pegawai_attribute->npp = $request->npp;
            $pegawai_attribute->save();

            // Upload the image
            if($request->photo_source != '') {
                $image = $request->photo_source;
                $image = str_replace('data:image/png;base64,', '', $image);
                $image = str_replace(' ', '+', $image);
                $imageName = date('Y-m-d-H-i-s').'.'.'png';
                File::put(public_path('assets/images/users'). '/' . $imageName, base64_decode($image));

                // Update the pegawai avatar
                $pegawai->avatar = $imageName;
                $pegawai->save();

                // Save pegawai avatar
                $pegawai_avatar = new UserAvatar;
                $pegawai_avatar->user_id = $pegawai->id;
                $pegawai_avatar->avatar = $pegawai->avatar;
                $pegawai_avatar->save();
            }

            // Redirect
            return redirect()->route('admin.pegawai.index')->with(['message' => 'Berhasil menambah data.']);
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

        // Get the pegawai
        $pegawai = User::findOrFail($id);
        
        // Get cabang
        $cabang = Cabang::orderBy('nama','asc')->get();

        // View
        return view('admin/pegawai/edit', [
            'pegawai' => $pegawai,
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
            // Update the pegawai
            $pegawai = User::find($request->id);
            $pegawai->name = $request->nama;
            $pegawai->username = $request->username;
            $pegawai->password = $request->password != '' ? bcrypt($request->password) : $pegawai->password;
            $pegawai->save();

            // Update the pegawai attribute
            $pegawai_attribute = UserAttribute::where('user_id','=',$pegawai->id)->first();
            $pegawai_attribute->npp = $request->npp;
            $pegawai_attribute->cabang_id = Auth::user()->role_id == role('admin-cabang') ? Auth::user()->attribute->cabang_id : $request->cabang;
            $pegawai_attribute->save();
            
            // Upload the image
            if($request->photo_source != '') {
                $image = $request->photo_source;
                $image = str_replace('data:image/png;base64,', '', $image);
                $image = str_replace(' ', '+', $image);
                $imageName = date('Y-m-d-H-i-s').'.'.'png';
                File::put(public_path('assets/images/users'). '/' . $imageName, base64_decode($image));

                // Update the pegawai avatar
                $pegawai->avatar = $imageName;
                $pegawai->save();

                // Save pegawai avatar
                $pegawai_avatar = new UserAvatar;
                $pegawai_avatar->user_id = $pegawai->id;
                $pegawai_avatar->avatar = $pegawai->avatar;
                $pegawai_avatar->save();
            }

            // Redirect
            return redirect()->route('admin.pegawai.index')->with(['message' => 'Berhasil mengupdate data.']);
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
        
        // Get the pegawai
        $pegawai = User::find($request->id);

        // Delete the pegawai
        $pegawai->delete();

        // Get the pegawai attribute
        $pegawai_attribute = UserAttribute::where('user_id','=',$pegawai->id)->first();

        // Delete the pegawai attribute
        $pegawai_attribute->delete();

        // Redirect
        return redirect()->route('admin.pegawai.index')->with(['message' => 'Berhasil menghapus data.']);
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
            // Get the pegawai
            $pegawai = User::find($id);
    
            // Delete the pegawai
            $pegawai->delete();
    
            // Get the pegawai attribute
            $pegawai_attribute = UserAttribute::where('user_id','=',$pegawai->id)->first();
    
            // Delete the pegawai attribute
            $pegawai_attribute->delete();
        }
        
        // Redirect
        return redirect()->route('admin.pegawai.index')->with(['message' => 'Berhasil menghapus data.']);
    }
}
