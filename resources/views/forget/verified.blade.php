<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>ThaiEx</title>
    <link rel="stylesheet" href="/css/simple-line-icons.css">
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href={{asset('/css/lzlogin.css')}}>
         <script src="{{asset('js/jquery.js')}}"></script>
    <script src="{{asset('layer/layer.js')}}"></script>
  </head>
  <body>
      <div class="return">
        <a><i class="icon-arrow-left"></i></a>
      </div>
      <nav class="header">设置新密码</nav>
      <section class="content">

        <div id="my-error" style="display:none;" class="alert alert-danger alert-dismissible fade in" role="alert">
          <div type="button" class="close">
            <span aria-hidden="true">&times;</span>
          </div>
          <p id="my-err-msg" style="width:200px;"></p>
        </div>

        <form class="" action="/forget/verified" method="post">
          {{ csrf_field() }}
          <p>邮件验证成功 请设置新密码</p>
          <div class="position">
            <!-- <i class="icon-lock"></i> -->
            <label class="icon-lock" for="password"></label>
            <input id="password" type="password" name="password" placeholder="请输入密码">
          </div>
          <input type="submit" name="submit" value="登入">

         

        </form>
      </section>


      <script src="/js/jquery.js" charset="utf-8"></script>
      <script src="/js/bootstrap.min.js" charset="utf-8"></script>
      <script src="/js/myServer.js" charset="utf-8"></script>
      <script type="text/javascript">
          window.onload = () => {

            $("input[type='submit']").click(()=> {
              var userData = {
                password: $("#password").val(),
                token:'{{$token}}',
                _token:'{{csrf_token()}}',
                targetUrl: $("#targetUrl").val(),
              };

              window.ser.post("/forget/verified", userData).done(data => {
                if (!data.errcode || data.errcode == "0") {
                 layer.msg(data.errmsg,{icon:6},function(){
                     window.location.href="/";
                 });
                 
                } else {
                   layer.msg(data.errmsg,{icon: 5});
                };
              }).fail(err => console.log(err));


              return false;
            });
          };
      </script>

  </body>
</html>
