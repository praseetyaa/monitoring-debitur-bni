<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;
use Ajifatur\FaturHelper\Models\Role;
use Ajifatur\FaturHelper\Models\User;
use Ajifatur\FaturHelper\Models\UserAccount;
use Ajifatur\FaturHelper\Models\Visitor;

class LoginController extends \App\Http\Controllers\Controller
{
    /**
     * The role key.
     *
     * @var string
     */
    protected $role = 'admin';

    /**
     * Show login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        // View
        return view('faturhelper::auth/login');
    }

    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function authenticate(Request $request)
    {
        // Validator
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
            'g-recaptcha-response' => 'recaptcha',
        ]);

        // Return if has errors
        if($validator->fails()) {
            // Add to log
            $this->authenticationLog($request, $validator->errors()->toJson());

            // Return
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        else {
            // Check login type
            if(config('faturhelper.auth.allow_login_by_email') === true)
                $loginType = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
            else
                $loginType = 'username';
    
            // Set credentials
            $credentials = [
                $loginType => $request->username,
                'password' => $request->password,
                'status' => 1
            ];

            // Add credentials if non-admin is disallowed to log in
            if(config()->has('faturhelper.auth.non_admin_can_login') && config('faturhelper.auth.non_admin_can_login') === false) {
                $credentials['role_id'] = Role::where('is_admin','=',1)->pluck('id')->toArray();
            }

            // Auth attempt
            if(Auth::attempt($credentials)) {
                // Regenerate session
                $request->session()->regenerate();

                // Update user's last visit
                $user = User::find($request->user()->id);
                $user->last_visit = date('Y-m-d H:i:s');
                $user->save();

                // Add to visitors
                if(Schema::hasTable('visitors')) {
                    $visitor = new Visitor;
                    $visitor->user_id = $user->id;
                    $visitor->ip_address = $request->ip();
                    $visitor->device = device_info();
                    $visitor->browser = browser_info();
                    $visitor->platform = platform_info();
                    $visitor->location = location_info($request->ip());
                    $visitor->save();
                }

                // Redirect
                if($user && $user->role->is_admin == 1)
                    return redirect()->route('admin.dashboard');
                elseif($user && $user->role->is_admin == 0)
                    return redirect('/');
            }
            else {
                // Add to log
                $this->authenticationLog($request, 'Attempt failed.');

                // Return
                return redirect()->back()->withErrors([
                    'message' => 'Tidak ada akun yang cocok dengan username / password yang Anda masukkan!'
                ])->withInput();
            }
        }
    }

    /**
     * Authentication Log.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string $errors
     * @return \Illuminate\Http\Response
     */
    public function authenticationLog(Request $request, $errors)
    {
        Log::build([
            'driver' => 'single',
            'path' => storage_path('logs/authentications.log'),
        ])->error(
            json_encode([
                'username' => $request->username,
                'ip' => $request->ip(),
                'errors' => $errors
            ])
        );
    }
    
    /**
     * Redirect to provider.
     *
     * @param  string $provider
     * @return \Laravel\Socialite\Facades\Socialite
     */
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from provider.  Check if the user already exists in our
     * database by looking up their provider_id in the database.
     * If the user exists, log them in. Otherwise, create a new user then log them in. After that
     * redirect them to the authenticated users homepage.
     *
     * @param  string $provider
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback($provider)
    {
        $user = Socialite::driver($provider)->user();
        $authUser = $this->findOrCreateUser($user, $provider);
        Auth::login($authUser, true);
        return redirect()->route('admin.dashboard');
    }

    /**
     * If a user has registered before using social auth, return the user
     * else, create a new user object.
     * @param  object $user
     * @param  string $provider
     * @return \Ajifatur\FaturHelper\Models\User
     */
    public function findOrCreateUser($user, $provider)
    {
        // Get the user account by provider ID and provider name
        $authUser = UserAccount::where('provider_id','=',$user->getId())->where('provider_name','=',$provider)->first();

        if($authUser) {
            return $authUser;
        }
        else {
            // Get the user by the email
            $data = User::where('email','=',$user->getEmail())->first();

            if(!$data) {
				// Save the user
                $data = new User;
                $data->role_id = role($this->role);
                $data->name = $user->getName();
                $data->username = $user->getNickname();
                $data->email = $user->getEmail();
                $data->password = null;
                $data->access_token = access_token();
                $data->remember_token = null;
				$data->avatar = $user->getAvatar();
				$data->status = 1;
				$data->last_visit = null;
				$data->email_verified_at = null;
                $data->save();
            }
				
            // Save the user account
            $user_account = new UserAccount;
            $user_account->user_id = $data->id;
            $user_account->provider_id = $user->getId();
            $user_account->provider_name = $provider;
            $user_account->save();

            return $data;
        }
    }
    
    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        // Logout
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    
        // Redirect
        return redirect()->route('auth.login');
    }
}