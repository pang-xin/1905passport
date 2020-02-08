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

        $check = md5($data . $key);

        if ($check != $sign) {
            echo '验签失败';
            die;
        } else {
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
        $check = md5($data . $key);
        echo '接受签名：' . $check;
        //对比接收过来的签名
        if ($check != $sign) {
            echo '验签失败';
        } else {
            echo '验签成功';
        }
    }

    public function key_sign()
    {
        $sign = $_GET['sign'];
//        $sign = 'asdsadsadasdsad';
        //字典排序
        $data = $_GET['data'];
        print_r($_GET);


        //使用公钥验签
        $pub_key = storage_path('keys/pub.key');
        $pkeyid = openssl_pkey_get_public("file://" . $pub_key);
        $status = openssl_verify($data, base64_decode($sign), $pkeyid);
        var_dump($status);
        echo '<br>';

        if ($status) {
            echo '验签成功';
        } else {
            echo '验签失败';
        }
    }

    /**
     * 加密
     */
    public function decrypt()
    {
        //接受到传过来的加密数据
        $data = $_GET['data'];
        //使用base64转换为可解密的数据
        $data=base64_decode($data);
        $method = "AES-256-CBC";
        $key = "1905api";
        $iv = "WUSD8796IDjhkchd";
        //解密
        $enc_data = openssl_decrypt($data,$method,$key,OPENSSL_RAW_DATA,$iv);
        echo '解密：'.$enc_data;echo "<br>";
    }
}
