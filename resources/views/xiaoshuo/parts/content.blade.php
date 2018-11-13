  <nav aria-label="breadcrumb-bravo">
    <ol class="breadcrumb-bravo">
      <li class="breadcrumb-item-bravo"><a href="/"><span class="glyphicon glyphicon-home"></span></a></li>
      <li class="breadcrumb-item-bravo"><a href="{{route('query', ['q'=>$book['category_name']])}}" target="_blank">{{$book['category_name']}}</a></li>
      <li class="breadcrumb-item-bravo"><a href="{{route('book', $book['bookid'])}}"><span class="glyphicon glyphicon-book"></span> {{$book['title']}}</a></li>
      <li class="breadcrumb-item-bravo"><a href="{{route('chapter', $book['bookid'])}}"><span class="glyphicon glyphicon-list"></span> 最新章节</a></li>
      <li class="breadcrumb-item-bravo active" aria-current="page">{{$info['volume']}}</li>
    </ol>
  </nav>
  @include('xiaoshuo.parts.content-pagectl')
  
  <div class="row center">
    <div class="col">
      <h1 class="m-2">{{$info['title']}}</h1>
    </div>
  </div>
  <div class="container">
    <span class="original-site">源站点 : {{$info['original_url']}}</span>
    <div class="content">
      @foreach ($info['content'] as $content)
      <p>{{$content}}</p>
      @endforeach
    </div>
  </div>
  @include('xiaoshuo.parts.content-pagectl')

@include('xiaoshuo.parts.gotop')