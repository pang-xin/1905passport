<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\User;
use Illuminate\Support\Facades\Redis;

class ApiController extends Controller
{
    public function login(Request $request)
    {
        $user_name = $request->input('user_name');
        $user_tel = $request->input('user_tel');
        $user_email = $request->input('user_email');
        $user_pwd = $request->input('user_pwd');
        $token = $request->header('token');
        $token = md5($token);
        if(!empty($user_name)){
            $info = User::where(['user_name' => $user_name])->first();
            if ($info) {
                $pass = $info->user_pwd;
                if (password_verify($user_pwd, $pass)) {
                    Redis::set('token',$token,604800);
                    return json_encode(['find'=>'登陆成功','code'=>'200']);
                } else {
                    return json_encode(['find'=>'密码有误','code'=>'201']);
                }
            } else {
                return json_encode(['find'=>'邮箱有误','code'=>'202']);
            }
        }else if(!empty($user_email)){
            $info = User::where(['user_email' => $user_email])->first();
            if ($info) {
                $pass = $info->user_pwd;
                if (password_verify($user_pwd, $pass)) {
                    Redis::set('token',$token,604800);
                    return json_encode(['find'=>'登陆成功','code'=>'200']);
                } else {
                    return json_encode(['find'=>'密码有误','code'=>'201']);
                }
            } else {
                return json_encode(['find'=>'邮箱有误','code'=>'202']);
            }
        }else if(!empty($user_tel)){
            $info = User::where(['user_tel' => $user_tel])->first();
            if ($info) {
                $pass = $info->user_pwd;
                if (password_verify($user_pwd, $pass)) {
                    Redis::set('token',$token,604800);
                    return json_encode(['find'=>'登陆成功','code'=>'200']);
                } else {
                    return json_encode(['find'=>'密码有误','code'=>'201']);
                }
            } else {
                return json_encode(['find'=>'邮箱有误','code'=>'202']);
            }
        }

    }
    /**
     * @param Request $request
     * @return false|string
     * 注册
     */
    public function reg(Request $request)
    {
        $data = $request->all();
        if($data['user_pwd1'] != $data['user_pwd']){
            return json_encode(['find'=>'密码不一致','code'=>'202']);
        }
        $data['user_pwd'] = password_hash($data['user_pwd'], PASSWORD_BCRYPT);
        $res = User::create([
            'user_name'=>$data['user_name'],
            'user_tel'=>$data['user_tel'],
            'user_email'=>$data['user_email'],
            'user_pwd'=>$data['user_pwd'],
        ]);
        if ($res) {
            return json_encode(['find'=>'注册成功','code'=>'200']);
        } else {
            return json_encode(['find'=>'注册失败','code'=>'201']);
        }
    }

    public function token(Request $request)
    {
        $token = $request->input('token');
        $token = md5($token);
        if ($token != Redis::get('token')) {
            echo 'token和登陆时存入的token不一致';
            die;
        } else {
            $data = User::get();
            return json_encode($data);
        }

    }
}
