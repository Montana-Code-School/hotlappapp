<?php

namespace App\Http\Controllers\Auth;
use Pest;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Socialite;
use Strava\API\Client;
use Strava\API\Exception;
use Strava\API\Service\REST;
use Illuminate\Http\Request;
use App\Stravaers;
use App\Company;
use Illuminate\Support\Facades\Auth;



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
        if ($request->session()->get('hotlappappAT')) {
            $token = $request->session()->get('hotlappappAT');
        } else  {
            $user = Socialite::driver('strava')->user();
            $authUser = $this->findOrCreateUser($user, $provider);
            Auth::login($authUser, true);
            $token = $user->token;
            session(['hotlappappAT'=>$token]);
        }
        dd(Auth::check());
        // dd($request->session()->get('hotlappappAT'));

        try {
 
            $adapter = new Pest('https://www.strava.com/api/v3');
            $service = new REST($token, $adapter);  // Define your user token here.
            $client = new Client($service);
            $athlete = $client->getAthlete($id = null);
            $members = $client->getClubMembers(432809);
            $member_activities = $client->getClubActivities(432809);

            if(Stravaers::find($athlete['id'])){
                return view('pages.leaderboard')->with(['club_members' => $members, 'activities' => $member_activities]);
            } else {

                $companies = Company::all(['name', 'id']);
                return view('pages.stravaers', ['companies' => $companies, 'stravaerId' => $athlete['id']]);
            }
            
           

        } catch(Exception $e) {
            print $e->getMessage();
        }


    }
}
