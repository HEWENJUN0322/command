<?php

namespace App\Http\Controllers\Study;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Study\Guess;
use Illuminate\Support\Facades\DB;

class LotteryController extends Controller
{
    //
	//抽奖页面
    public function lottery(){

    	return view('study.lottery.index');
    }


    //执行抽奖的操作
    public function doLottery(Request $request){

    	$phone = $request->input('phone');
    	$user_id = $request->input('user_id');

    	$start = date('Y-m-d 18:00:00',time());
    	$end = date('Y-m-d 21:00:00',time());

    	$return = [
    		'code' => 2000,
    		'msg' => '成功'
    	];

    	if(empty($phone)){
    		$return = [
    			'code' => 4001,
    			'msg' => '手机号不能为空'
    		];
    		return json_encode($return);
    	}
    	// dd($phone);
    	//检测用户信息
    	$user = DB::table('study_lottery_user')->where('phone',$phone)->first();
    	// dd($user);
    	if(empty($user)){
    		$return = [
    			'code' => 4002,
    			'msg' => '用户不存在'
    		];

    		return json_encode($return);
    	}

    	//检测用户今日的抽奖次数
    	$records = DB::table('study_lottery_record')->where('user_id',$user_id)->where('created_at',date('Y-m-d'))->count();
    	// dd($records);
    	if($records >= 3){
    		$return = [
    			'code' => 4003,
    			'msg' => '今日抽奖次数已用完，请明天再来'
    		];

    		return json_encode($return);
    	}
    	//判断活动时间
    	if(time() > strtotime($end) || time() < strtotime($start)){
    		$return = [
    			'code' => 4004,
    			'msg' => '请在活动期间内抽奖'
    		];
    		return json_encode($return);
    	}

    	/**********************[执行抽奖]*************************/

    	//获取奖品列表
    	$lottery = DB::table('study_lottery')->get()->toArray();
    	// dd($lottery);
    	$lotterys = $precents = [];
    	//组装奖品的数据
    	foreach ($lottery as $key => $value) {
    		//奖品
    		$lotterys[$value->id] = [
    			'lottery_name' => $value->lottery_name
    		];
    		//抽奖概率
    		$precents[$value->id] = $value->precent;
    	}
    	// dd($lotterys,$precents);

    	//概率求和
    	$preSum = array_sum($precents);
    	// dd($preSum);

    	//中奖概率求和
    	$result = '';

    	//计算中奖
    	foreach ($precents as $k => $v) {
    		//随机概率
    		$preCurrent = mt_rand(1, $preSum);
    		//如果设置的中奖概率小于随机值，中奖了
    		if($v > $preCurrent){
    			$result = $k;
    			break;
    		}else{
    			$preSum = $preSum - $v;
    		}
    	}
    	
    	//添加中将记录
    	$data = [
    		'user_id' => $user_id,
    		'lottery_id' => $result,
    		'created_at' => date('Y-m-d')
    	];
    	DB::table('study_lottery_record')->insert($data);

    	$return['msg'] = $lotterys[$result]['lottery_name'];
    	// dd($return);
    	return json_encode($return);
    }
}
