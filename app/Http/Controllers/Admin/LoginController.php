<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Login;

class LoginController extends Controller
{
    //

    public function login(Request $request)
    {
    	$return = [
    		'code' => 2000,
    		'msg'  => '登录成功'
     	];

     	$params = $request->all();

     	if ($params['name'] == '') {

     		$return = [
     			'code' => 4001,
     			'msg'  => '用户名不能为空'
     		];

     		return json_encode($return);
     	}

     	if ($params['password'] == '') {

     		$return = [
     			'code' => 4002,
     			'msg'  => '密码不能为空'
     		];

     		return json_encode($return);
     	}

     	unset($params['_token']);

     	$res = Login::select($params['name']);

     	if (!$res) {

     		$return = [
     			'code' => 4003,
     			'msg'  => '用户不存在'
     		];
     		// dd('111');die;
     		return json_encode($return);
     	}

     	$result = Login::find($params);

     	if (!$result) {

     		$return = [
     			'code' => 4004,
     			'msg'  => '输入密码有误'
     		];

     		return json_encode($return);
     	}

     	return json_encode($return);
    	
    }
}
