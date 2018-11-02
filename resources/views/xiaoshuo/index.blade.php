@extends('layouts.app')

@section('content')
<div class="container-fluid ">
  @guest
    
  @else
  {{-- show history --}}
  @endguest
</div>
@endsection