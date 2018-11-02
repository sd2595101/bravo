@extends('layouts.app')

@section('content')

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
      <a href="{{route('chapter', $book['bookid'])}}" class="gochapter"><span class="glyphicon glyphicon-list"></span> 目录</a>
      <a href="{{$prev}}" class="prevchapter"><span class="glyphicon glyphicon-arrow-left"></span> 上一章</a>
      <a href="{{$next}}" class="nextchapter"><span class="glyphicon glyphicon-arrow-right"></span> 下一章</a>
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
      <a href="{{route('chapter', $book['bookid'])}}" class="gochapter"><span class="glyphicon glyphicon-list"></span> 目录</a>
      <a href="{{$prev}}" class="prevchapter"><span class="glyphicon glyphicon-arrow-left"></span> 上一章</a>
      <a href="{{$next}}" class="nextchapter"><span class="glyphicon glyphicon-arrow-right"></span> 下一章</a>
    </div>
</div>
<div id="gotop" title="返回顶部"><a href="javascript:void(0);" onclick="gotop();return false;"><i class="fas fa-2x fa-angle-double-up"></i></a></div>
<script>
    function gotop(){
        console.log('aa');
        $("html").animate({"scrollTop":0},300);
    }
</script>
<!--<script src="/vendor/laravel-admin/jquery-pjax/jquery.pjax.js"></script>-->
<!--<script src="https://unpkg.com/nprogress@0.2.0/nprogress.js"></script>-->
<!-- REQUIRED JS SCRIPTS -->
<script src="http://im-bravo.com/vendor/laravel-admin/jquery-pjax/jquery.pjax.js"></script>
<script src="http://im-bravo.com/vendor/laravel-admin/nprogress/nprogress.js"></script>
<script>

$.pjax.defaults.timeout = 5000;
$.pjax.defaults.maxCacheLength = 0;
$(document).pjax('a:not(a[target="_blank"])', {
    container: '#pjax-container'
});

NProgress.configure({parent: '#pjax-container'});

$(document).on('pjax:timeout', function (event) {
    event.preventDefault();
})

$(document).on('submit', 'form[pjax-container]', function (event) {
    $.pjax.submit(event, '#pjax-container')
});

$(document).on("pjax:popstate", function () {

    $(document).one("pjax:end", function (event) {
        $(event.target).find("script[data-exec-on-popstate]").each(function () {
            $.globalEval(this.text || this.textContent || this.innerHTML || '');
        });
    });
});

$(document).on('pjax:send', function (xhr) {
    if (xhr.relatedTarget && xhr.relatedTarget.tagName && xhr.relatedTarget.tagName.toLowerCase() === 'form') {
        $submit_btn = $('form[pjax-container] :submit');
        if ($submit_btn) {
            $submit_btn.button('loading')
        }
    }
    NProgress.start();
});

$(document).on('pjax:complete', function (xhr) {
    if (xhr.relatedTarget && xhr.relatedTarget.tagName && xhr.relatedTarget.tagName.toLowerCase() === 'form') {
        $submit_btn = $('form[pjax-container] :submit');
        if ($submit_btn) {
            $submit_btn.button('reset')
        }
    }
    NProgress.done();
});


</script>
@endsection