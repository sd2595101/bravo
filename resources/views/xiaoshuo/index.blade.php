@extends('layouts.app')

@section('content')
<div class="container-fluid ">
  @guest
    
  @else
  {{-- show history --}}
  @include('xiaoshuo.parts.history')
  @endguest
</div>
@endsection