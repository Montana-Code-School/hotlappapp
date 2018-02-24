<?php

namespace App\Http\Controllers\Auth;
// include 'vendor/autoload.php';
use Pest;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Socialite;
use Strava\API\Client;
use Strava\API\Exception;
use Strava\API\Service\REST;

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
    public function handleProviderCallback()
    {
        $user = Socialite::driver('strava')->user();
        // dd($user->token);
        try {
            $adapter = new Pest('https://www.strava.com/api/v3');
            $service = new REST($user->token, $adapter);  // Define your user token here.
            $client = new Client($service);
        
            // $athlete = $client->getAthlete();
            // print_r($athlete);
        
            // $activities = $client->getAthleteActivities();
            // print_r($activities);
        
            // $club = $client->getClub(432809);
            // print_r($club);
            
            $members = $client->getClubMembers(432809);
            $member_activities = $client->getClubActivities(432809);
            //loop over each member to get activity and pass to leaderboard

            return view('pages.leaderboard')->with(['club_members' => $members, 'activities' => $member_activities]);
            
            //      foreach($member_activity as $activity)
            //         {
            //             $activity = User::find($activity->created_by);
            //             $created_by = $user['name'];
            //         }
            // print_r($member_activity);

            

        } catch(Exception $e) {
            print $e->getMessage();
        }
        // dd($user);

        // $user->token;
    }
}
