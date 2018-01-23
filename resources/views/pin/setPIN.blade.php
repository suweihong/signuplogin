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
      <nav class="header">重设PIN码</nav>
      <section class="content">

        <div id="my-error" style="display:none;" class="alert alert-danger alert-dismissible fade in" role="alert">
          <div type="button" class="close">
            <span aria-hidden="true">&times;</span>
          </div>
          <p id="my-err-msg" style="width:200px;"></p>
        </div>

        <form class="" action="/forget" method="post">
          {{ csrf_field() }}
          <p>请输入您6位新的PIN码</p>
          <div class="position">
            <label class="icon-lock" for="email"></label>
            <input id="pin" type="pin" name="text" placeholder="新的PIN码">
          </div>
          <input type="submit" name="submit" value="提交">


        </form>
      </section>


      <script src="/js/jquery.js" charset="utf-8"></script>
      <script src="/js/bootstrap.min.js" charset="utf-8"></script>
      <script src="/js/myServer.js" charset="utf-8"></script>
      <script type="text/javascript">
          window.onload = () => {

            $("input[type='submit']").click(()=> {
              var userData = {
                pin: $("#pin").val(),
                _token:'{{csrf_token()}}',
                targetUrl: $("#targetUrl").val(),
              };

              window.ser.post("/pin", userData).done(data => {
                if (!data.errcode || data.errcode == "0") {
               layer.msg(data.errmsg,{icon:6},function(){
                    console.log(data);
                    // window.location.href = "";
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
