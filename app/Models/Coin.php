<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Market;
use App\Models\Trade;
use App\Models\Transaction;
use App\Models\Address;

class Coin extends Model
{
    protected $casts = [
        'to_btc' => '',
        'freeze' => 0,
        'for_sell' => 0,
        'address' => '',
    ];

	// to btc
	public function getToBtcAttribute()
    {
    	// btc
    	if($this->id == 100){
    		return 1;
    	}

        // 和卖方为BTC的关系
        $market = Market::where('sell_id', 100)
                        ->where('buy_id', $this->id)
                        ->first();
        if($market){
            // get trans
            $lastTransaction = Transaction::where('market_id', $market->id)
                                    ->where('action', 'sell')
                                    ->first();
            return !$lastTransaction? 0: 1/$lastTransaction->price;
        }


    	// 和买方为BTC的关系
    	$market = Market::where('sell_id', $this->id)
    					->where('buy_id', 100)
    					->first();
    	if($market){
            // get trans
            $lastTransaction = Transaction::where('market_id', $market->id)
                                    ->where('action', 'buy')
                                    ->first();
            return !$lastTransaction? 0: $lastTransaction->price;
        }

    	return 0;
    }


    // wallet envents
    public function walletEvents()
    {
        return $this->hasMany('App\Models\WalletEvent');
    }


    // 冻结
    public function getFreezeAttribute()
    {

        return $this->walletEvents()
                        ->where('user_id', \Auth::user()->id)
                        ->where('status', '!=', 'success')
                        ->where('status', '!=', 'return')
                        ->sum('amount');
    }

    // 挂单
    public function getForSellAttribute()
    {
        // 获取销售的市场
        $marketsSells = Market::where('sell_id', $this->id)->get();     // 卖单
        $marketBuys = Market::where('buy_id', $this->id)->get();        // 买单

        // 计算
        $sumPrice = 0;
        foreach($marketsSells as $market){
            $trades = Trade::where('market_id', $market->id)
                            ->where('user_id', \Auth::user()->id)
                            ->where('action', 'sell')
                            ->get();
            foreach($trades as $trade) { 
                $sumPrice += $trade->amount;
            }
        }
        foreach($marketBuys as $market){
            $trades = Trade::where('market_id', $market->id)
                            ->where('user_id', \Auth::user()->id)
                            ->where('action', 'buy')
                            ->get();
            foreach($trades as $trade) { 
                $sumPrice += $trade->amount * $trade->price;
            }
        }

        return $sumPrice;
    }

    // 发送地址
    public function getAddressAttribute()
    {
        // has addr
        $addr = Address::where('user_id', \Auth::user()->id)
                    ->where('coin_id', $this->id)
                    ->orderBy('id', 'asc')
                    ->first();
        // retun        
        if($addr){
            return $addr->address;
        }

        // 是否有空地址
        $addr = Address::where('user_id', null)
                    ->where('coin_id', $this->id)
                    ->orderBy('id', 'asc')
                    ->sharedLock()
                    ->first();
        // 有空地址 分配
        if($addr){
            $addr->user_id = \Auth::user()->id;
            if(!$addr->save()){
                return '';
            }

            return $addr->address;
        }

        // 无空地址 反空
        return '';

    }

}