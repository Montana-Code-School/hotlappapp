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
use Iamstuartwilson\StravaApi;




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
        // return Socialite::driver('strava')->scopes(['public'])->redirect();
        $api = new StravaApi(
            env('STRAVA_KEY'),
            env('STRAVA_SECRET')
        );
        $call = $api->authenticationUrl(env('STRAVA_REDIRECT_URI'), $approvalPrompt = 'auto', $scope = 'write', $state = null);
        return redirect($call);
    
    }
     /**
     * Obtain the user information from Strava.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback(Request $request)
    {
        $api = new StravaApi(
            env('STRAVA_KEY'),
            env('STRAVA_SECRET')
        );
        $token = $api->tokenExchange($request->code);
        $user = $token->athlete; 
        $api->setAccessToken($token->access_token);
       
        $authUser = $this->findOrCreateUser($user, $token->access_token);
    
        $hotLappAppClubId = 432809;
        $clubs = $api->get("/athlete/clubs");
        $inDaClub = false;
       foreach ($clubs as $club) {
            if ($club->id === $hotLappAppClubId){
                $inDaClub = true;
            }
       } 
       if (!$inDaClub) {
            $success = $api->post('/clubs/432809/join');
       }

        if($authUser->company_id !== null){
            return redirect()->route('leaderboard', ['token'=>$token]);
        } else {
            return redirect()->route('companies', ['authUserId'=>$authUser->id]);

        //     $companies = Company::all(['name', 'id']);
        //    return view('pages.stravaers', ['companies' => $companies, 'stravaerId' => $athlete['id']]);
        }
    }

    public function findOrCreateUser($user, $token)
    {
        $authUser = User::where('strava_id', $user->id)->first();
        if ($authUser) {
            return $authUser;
        }
        return User::create([
            'name'     => $user->firstname . " " . $user->lastname,
            'email'    => $user->email,
            'strava_id' => $user->id,
            'strava_token' => $token
        ]);
    }
    
}