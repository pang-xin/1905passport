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
        $token = md5(time());
        if(!empty($user_name)){
            $info = User::where(['user_name' => $user_name])->first();
            if ($info) {
                $pass = $info->user_pwd;
                if (password_verify($user_pwd, $pass)) {
                    Redis::set('token',$token,604800);
                    return json_encode(['msg'=>'登陆成功','code'=>'200','token'=>$token,'user_id'=>$info['user_id']],JSON_UNESCAPED_UNICODE);
                } else {
                    return json_encode(['msg'=>'密码有误','code'=>'201'],JSON_UNESCAPED_UNICODE);
                }
            } else {
                return json_encode(['msg'=>'邮箱有误','code'=>'202'],JSON_UNESCAPED_UNICODE);
            }
        }else if(!empty($user_email)){
            $info = User::where(['user_email' => $user_email])->first();
            if ($info) {
                $pass = $info->user_pwd;
                if (password_verify($user_pwd, $pass)) {
                    Redis::set('token',$token,604800);
                    return json_encode(['msg'=>'登陆成功','code'=>'200','token'=>$token,'user_id'=>$info['user_id']],JSON_UNESCAPED_UNICODE);
                } else {
                    return json_encode(['msg'=>'密码有误','code'=>'201'],JSON_UNESCAPED_UNICODE);
                }
            } else {
                return json_encode(['msg'=>'邮箱有误','code'=>'202'],JSON_UNESCAPED_UNICODE);
            }
        }else if(!empty($user_tel)){
            $info = User::where(['user_tel' => $user_tel])->first();
            if ($info) {
                $pass = $info->user_pwd;
                if (password_verify($user_pwd, $pass)) {
                    Redis::set('token',$token,604800);
                    return json_encode(['msg'=>'登陆成功','code'=>'200','token'=>$token,'user_id'=>$info['user_id']],JSON_UNESCAPED_UNICODE);
                } else {
                    return json_encode(['msg'=>'密码有误','code'=>'201'],JSON_UNESCAPED_UNICODE);
                }
            } else {
                return json_encode(['msg'=>'邮箱有误','code'=>'202'],JSON_UNESCAPED_UNICODE);
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
        $this->validate($request, [
            'user_name' => 'required|unique:user',
            'user_email' => 'required|unique:user',
            'user_tel' => 'required|unique:user',
        ],[
            'user_name.unique'=>'用户名称不能一致',
            'user_name.required'=>'用户名称不能为空',
            'user_email.unique'=>'邮箱称不能一致',
            'user_email.required'=>'邮箱称不能为空',
            'user_tel.unique'=>'手机号称不能一致',
            'user_tel.required'=>'手机号称不能为空',
        ]);


        $data = $request->all();
        if($data['user_pwd1'] != $data['user_pwd']){
            return json_encode(['msg'=>'密码不一致','code'=>'202'],JSON_UNESCAPED_UNICODE);
        }
        $data['user_pwd'] = password_hash($data['user_pwd'], PASSWORD_BCRYPT);
        $res = User::create([
            'user_name'=>$data['user_name'],
            'user_tel'=>$data['user_tel'],
            'user_email'=>$data['user_email'],
            'user_pwd'=>$data['user_pwd'],
        ]);
        if ($res) {
            return json_encode(['msg'=>'注册成功','code'=>'200'],JSON_UNESCAPED_UNICODE);
        } else {
            return json_encode(['msg'=>'注册失败','code'=>'201'],JSON_UNESCAPED_UNICODE);
        }
    }

    public function token(Request $request)
    {
        if(empty($_SERVER['HTTP_TOKEN'])){
            return json_encode(['code'=>203,'msg'=>'Token Not Valid!']);
        }
        $token = $_SERVER['HTTP_TOKEN'];
        $user_id = $_SERVER['HTTP_UID'];

        if ($token != Redis::get('token')) {
            return json_encode(['code'=>203,'msg'=>'Token Not Valid!']);
        } else {
            $data = User::get();
            return json_encode(['code'=>0,'msg'=>'ok','data'=>$data]);
        }

    }
}
