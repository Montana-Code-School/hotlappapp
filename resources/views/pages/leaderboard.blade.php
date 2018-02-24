
@extends('layouts.general')

@section('content')

 <!-- Masthead -->

<!-- example vue component -->
<div id="app">
      
      <leaderboard-component>
      </leaderboard-component>
     
    </div>

 @foreach ($club_members as $member)
          <p>This is a club member in da club:  {{ $member['id'] }}</p>
        @endforeach

        @foreach ($activities as $activity )
          <p>This is activites in da club:</p>
          <ul>
            <li> Name: {{ $activity['athlete']['firstname'] }} </li>
            <li> Route Name: {{ $activity['name'] }} </li>
            <li> Date: {{ $activity['start_date_local'] }} </li>
            <li> Distance: {{ $activity['distance'] }} </li>
          </ul>
        @endforeach

@endsection

