
@extends('layouts.general')

@section('content')

 <!-- Masthead -->

<!-- example vue component -->

<div id="app">


      
      <leaderboard-component 
        :activities="{{ json_encode($activities) }} " 
        :companies="{{ json_encode($companies) }}"
        :users="{{ json_encode($users) }}"
        >
      
      </leaderboard-component>
     
    </div>



@endsection

