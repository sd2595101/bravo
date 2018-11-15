  <div class="chapter_opbox chapter_opbox_top center row">
    <a href="{{route('chapter', $book['bookid'])}}" class="gochapter menu"><span class="glyphicon glyphicon-list"></span> 目录</a>
    <a href="{{$prev}}" class="prevchapter">
      <span class="glyphicon glyphicon-step-backward"></span> 上一章
    </a>
    <a href="{{$next}}" class="nextchapter">
      下一章 <span class="glyphicon glyphicon-step-forward"></span>
    </a>
  </div>