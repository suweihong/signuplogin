<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Events\UserCreated;

class User extends Model
{

    /**
     * 模型的事件映射。
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'created' => UserCreated::class,
    ];

    // 钱包
    public function wallet()
    {
        return $this->hasMany('App\Models\Wallet');
    }

    // 个人消息
    public function notes()
    {
        return $this->hasMany('App\Models\Note');
    }

    // 动态事件
    public function events()
    {
        return $this->hasMany('App\Models\Event');
    }

    // 充值记录
    public function sendEvents()
    {
        return $this->hasMany('App\Models\WalletEvent')->where('action', 'send');
    }

    // 提现记录
    public function receiveEvents()
    {
        return $this->hasMany('App\Models\WalletEvent')->where('action', 'receive');
    }


    // sends
	public function sends()
    {
        return $this->hasMany('App\Models\AddressSend');
    }

    // receives
	public function receives()
    {
        return $this->hasMany('App\Models\AddressReceive');
    }

    //获取mailtoken
    public function hasManymailtoken()
{
    return $this->hasMany('App\Models\MailToken');
}
}