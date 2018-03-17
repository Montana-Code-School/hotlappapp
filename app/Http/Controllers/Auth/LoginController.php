<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Socialite;
use Illuminate\Http\Request;
use App\Company;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Http\Controllers\StravaersController;
use Pest;
use Strava\API\Service\REST;
use Strava\API\Client;
use Strava\API\Exception;



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
       //dd($user);
        $token = $authUser->strava_token;
        $hotLappAppClubId = 432809;
        $clubs = $user->user['clubs'];
        $inDaClub = false;
       //dd($clubs);
       foreach ($clubs as $club) {
            if ($club['id'] === $hotLappAppClubId){
                $inDaClub = true;
            }
       } 
       if (!$inDaClub) {
          return redirect()->route('welcome');
       }

           if($authUser->company_id !== null){
                return redirect()->route('leaderboard', ['token'=>$token]);
            } else {
                return redirect()->route('companies', ['authUserId'=>$authUser->id]);

            //     $companies = Company::all(['name', 'id']);
            //    return view('pages.stravaers', ['companies' => $companies, 'stravaerId' => $athlete['id']]);
            }
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