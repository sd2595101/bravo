@extends('layouts.app')

@section('content')
    @foreach ($booklist as $book)
    <div class="row border-bottom list">
        <div class="col-3 col-sm-3 col-md-2 col-lg-2 ">
            <div class="p-2">
                <img src="{{$book['image']}}" class="inner" style="width:100%;"/>
            </div>
        </div>
        <div class="col-9 col-sm-9 col-md-10 col-lg-10 ">
            <div class="p-2">
                <div class="row">
                    <span class="h3">
                        <a href="{{route('book', $book['bookid'])}}"><span class="glyphicon glyphicon-book"></span>{{$book['title']}}</a>
                    </span>
                </div>
                <div class="row">
                    <a href="{{$book['ulink']}}" class="p-1 font-weight-light" target="_blank">{{$book['uname']}}</a>
                    <span class="p-1 font-weight-light"> | </span>
                    <a href="{{$book['clink']}}" class="p-1 font-weight-light" target="_blank">{{$book['cname']}}</a>
                    <span class="p-1 font-weight-light text-muted" style="font-size:14px"> | 关键字 : {{implode(' | ',$book['keyword'])}}</span>
                </div>
                <div class="description text-left">
                    {{$book['desc']}}
                </div>
                <div class="list-btn-area  d-none d-md-block d-lg-block">
                    <div>
                        <a href="{{route('book', $book['bookid'])}}" class="p-1 m-1 text-right text-danger" >
                            [ <span class="glyphicon glyphicon-book"></span> 查看书页 ] </a>
                    </div>
                    <div>
                        <a href="{{route('chapter', $book['bookid'])}}" class="p-1 m-1 text-right text-danger" >
                            [ <span class="glyphicon glyphicon-list"></span> 章节目录 ] </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
    @include('xiaoshuo.parts.gotop')
@endsection

