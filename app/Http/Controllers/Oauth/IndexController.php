<?php

namespace App\Http\Controllers\Oauth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Models\Wallet;
use App\Models\Event;
 
class IndexController extends Controller
{

    // __construct
    public function __construct()
    {
    }

    // Oauth 授权登录
    public function oauth(Request $request){

        // 检查app 和 callback 参数
        // $app = $request->input('app');
        // $callback = $request->input('callback');

        // // empty
        // if(empty($app) || empty($callback)){
        //     abort(403);
        // }

        // // session save
        // session('oauth_app', $app);
        // session('oauth_callback', $callback);

        // view
        return view('oauth.index');
    }

    // doOAuth 登录的密码正确
    public function doOauth(Request $request){
         // agrs
        $agent = new Agent();
        $email = $request->input('email', '');
        $password = $request->input('password', '');
        $lang = $request->input('lang', 'en');

        // 获得用户
        $user = User::where('email', $email)->first();

        // 无用户
        if(!$user){
            return response()->json([
                    'errcode' => 1001,
                    'errmsg' => trans('login.user_not_found'),
                ]);
        }

        // 密码错误
        if(!Hash::check($password, $user->password)){

            // 加入事件
            $event = new Event;
            $event->user_id = $user->id;
            $event->ipaddr = $request->getClientIp();
            $event->device = $agent->browser().' on '.$agent->platform();
            $event->action = 'SESSION_FAIL';
            $event->save();
            return response()->json([
                    'errcode' => 1002,
                    'errmsg' => trans('login.user_login_fail'),
                ]);
        }

        // 修改api_token
        $user->api_token = str_random(32);
        $user->lang = $lang;

        // 完善钱包信息
        Wallet::fixNullData($user->id);

        // 加入事件
        $event = new Event;
        $event->user_id = $user->id;
        $event->ipaddr = $request->getClientIp();
        $event->device = $agent->browser().' on '.$agent->platform();
        $event->action = 'SESSION_CREATE';
        $event->save();

        // fail
        if (!$user->save()) {
            return response()->json([
                'errcode' => 9001,
                'errmsg' => trans('system.9001'),
            ]);
        }


        // 判断是否开启GOOGLE AUTH

        // if open
         // return response()->json([
         //        'api_token'=> '',
         //        'second_auth'=> true,
         //        'custom_lang'=> $user->lang,
         //    ]);
        if($user['secret']=='')
        {
           return response()->json([
                    'errcode' => 1001,
                    'errmsg' => trans('login.login_double_open'),
                ]);
        }else{
             return response()->json([
                    'errcode' => 0,
                    'errmsg' => trans('login.login_double_operate'),
                    'api_token'=>$user->api_token,
                ]);
        }
        // else 
        
        // return response()->json([
        //     'api_token'=> $user->api_token,
        //     'second_auth'=> false,
        //     'custom_lang'=> $user->lang,
        // ]);
        // return redirect('/double/auth');
        // dd('验证登录是否成功，if success [goto double auth] else [back]');
    }

 
}
