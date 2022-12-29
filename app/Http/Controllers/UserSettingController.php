<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Ajifatur\Helpers\DateTimeExt;
use Ajifatur\FaturHelper\Models\User;
use Ajifatur\FaturHelper\Models\UserAvatar;
use App\Models\Cabang;
use App\Models\Jabatan;
use App\Models\User as ModelsUser;

class UserSettingController extends \App\Http\Controllers\Controller
{
    /**
     * Display the user profile.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get the user
        // $user = User::findOrFail(Auth::user()->id);
        // dd(Auth::user()->role_id);
        $user = ModelsUser::with('role', 'attribute.cabang', 'attribute.jabatan')->where('id','=',Auth::user()->id)->first();

        // View
        return view('admin/user-setting/index', [
            'user' => $user
        ]);
    }

    /**
     * Display the user profile settings.
     *
     * @return \Illuminate\Http\Response
     */
    public function profile()
    {
        // View
        $cabang = Cabang::orderBy('nama','asc')->get();
        $jabatan = Jabatan::orderBy('nama','asc')->get();
        $user = ModelsUser::with('role', 'attribute.cabang', 'attribute.jabatan')->where('id','=',Auth::user()->id)->first();

        return view('admin/user-setting/profile', [
            'cabang'    => $cabang,
            'jabatan'   => $jabatan,
            'user'      => $user
        ]);

    }

    /**
     * Update the user profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateProfile(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:200',
            'email' => [
                'required', 'email', Rule::unique('users', 'email')->ignore(Auth::user()->id, 'id')
            ],
            'gender' => 'required',
            'country_code' => 'required',
            'phone_number' => 'required|numeric'
        ]);

        // Check errors
        if($validator->fails()) {
            // Back to form page with validation error messages
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        else {
            // Update the user profile
            $user = User::find(Auth::user()->id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->save();

            // Update the user attribute
            if($user->attribute) {
                $user->attribute->birthdate = DateTimeExt::change($request->birthdate);
                $user->attribute->gender = $request->gender;
                $user->attribute->npp = $request->npp;
                $user->attribute->country_code = $request->country_code;
                $user->attribute->dial_code = dial_code($request->country_code);
                $user->attribute->phone_number = $request->phone_number;
                $user->attribute->save();
            }

            // Redirect
            return redirect()->route('admin.settings.profile')->with(['message' => 'Berhasil mengupdate data.']);
        }
    }

    /**
     * Display the user account settings.
     *
     * @return \Illuminate\Http\Response
     */
    public function account()
    {
        // View
        return view('admin/user-setting/account');
    }

    /**
     * Update the user account.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateAccount(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'email' => [
                'required', 'email', Rule::unique('users')->ignore(Auth::user()->id, 'id')
            ],
            'username' => [
                'required', 'alpha_dash', 'min:4', Rule::unique('users')->ignore(Auth::user()->id, 'id')
            ],
        ]);

        // Check errors
        if($validator->fails()) {
            // Back to form page with validation error messages
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        else {
            // Update the user account
            $user = User::find(Auth::user()->id);
            $user->email = $request->email;
            $user->username = $request->username;
            $user->save();

            // Redirect
            return redirect()->route('admin.settings.account')->with(['message' => 'Berhasil mengupdate data.']);
        }
    }

    /**
     * Display the user password settings.
     *
     * @return \Illuminate\Http\Response
     */
    public function password()
    {
        // View
        return view('admin/user-setting/password');
    }

    /**
     * Update the user password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'old_password' => 'required|min:6',
            'new_password' => 'required|min:6',
            'confirm_password' => 'required|min:6|same:new_password',
        ]);

        // Check errors
        if($validator->fails()) {
            // Back to form page with validation error messages
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        else {
            // Check password hashing, for security
            if(Hash::check($request->old_password, Auth::user()->password)) {
                // Update the user password
                $user = User::find(Auth::user()->id);
                $user->password = bcrypt($request->new_password);
                $user->save();

                // Redirect
                return redirect()->route('admin.settings.password')->with(['message' => 'Berhasil mengupdate data.', 'status' => 1]);
            }
            else {
                // Redirect
                return redirect()->back()->with(['message' => 'Kata sandi lama yang dimasukkan tidak cocok dengan kata sandi yang dimiliki saat ini.', 'status' => 0]);
            }
        }
    }

    /**
     * Get the user avatar.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function avatar(Request $request)
    {
        if($request->ajax()) {
            // Get the user
            $avatars = Auth::user()->avatars->pluck('avatar')->toArray();

            // Return
            return response()->json($avatars);
        }
    }

    /**
     * Update the user avatar.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateAvatar(Request $request)
    {
        // Make directory if not exists
        if(!File::exists(public_path('assets/images/users')))
            File::makeDirectory(public_path('assets/images/users'));

        if($request->choose == 1) {
            // Update the user avatar
            $user = User::find(Auth::user()->id);
            $user->avatar = $request->image;
            $user->save();
        }
        else {
            // Upload the image
            $image = $request->image;
            $image = str_replace('data:image/png;base64,', '', $image);
            $image = str_replace(' ', '+', $image);
            $imageName = date('Y-m-d-H-i-s').'.'.'png';
            File::put(public_path('assets/images/users'). '/' . $imageName, base64_decode($image));

            // Update the user avatar
            $user = User::find(Auth::user()->id);
            $user->avatar = $imageName;
            $user->save();

            // Save user avatar
            $user_avatar = new UserAvatar;
            $user_avatar->user_id = $user->id;
            $user_avatar->avatar = $user->avatar;
            $user_avatar->save();
        }

        // Redirect
        return redirect()->route('admin.profile')->with(['message' => 'Berhasil mengupdate avatar.']);
    }
}
