<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function sign()
    {
        $data = $_GET['data'];//传过来的数据
        $sign = $_GET['sign'];//传过来的签名
        $key = "1905";

        $check = md5($data.$key);

        if($check != $sign){
            echo '验签失败';die;
        }else{
            echo '验签成功';
        }
    }
}
