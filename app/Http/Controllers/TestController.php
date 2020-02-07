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

    public function sign2()
    {
        print_r($_POST);
        $key = "ljx";
        $data = $_POST['data'];
        $sign = $_POST['sign'];
        echo '<br>';
        //计算签名
        $check = md5($data.$key);
        echo '接受签名：'.$check;
        //对比接收过来的签名
        if($check != $sign){
            echo '验签失败';
        }else{
            echo '验签成功';
        }
    }
}
