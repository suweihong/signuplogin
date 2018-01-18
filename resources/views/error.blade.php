<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>ThaiEx</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href={{asset('/css/lzlogin.css')}}>

  </head>
  <body>
    <div class="return">
      <a href="#"></a>
    </div>

      <section class="content">
          <p>{{$err}}</p>
          <p>错误信息2！！！</p>

        <button class="btn btn-primary" type="button" name="button"><a style="color:#fff;" href="#">返回</a></button>

          <!-- <div class="others">
              <a class="right" href="#"><button class="btn btn-primary" type="button" name="button">返回</button></a>
          </div> -->
      </section>


      <script src="/js/jquery.js" charset="utf-8"></script>
      <script src="/js/bootstrap.min.js" charset="utf-8"></script>
      <script src="/js/myServer.js" charset="utf-8"></script>
      <script type="text/javascript">
        window.onload = () => {
          console.log("mess");
          
        };
      </script>
  </body>
</html>
