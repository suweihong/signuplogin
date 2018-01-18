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
      <div class="return" style="left:50px;">
        <a><i class="icon-arrow-left"></i></a>
      </div>
      <nav class="header">绑定双重验证</nav>
      <section class="double-content">

        <div id="my-error" style="display:none;" class="alert alert-danger alert-dismissible fade in" role="alert">
          <div type="button" class="close">
            <span aria-hidden="true">&times;</span>
          </div>
          <p id="my-err-msg" style="width:200px;"></p>
        </div>

        <form class="" action="/double/init" method="post">
            {{ csrf_field() }}
            <div class="row">
              <p class="col-lg-offset-1 col-lg-1 col-md-offset-1 col-md-1 col-sm-1 col-xs-1">步骤1.</p>
              <p class="col-lg-10 col-md-10 col-sm-11 col-xs-11">在手机上安装谷歌双重验证器(Google Authenticator)。</p>
              <div class="col-lg-offset-2 col-lg-10 col-md-offset-2 col-md-11 col-sm-offset-1 col-sm-11 col-xs-offset-1 col-xs-11">
                <div class="row-1 col-lg-3 col-md-3 col-sm-3 col-xs-2" style="padding:0;">
                  <img src="/images/phoneOne.png" alt="">
                </div>
                <div class="row-2 col-lg-9 col-md-9 col-sm-9 col-xs-10 ver-phone">

                  <p><a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2">
                    IOS 下载
                  </a></p>
                  <p><a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2">
                    Android 下载
                  </a></p>
                </div>
              </div>
            </div>

            <div class="row">
              <p class="col-lg-offset-1 col-lg-1 col-md-offset-1 col-md-1 col-sm-1 col-xs-1">步骤2.</p>
              <p class="col-lg-10 col-md-10 col-sm-11 col-xs-11">使用谷歌双重验证器扫描下面的二维码。</p>
              <div class="col-lg-offset-2 col-lg-10 col-md-offset-2 col-md-11 col-sm-offset-1 col-sm-11 col-xs-offset-1 col-xs-11">
                <img src={{$qrCodeUrl}} alt="验证二维码">
              </div>
            </div>

            <div class="row">
              <p class="col-lg-offset-1 col-lg-1 col-md-offset-1 col-md-1 col-sm-1 col-xs-1">步骤3.</p>
              <p class="col-lg-10 col-md-10 col-sm-11 col-xs-11">请把下方的密码写下并保存在一个安全的地方:</p>
              <div class="col-lg-offset-2 col-lg-10 col-md-offset-2 col-md-11 col-sm-offset-1 col-sm-11 col-xs-offset-1 col-xs-11">
              <!-- secret 谷歌验证器的密钥 -->
                <mark id="secret">{{$secret}}</mark>
                <p>如果您的手机丢失时，需要此密码您才能访问您的账户。</p>
              </div>
            </div>

            <div class="row">
              <p class="col-lg-offset-1 col-lg-1 col-md-offset-1 col-md-1 col-sm-1 col-xs-1">步骤4.</p>
              <p class="col-lg-10 col-md-10 col-sm-11 col-xs-11">在下面输入您的谷歌双重验证器中显示的6位数字:</p>
              <div class="col-lg-offset-2 col-lg-10 col-md-offset-2 col-md-10 col-sm-offset-1 col-sm-11 col-xs-offset-1 col-xs-11">
                <div class="col-lg-5 col-md-5 col-sm-4 col-xs-3">
                  <!-- <div ref="verifyInput" class="verify-input" contenteditable="true" placeholder="000000">

                  </div> -->
                  <!-- <input type="text" maxlength="6" class="verify-input" onkeyup="this.value=this.value.replace(/\D/g,'')" onafterpaste="this.value=this.value.replace(/\D/g,'')" name="" value=""> -->
                  <!-- <input type="text" maxlength="6" class="verify-input" :value="oneCode" name="oneCode" v-model="value"> -->
                  <!-- <input type="text" maxlength="6" style="color:transparent;text-shadow:0 0 0 #fff;" class="verify-input" :value="formData.oneCode" name="oneCode" v-model="formData.oneCode" placeholder="000000"> -->
              <!-- oneCode 谷歌验证器的6位数字 -->
                  <input type="text" maxlength="6" class="verify" name="oneCode" id="oneCode" placeholder="000000">
                </div>
                <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
                  <input class="verify" type="submit" name="commit" value="开启">
                </div>
              </div>
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
                secret: '{{$secret}}',
                oneCode: $("#oneCode").val(),
                token:'{{$token}}',
                _token:'{{csrf_token()}}',
                targetUrl: $("#targetUrl").val(),
              };

              window.ser.post("/double/init", userData).done(data => {
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
