@extends('layouts.general')

@section('content')

<!-- Masthead -->
<header class="masthead text-white text-center">
    <div class="overlay responsive"></div>
    <div class="container">
      <div class="row">
        <div class="col-xl-9 mx-auto">
          <h1 class="mb-5">Welcome to HotLappApp.</h1>
          <h3 class="mb-5">Connect Strava. Run laps. Earn $.</h3>
          <h3 class="mb-5">Support GVLT. Drink Beer.</h3>
        </div>
        <div class="col-md-10 col-lg-8 col-xl-7 mx-auto">
        <a class="btn btn-primary" href="{{ route('stravalogin') }}">Connect to Strava</a>
        </div>
      </div>
    </div>
  </header>



  

  <!-- Testimonials -->
  <section class="testimonials text-center bg-light">
    <div class="container">
    <br />
    <br />
      <h2 class="mb-5">What people are saying...</h2>
      <div class="row">
        <div class="col-lg-4">
          <div class="testimonial-item mx-auto mb-5 mb-lg-0">
            <img class="img-fluid rounded-circle mb-3" src="img/testimonials-1.jpg" alt="">
            <h5>Stuart Wilson</h5>
            <p class="font-weight-light mb-0">"I love free beer!"</p>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="testimonial-item mx-auto mb-5 mb-lg-0">
            <img class="img-fluid rounded-circle mb-3" src="img/testimonials-2.jpg" alt="">
            <h5>J Basvandorst</h5>
            <p class="font-weight-light mb-0">"Great app. Thanks Strava for removing the data."</p>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="testimonial-item mx-auto mb-5 mb-lg-0">
            <img class="img-fluid rounded-circle mb-3" src="img/testimonials-3.jpg" alt="">
            <h5>Oscar Auth</h5>
            <p class="font-weight-light mb-0">"Better than Facebook!"</p>
          </div>
        </div>
      </div>
    </div>
  </section>

@endsection