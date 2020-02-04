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
                    return json_encode(['msg'=>'登陆成功','error'=>'0','token'=>$token,'user_id'=>$info['user_id']],JSON_UNESCAPED_UNICODE);
                } else {
                    return json_encode(['msg'=>'密码有误','error'=>'201'],JSON_UNESCAPED_UNICODE);
                }
            } else {
                return json_encode(['msg'=>'用户名有误','error'=>'201'],JSON_UNESCAPED_UNICODE);
            }
        }else if(!empty($user_email)){
            $info = User::where(['user_email' => $user_email])->first();
            if ($info) {
                $pass = $info->user_pwd;
                if (password_verify($user_pwd, $pass)) {
                    Redis::set('token',$token,604800);
                    return json_encode(['msg'=>'登陆成功','error'=>'0','token'=>$token,'user_id'=>$info['user_id']],JSON_UNESCAPED_UNICODE);
                } else {
                    return json_encode(['msg'=>'密码有误','error'=>'201'],JSON_UNESCAPED_UNICODE);
                }
            } else {
                return json_encode(['msg'=>'邮箱有误','error'=>'201'],JSON_UNESCAPED_UNICODE);
            }
        }else if(!empty($user_tel)){
            $info = User::where(['user_tel' => $user_tel])->first();
            if ($info) {
                $pass = $info->user_pwd;
                if (password_verify($user_pwd, $pass)) {
                    Redis::set('token',$token,604800);
                    return json_encode(['msg'=>'登陆成功','error'=>'0','token'=>$token,'user_id'=>$info['user_id']],JSON_UNESCAPED_UNICODE);
                } else {
                    return json_encode(['msg'=>'密码有误','error'=>'201'],JSON_UNESCAPED_UNICODE);
                }
            } else {
                return json_encode(['msg'=>'手机号有误','error'=>'201'],JSON_UNESCAPED_UNICODE);
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
        $user_name = $request->input('user_name');
        $name = User::where(['user_name'=>$user_name])->first();
        if(!empty($name)){
            return json_encode(['msg'=>'用户名不能一致','error'=>'202'],JSON_UNESCAPED_UNICODE);
            die;
        }

        $user_email = $request->input('user_email');
        $email = User::where(['user_email'=>$user_email])->first();
        if(!empty($email)){
            return json_encode(['msg'=>'邮箱不能一致','error'=>'202'],JSON_UNESCAPED_UNICODE);
            die;
        }

        $user_tel = $request->input('user_tel');
        $tel = User::where(['user_tel'=>$user_tel])->first();
        if(!empty($tel)){
            return json_encode(['msg'=>'手机号不能一致','error'=>'202'],JSON_UNESCAPED_UNICODE);
            die;
        }

        $user_pwd = $request->input('user_pwd');
        $user_pwd1 = $request->input('user_pwd1');
        if($user_pwd1 != $user_pwd){
            return json_encode(['msg'=>'密码不一致','error'=>'202'],JSON_UNESCAPED_UNICODE);
            die;
        }

        $user_pwd = password_hash($user_pwd, PASSWORD_BCRYPT);
        $res = User::create([
            'user_name'=>$user_name,
            'user_tel'=>$user_tel,
            'user_email'=>$user_email,
            'user_pwd'=>$user_pwd,
        ]);
        if ($res) {
            return json_encode(['msg'=>'注册成功','error'=>'0'],JSON_UNESCAPED_UNICODE);
        } else {
            return json_encode(['msg'=>'注册失败','error'=>'201'],JSON_UNESCAPED_UNICODE);
        }
    }

    public function token(Request $request)
    {
        if(empty($_SERVER['HTTP_TOKEN'])){
            return json_encode(['error'=>203,'msg'=>'Token Not Valid!']);
        }
        $token = $_SERVER['HTTP_TOKEN'];
        $user_id = $_SERVER['HTTP_UID'];

        if ($token != Redis::get('token')) {
            return json_encode(['error'=>203,'msg'=>'Token Not Valid!']);
        } else {
            $data = User::get();
            return json_encode(['error'=>0,'msg'=>'ok','data'=>$data]);
        }

    }

    public function auth()
    {
        $token = $_POST['token'];
        $user_id = $_POST['user_id'];
        if(empty($user_id) || empty($token)){
            $response = [
                'error'=>203,
                'msg'=>'Uid Not Valid!'
            ];
            return $response;
        }
        if ($token != Redis::get('token')) {
            $response = [
                'error'=>203,
                'msg'=>'Token Not Valid!'
            ];
        } else {
            $response = [
                'error'=>0,
                'msg'=>'ok'
            ];
        }
        return $response;
    }

    public function love()
    {
        for($i=1;$i<=520;$i++){
            echo $i.'.我爱你';
        }
    }
}
