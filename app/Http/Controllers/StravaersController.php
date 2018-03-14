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

        $stravaer = new Stravaer;

        $stravaer->id = $request->strava_id;

        $stravaer->save();
        


    }

    public function loadLeaderboard(Request $request)
    {
        try {
 
            $user = Auth::user();
            $token = $user->strava_token;
            $adapter = new Pest('https://www.strava.com/api/v3');
            $service = new REST($token, $adapter);  // Define your user token here.
            $client = new Client($service);
            $athlete = $client->getAthlete($id = null);
            $members = $client->getClubMembers(432809);
            $member_activities = $client->getClubActivities(432809);

        
            if(User::find($athlete['id'])){
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