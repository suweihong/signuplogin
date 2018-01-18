<?php

namespace App\Http\Controllers\Double;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \PHPGangsta_GoogleAuthenticator;
 

use App\Models\User;
use App\Models\MailToken;
class IndexController extends Controller
{

    // __construct
    public function __construct()
    {
    }
    public function test(Request $request)
    {
       $session=$request->session()->all();
        dump( $session);
    }
    // init 开启二次验证
    public function init(Request $request){
        $token=$request->route('token');
        $ga = new PHPGangsta_GoogleAuthenticator();
        $secret = $ga->createSecret();
        $qrCodeUrl = $ga->getQRCodeGoogleUrl('Blog', $secret);
        $oneCode = $ga->getCode($secret);
        dump($oneCode);
        return view('double.init',compact('secret','qrCodeUrl','token'));
    }

    // doInit 注册时判断二次验证码是否正确
    public function doInit(Request $request){
        $oneCode=$request->input('oneCode','');
        $secret=$request->input('secret','');
        $token=$request->input('token','');
        // 验证TOKEN
        $mailToken = MailToken::where('token', $token)->first();
        // TOKEN 无效
        if(!$mailToken){
            return response()->json([
                'errcode' => 1001,
                'errmsg' => trans('login.password_token_error'),
            ]);
        }
        // success
        $user_id= $mailToken->user->id;
        //判断验证码是否正确
        $ga = new PHPGangsta_GoogleAuthenticator();
        $checkResult = $ga->verifyCode($secret, $oneCode, 2);    // 2 = 2*30sec clock tolerance
        if ($checkResult) {
            $user=user::where('id',$user_id)->first();
            $user->secret=$secret;
            $res=$user->save();
            if($res){
            $mailToken->delete();
            return response()->json([
                'errcode' => 0,
                'errmsg' => trans('login.double_signup_success'),
            ]);
            }else{
                return 'false';
            }
        } else {
           return response()->json([
                'errcode' => 1001,
                'errmsg' => trans('login.double_signup_fail'),
            ]);
        }
        // dd('验证双重认证设置完成，成功则跳回callback 失败则 重试');
    }

    // auth 
    public function auth(Request $request){
        $api_token=$request->route('api_token');
        return view('double.auth',compact('api_token'));
    }


    // authd 登录时判断验证码是否正确
    public function doAuth(Request $request){
        $oneCode=$request->input('oneCode');
        $api_token=$request->input('api_token');
         // 验证TOKEN
        $user=user::where('api_token',$api_token)->first();
        // TOKEN 无效
        if(! $user){
            return response()->json([
                'errcode' => 1001,
                'errmsg' => trans('login.password_token_error'),
            ]);
        }
        // success
        $secret=$user['secret'];
        //判断验证码是否正确
        $ga = new PHPGangsta_GoogleAuthenticator();
        $checkResult = $ga->verifyCode($secret, $oneCode, 2);  
        if($checkResult){
             return response()->json([
                'errcode' => 0,
                'errmsg' => trans('login.double_login_sucess'),
            ]);

        }else{
             return response()->json([
                'errcode' => 1001,
                'errmsg' => trans('login.double_login_fail'),
            ]);
        }
        // echo ('验证双重认证是否成功，成功则跳回callback 失败则 重试');
    }

 
}
