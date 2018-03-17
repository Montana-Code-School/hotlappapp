<?php

namespace App\Http\Controllers;

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
 
            
            $adapter = new Pest('https://www.strava.com/api/v3');
            $service = new REST($request->token, $adapter);  // Define your user token here.
            $client = new Client($service);
            $athlete = $client->getAthlete($id = null);
            $members = $client->getClubMembers(432809);
            $member_activities = $client->getClubActivities(432809);
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