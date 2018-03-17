<?php

namespace App\Http\Controllers;

// require_once 'StravaApi.php';

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Pest;
use Strava\API\Service\REST;
use Strava\API\Client;
use Strava\API\Exception;
use App\User;
use App\Company;
use Iamstuartwilson\StravaApi;




class StravaersController extends Controller
{
    
    /**
     * Store a new user.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $companyId = $request->input('companyId');
        $authUser = User::where('id', $request->userId)->first();
        $authUser->company_id = $request->companyId;
        $authUser->save();
        return redirect()->route('leaderboard', ['token'=>$authUser->strava_token]);
    
    }

    public function loadLeaderboard(Request $request)
    {
        try {
 
            $api = new StravaApi(
                env('STRAVA_KEY'),
                env('STRAVA_SECRET')
            );
            $api->setAccessToken($request->token);
            $athlete = $api->get('athlete');
            $members = $api->get('/clubs/432809/members');
            dd($athlete);
            $member_activities = $api->get('/clubs/432809/activities');
            dd($member_activities);
            $companies = Company::all(['name', 'id']);
            $users = User::all(['strava_id', 'company_id']);
                return view('pages.leaderboard')->with(['club_members' => $members, 'activities' => $member_activities, 'companies' => $companies, 'users' => $users]);
           
        } catch(Exception $e) {
            print $e->getMessage();
        }


    }

    public function loadCompanies(Request $request) {
        $companies = Company::all(['name', 'id']);
        return view('pages.stravaers', ['companies' => $companies, 'userId' => $request->authUserId]);
    }
}