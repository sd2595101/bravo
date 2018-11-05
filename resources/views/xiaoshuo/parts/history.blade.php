<div class="container-fluid  ">
  <div class="row">
    <di class="col bravo-h1">阅读足迹</h1>
  </div>
  <div class="">
	<div class="col history clearfix">
	@foreach ($historys as $history)
	<?php $book = $history['book']; $content = $history['content'] ?>
	

	  
	  <div class="history-item">
	    <div class="image-box float-left">
	      <img src="{{$book['image']}}" class="inner" />
	    </div>
	    <div class="book-info float-right">
		    <div class="bookname" title="{{$book['title']}}"><a href="{{route('chapter', $book['bookid'])}}">{{$book['title']}}</a></div>
		    <p class="info">{{ $book['desc'] }}</p>
		    <div class="author" title="{{$book['uname']}}">{{$book['uname']}}</div>
		    <a class="cate" href="http://www.zongheng.com/category/15.html" target="_blank" title="{{ $book['cname'] }}">{{ $book['cname'] }}</a>
		</div>
		<div class="latest-browsing"><a href="{{ route('content', [$book['bookid'], $content['chapterid']]) }}" title="{{$content['volume']}}"><div>最近阅读：{{$content['title']}}</div></a>
		</div>
	  </div>

	  
	  @endforeach
	</div>
  </div>
    



    
</div>