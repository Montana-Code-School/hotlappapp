
@extends('layouts.general')

@section('content')

<form method="post" action="/stravaers">
  {{ csrf_field() }}
  <div class="form-group">
   <label for="exampleFormControlSelect1">Example select</label>
    <select name="companyId" class="form-control" id="exampleFormControlSelect1">
      @foreach ($companies as $company)
        <option value={{$company->id}}>{{$company->name}}</option>
      @endforeach
    </select>
  </div>
  <button type="submit" class="btn btn-primary">Submit</button>
</form>

@endsection