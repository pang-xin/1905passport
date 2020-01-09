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
        $user_email = $request->input('user_email');
        $user_pwd = $request->input('user_pwd');
        $info = User::where(['user_email' => $user_email])->first();
        if ($info) {
            $pass = $info->user_pwd;
            if (password_verify($user_pwd, $pass)) {
                $token = md5($info['user_id'],time());
                Redis::expire($token,604800);
                return json_encode(['find'=>'登陆成功','code'=>'200']);
            } else {
                return json_encode(['find'=>'密码有误','code'=>'201']);
            }
        } else {
            return json_encode(['find'=>'邮箱有误','code'=>'202']);
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
        $data['user_pwd'] = password_hash($data['user_pwd'], PASSWORD_BCRYPT);
        $res = User::create($data);
        if ($res) {
            return json_encode(['find'=>'注册成功','code'=>'200']);
        } else {
            return json_encode(['find'=>'注册失败','code'=>'201']);
        }
    }
}
