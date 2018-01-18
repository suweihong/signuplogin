<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>ThaiEx</title>
    <link rel="stylesheet" href="/css/simple-line-icons.css">
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/lzlogin.css">
        <script src="{{asset('js/jquery.js')}}"></script>
    <script src="{{asset('layer/layer.js')}}"></script>


  </head>
  <body>
      <div class="return">
        <a><i class="icon-arrow-left"></i></a>
      </div>
      <nav class="header">立即注册</nav>
      <section class="content">

        <div id="my-error" style="display:none;" class="alert alert-danger alert-dismissible fade in" role="alert">
          <div type="button" class="close">
            <span aria-hidden="true">&times;</span>
          </div>
          <p id="my-err-msg" style="width:200px;"></p>
        </div>

        <form class="" action="/signup" method="post">
          {{ csrf_field() }}
          <p>您同意 服务条款, 并确认您不属于美国公民或居民。</p>
          <p>直到获得美国联邦和州的许可证前，美国公民或居民将被禁止使用本平台。更多细节请 阅读全文 ，感谢您的耐心等候！</p>
          <p>为了确定您同意我们的服务条款，请提供您的电子邮件地址。</p>
          <div class="position">
            <label class="icon-envelope" for="email"></label>
            <input id="email" type="email" name="email" value="" placeholder="请输入电子邮件地址">
          </div>
          <input type="submit" name="submit" value="立即注册">
          <div class="others">
            <a class="right" href="/oauth">登入</a>
          </div>

          
          
        </form>
      </section>


      <script src="/js/jquery.js" charset="utf-8"></script>
      <script src="/js/bootstrap.min.js" charset="utf-8"></script>
      <script src="/js/myServer.js" charset="utf-8"></script>
      <script type="text/javascript">
          window.onload = () => {

            $("input[type='submit']").click(()=> {
              var userData = {
                email: $("#email").val(),
                _token:'{{csrf_token()}}',
                targetUrl: $("#targetUrl").val(),
              };

              window.ser.post("/signup", userData).done(data => {
                if (!data.errcode || data.errcode == "0") {
                   layer.msg(data.errmsg,{icon: 6},function(){
                       location.href = "/signup/verified/"+data.token;
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
