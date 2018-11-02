@extends('layouts.app')

@section('content')
@include('xiaoshuo.parts.content')

<div id="gotop" title="返回顶部"><a href="javascript:void(0);" onclick="gotop();return false;">
    <i class="fas fa-2x fa-tasks"></i></a></div>
<script>
    function gotop(){
        console.log('aa');
        $("html").animate({"scrollTop":0},300);
    }
</script>
<!--<script src="/vendor/laravel-admin/jquery-pjax/jquery.pjax.js"></script>-->
<script src="https://unpkg.com/nprogress@0.2.0/nprogress.js"></script>
<!-- REQUIRED JS SCRIPTS -->
<!--<script src="http://im-bravo.com/vendor/laravel-admin/jquery-pjax/jquery.pjax.js"></script>-->
<!--<script src="/vendor/laravel-admin/nprogress/nprogress.js"></script>-->
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