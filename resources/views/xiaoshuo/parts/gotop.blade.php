<div id="gotop" title="返回顶部"><a href="javascript:void(0);" onclick="gotop();return false;">
    <i class="glyphicon glyphicon-chevron-up"></i></a></div>
<script>
    function gotop(){
        console.log('aa');
        $("html").animate({"scrollTop":0},300);
    }
</script>