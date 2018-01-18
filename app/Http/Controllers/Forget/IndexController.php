<?php

namespace App\Http\Controllers\Forget;

use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Jenssegers\Agent\Agent;


use App\Models\User; 
use App\Models\MailToken;
use App\Models\Event;
class IndexController extends Controller
{

    // __construct
    public function __construct()
    {
    }

    // index 忘记密码的页面，输入邮箱
    public function index(Request $request){
        return view('forget.index');
    }

    // doIndex  发送邮箱
    public function doIndex(Request $request){
        // agrs
        $email = $request->input('email', '');
        // validate
        $validate = Validator::make($request->all(), [
            'email' => 'required|email',
        ], [
            'email.required'=> trans('login.signup_email_required'),
            'email.email'=> trans('login.signup_email_email'),
        ]);
 
        // validate fail
        if($validate->fails()){
            return response()->json([
                'errcode' => 1001,
                'errmsg' => $validate->errors()->first(),
            ]);
        }

        // find email
        $user = User::where('email', $email)->first();

        // 无该地址
        if (!$user) {
            return response()->json([
                'errcode' => 1002,
                'errmsg' => trans('login.email_not_found'),
            ]);
        }

        // send mail
        $mailToken = new MailToken;
        $mailToken->user_id = $user->id;
        $mailToken->token = str_random(32);

        // success 1
        if($mailToken->save()){

            // sendmail
            Mail::send('emails.forget', [
                'email'=> $user->email,
                'token'=> $mailToken->token,
            ], function ($message) use ($user) {
                $message->subject(trans('sendmail.forget_subject'));
                $message->to($user->email);
            });

        }

        // success
        return response()->json([
                'errcode' => 0,
                'errmsg' => trans('login.forget_password_set'),
                'token' => $mailToken->token ,
            ]);
    }


    // verified 修改密码的页面
    public function verified(Request $request){
        $token=$request->route('token');
        dump($token);
        return view('forget.verified',compact('token'));
    }

    // verified do 修改密码的操作
    public function doVerified(Request $request){
         // agrs
        $token = $request->input('token', '');
        $password = $request->input('password', '');
        // validate
        $validate = Validator::make($request->all(), [
            'token' => 'required',
            'password' => 'required|between:6,32',
        ], [
            'token.required'=> trans('login.password_token_required'),
            'password.required'=> trans('login.password_required'),
            'password.between'=> trans('login.password_between'),
        ]);
           // validate fail
        if($validate->fails()){
            return response()->json([
                'errcode' => 1001,
                'errmsg' => $validate->errors()->first(),
            ]);
        }
         // 验证TOKEN
        $token = MailToken::where('token', $token)
                            // ->where('created_at', 'like', date('Y-m-d').'%')
                            ->first();

        // // TOKEN 无效
        if(!$token){
            return response()->json([
                'errcode' => 1002,
                'errmsg' => trans('login.password_token_error'),
            ]);
        }
        // save
        $user = $token->user;
        $user->password = Hash::make($password);
         // fail
        if (!$user->save()) {
            return response()->json([
                'errcode' => 9001,
                'errmsg' => trans('system.9001'),
            ]);
        }
       // 删除token
        $token->delete();
         // 加入事件
        $agent = new Agent();
        $event = new Event;
        $event->user_id = $user->id;
        $event->ipaddr = $request->getClientIp();
        $event->device = $agent->browser().' on '.$agent->platform();
        $event->action = 'PASSWORD_SET';
        $event->save();
         // success
        return response()->json([
                'errcode' => 0,
                'errmsg' => trans('login.forget_password_save'),
            ]);
        // return redirect('/oauth');
    }

 
}
