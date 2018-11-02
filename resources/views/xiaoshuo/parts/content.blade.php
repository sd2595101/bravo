<div class="container">
  <nav aria-label="breadcrumb-bravo">
    <ol class="breadcrumb-bravo">
      <li class="breadcrumb-item-bravo"><a href="/"><span class="glyphicon glyphicon-home"></span></a></li>
      <li class="breadcrumb-item-bravo"><a href="/query?q={{$book['category_name']}}" target="_blank">{{$book['category_name']}}</a></li>
      <li class="breadcrumb-item-bravo"><a href="{{route('book', $book['bookid'])}}"><span class="glyphicon glyphicon-book"></span> {{$book['title']}}</a></li>
      <li class="breadcrumb-item-bravo"><a href="{{route('chapter', $book['bookid'])}}"><span class="glyphicon glyphicon-list"></span> 最新章节</a></li>
      <li class="breadcrumb-item-bravo active" aria-current="page">{{$info['volume']}}</li>
    </ol>
  </nav>
  <div class="chapter_opbox chapter_opbox_top center">
    <a href="{{$prev}}" class="prevchapter">
      <span class="glyphicon glyphicon-step-backward"></span> 上一章
    </a>
    <a href="{{route('chapter', $book['bookid'])}}" class="gochapter"><span class="glyphicon glyphicon-list"></span> 目录</a>
    <a href="{{$next}}" class="nextchapter">
      下一章 <span class="glyphicon glyphicon-step-forward"></span>
    </a>
  </div>
  <div class="row center">
    <div class="col">
      <h1 class="m-2">{{$info['title']}}</h1>
    </div>
  </div>
  <div class="container">
    <span class="original-site">源站点链接 : {{$info['original_url']}}</span>
    <div class="content">
      @foreach ($info['content'] as $content)
      <p>{{$content}}</p>
      @endforeach
    </div>
  </div>
  <div class="chapter_opbox chapter_opbox_bottom center">
    <a href="{{$prev}}" class="prevchapter">
      <span class="glyphicon glyphicon-step-backward"></span> 上一章
    </a>
    <a href="{{route('chapter', $book['bookid'])}}" class="gochapter"><span class="glyphicon glyphicon-list"></span> 目录</a>
    <a href="{{$next}}" class="nextchapter">
      下一章 <span class="glyphicon glyphicon-step-forward"></span>
    </a>
  </div>
</div>