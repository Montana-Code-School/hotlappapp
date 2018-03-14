<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Socialite;
use Illuminate\Http\Request;
use App\Company;
use Illuminate\Support\Facades\Auth;
use App\User;



class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    /**
     * Redirect the user to the Strava authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider()
    {
        return Socialite::driver('strava')->redirect();
    }
     /**
     * Obtain the user information from Strava.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback(Request $request)
    {
        $user = Socialite::driver('strava')->user();
        $authUser = $this->findOrCreateUser($user);

        Auth::login($authUser, true);
        // return redirect($this->redirectTo);

        // if ($request->session()->get('hotlappappAT')) {
        //     $token = $request->session()->get('hotlappappAT');
        // } else  {
        //     $authUser = $this->findOrCreateUser($user, $provider);
        //     Auth::login($authUser, true);
        //    
        //     session(['hotlappappAT'=>$token]);
        // }
        // dd(Auth::check());
        // dd($request->session()->get('hotlappappAT'));

        


    }

    public function findOrCreateUser($user)
    {
        $authUser = User::where('strava_id', $user->id)->first();
        if ($authUser) {
            return $authUser;
        }
        return User::create([
            'name'     => $user->name,
            'email'    => $user->email,
            'strava_id' => $user->id,
            'strava_token' => $user->token
        ]);
    }
    
}