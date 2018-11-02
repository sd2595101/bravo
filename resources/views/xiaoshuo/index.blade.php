@extends('layouts.app')

@section('content')
<div class="container-fluid ">
  @guest
    
  @else
  {{-- show history --}}
  <ul>
      
  </ul>
  @endguest
</div>
@endsection