@extends('layouts.app')

@section('content')
  @guest
    
  @else
  {{-- show history --}}
  @include('xiaoshuo.parts.history')
  @endguest
@endsection