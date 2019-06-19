<?php

namespace App\Model;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Login extends Model
{
    //
	protected $table = 'zzz';

	//查询单条数据
    public static function find($data)
    {
    	$res = DB::table('zzz')->where($data)->first();	

    	return $res;
    }

    public static function select($data)
    {
    	$res = DB::table('zzz')->where(['name' => $data])->first();

    	return $res;
    }
}
