<!DOCTYPE html>
<html lang="zh" class="{{$bodyClass??''}}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../../../favicon.ico">

    <title>{{$pageTitle??'Im'}}-Bravo</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">

    <!-- Custom styles for this template -->
    <link href="/css/xiaoshuo.css" rel="stylesheet">
  </head>

  <body class="bravo center {{$bodyClass??''}}">

    <div class="container d-flex w-100 h-100 p-3 mx-auto flex-column">
      <header class="masthead {{$headerCssAdd ?? ''}}">
        <div class="inner">
          <h3 class="masthead-brand">
            <a href="/" class="badge  "><i class="fas fa-home"></i> 欢迎来到 - Bravo</a>
          </h3>
          
          <nav class="nav nav-masthead justify-content-center">
            <a class="nav-link {{$homeactive??''}}" href="/xiaoshuo">Home</a>
            <!--
            <a class="nav-link" href="#">Features</a>
            <a class="nav-link" href="#">Contact</a>
        -->
          </nav>
        @section('topmenu')
        @show
        </div>
      </header>

      <main role="main" class="inner cover">
        @yield('main')
      </main>

      <footer class="mastfoot mt-auto">
        <div class="inner">
          <p><a href="mailto:qcr128@163.com" ><i class="fa fa-envelope-square">&nbsp;</i>联系我们</a></p>
        </div>
      </footer>
    </div>

    <!-- Bootstrap core JavaScript 
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery-slim.min.js"><\/script>')</script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
  </body>
</html>
