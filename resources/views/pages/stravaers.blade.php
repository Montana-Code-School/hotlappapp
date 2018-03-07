
@extends('layouts.general')

@section('content')

{{dd($companies);}}

<form>
  <div class="form-group">
   <label for="exampleFormControlSelect1">Example select</label>
    <select class="form-control" id="exampleFormControlSelect1">
      <option>1</option>
      <option>2</option>
      <option>3</option>
      <option>4</option>
      <option>5</option>
    </select>
  </div>
  <button type="submit" class="btn btn-primary">Submit</button>
</form>

@endsection