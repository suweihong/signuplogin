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
      <nav class="header">双重验证</nav>
      <section class="content">

        <div id="my-error" style="display:none;" class="alert alert-danger alert-dismissible fade in" role="alert">
          <div type="button" class="close">
            <span aria-hidden="true">&times;</span>
          </div>
          <p id="my-err-msg" style="width:200px;">错误的地方！！！</p>
        </div>

        <form class="" action="/double/auth" method="post">
          {{ csrf_field() }}
          <p>请输入由谷歌身份验证器生成的双重验证码</p>
          <input class="verify" type="text" maxlength="6" name="oneCode" id="oneCode" placeholder="000000">
          <input type="submit" name="submit" value="验证">
        <!-- 隐藏标签input 用于保存api_token的值 -->
        </form>
      </section>


      <script src="/js/jquery.js" charset="utf-8"></script>
      <script src="/js/bootstrap.min.js" charset="utf-8"></script>
      <script src="/js/myServer.js" charset="utf-8"></script>
      <script type="text/javascript">
          window.onload = () => {

            $("input[type='submit']").click(()=> {
              var userData = {
                oneCode: $("#oneCode").val(),
                api_token: '{{$api_token}}',
                _token:'{{csrf_token()}}',
                targetUrl: $("#targetUrl").val(),
              };

              window.ser.post("/double/auth", userData).done(data => {
                if (!data.errcode || data.errcode == "0") {
                 layer.msg(data.errmsg,{icon:6},function(){
                   window.location.href = "/";
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
