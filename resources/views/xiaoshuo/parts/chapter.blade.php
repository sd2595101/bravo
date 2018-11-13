<div class="container-fluid">
  <nav aria-label="breadcrumb-bravo">
    <ol class="breadcrumb-bravo">
      <li class="breadcrumb-item-bravo"><a href="/"><span class="glyphicon glyphicon-home"></span></a></li>
      <li class="breadcrumb-item-bravo"><a href="/query?q={{$book['category_name']}}" target="_blank">{{$book['category_name']}}</a></li>
      <li class="breadcrumb-item-bravo"><a href="{{route('book', $book['bookid'])}}"><span class="glyphicon glyphicon-book"></span> {{$book['title']}}</a></li>
      <li class="breadcrumb-item-bravo"><span class="glyphicon glyphicon-list"></span> 最新章节</li>
    </ol>
  </nav>
  <div class="row center">
    <div class="col">
      <h1 class="m-2 text-danger">{{$book['title']}}</h1>
    </div>
  </div>
  <div class="row center">
    <div class="col">
      <span>作者&nbsp;：&nbsp;{{$book['uname']}}</span>
    </div>
  </div>
  <div class="center container-fluid">
    @foreach ($list as $group)
    <div class="chapter-container tomename">
      <div class="col"><h3>{{$group['volume']}}</h3></div>
    </div>
    <ul class="chapter-list">
      @foreach ($group['chapter-list'] as $chapter)
      <?php 
          $chapterId = $chapter['chapterid'];
          $isVip = in_array($chapterId, $group['chapter-list-vip']);
      ?>
      <li class="col-12 col-sm-6 col-lg-4 col-md-6 {{ $isVip ? 'vip' : '' }}">
        
        <a href="{{ route('content', [$book['bookid'], $chapter['chapterid']]) }}">
        @if ($isVip ==true)<span class="glyphicon glyphicon-star-empty"></span>@endif
        {{trim($chapter['chaptername'])}}</a>
      </li>
      @endforeach
    </ul>
    @endforeach
  </div>
</div>

@include('xiaoshuo.parts.gotop')