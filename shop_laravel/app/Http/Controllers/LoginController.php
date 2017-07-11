<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use DB;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use Cache;

class LoginController extends Controller{
  
    //登录验证
   
    public function login(Request $request){
        header("Access-Control-Allow-Origin:*");
        //星号表示所有的域都可以接受，
        header("Access-Control-Allow-Methods:POST");
        
        $data  = $request -> input();
        $uname = $data['uname'];
        $pwd   = md5($data['pwd']);
//          print_r($pwd);die;
        $token = isset($data['token'])?$data['token']:'';
        $time  = isset($data['time'])?$data['time']:'';
        
        //校验
        if(empty($token)){
            echo 2;die;
        }

        //校验码不正确
        if($token != md5($uname.$time)){
            echo 4;die;//校验不正确
        }

        //时间不得超过60秒
        $times = time();
        if($times - $time > 60){
            echo 3;die;
        }

        $res = DB::table('username')->where([
                    'uname' => "$uname",
                    'pwd'   => "$pwd"
                ])->first();
       
        if($res){
           /*Session::put('id', $res['uid']);  //将图形验证码的值写入到session中
           Session::save();*/

            Cache::put('id',$res['uid'],60);
           echo 1;die;
        }else{
           echo 0;die;
        }
    }

    //注册
    public function reg(Request $request){

        header("Access-Control-Allow-Origin:*");
        //星号表示所有的域都可以接受，
        header("Access-Control-Allow-Methods:POST");

        $uname = $request['uname'];
        $pwd   = $request['pwd'];
        $tel   = $request['tel'];
        $phone = $request['phone'];
        $email = $request['email'];
        // echo 6;die;
        if(empty($uname)){
            echo 2;die;
        }else{
            $reg = "/^[a-z_]\w{3,9}$/i";

            if(!preg_match($reg, $uname)){
                echo 2;die;
            }
        }

        //手机号
        if(empty($tel)){
            echo 2;die;
        }else{

            $reg = "/^1[7,3,8,5]\d{9}$/";

            if(!preg_match($reg, $tel)){
                echo 2;die;
            }
            //密码
            if(empty($pwd)){
                echo 2;die;
            }else{

                if(strlen($pwd)< 6 && strlen($pwd) >10){
                    echo 2;die;
                }
                //邮箱验证
                if(empty($email)){
                    echo 2;die;
                }else{
                    $reg = "/^[A-Za-z\d]+([-_.][A-Za-z\d]+)*@([A-Za-z\d]+[-.])+[A-Za-z\d]{2,4}$/";

                    if(!preg_match($reg, $email)){
                        echo 2;die;
                    }
                }
        //      //短信验证
            // if(empty($phone)){
                //  echo 2;die;
               //// echo 6;die;
            // }
            // 
                $res = DB::table('username')->insertGetId([
                            'uname'=>"$uname",
                            'pwd'=>md5("$pwd"),
                            'tel'=>"$tel",
                            'email'=>"$email"
                        ]);
//                print_r($res);die;
                if($res){
                     Cache::put('id',$res,60);
                    echo 1;die;
                }else{
                    echo 0;die;
                }
            }
        }
    }

    //短信验证
    public function duanxin(){
        header("Access-Control-Allow-Origin:*");
         //星号表示所有的域都可以接受，
        header("Access-Control-Allow-Methods:GET,POST");
        
        // echo 1;
        $tel  = Input::get('tel');
        $rand = rand(1000,9999);
        $url  = "http://api.k780.com";
        $data = array(
            'app'    => 'sms.send',
            'tempid' => '51015',
            'param'  => 'code%3d'.$rand,
            'phone'  => "$tel",
            'appkey' => '23760',
            'sign'   => 'e9d71c5357903f13aa5c68ddcf799cab',
        );
        $res  = $this -> curl($url,$data,true);
        $aa   = json_decode($res);
        $data = array('res' => $aa,'rand' => $rand);
        $data = json_encode($data);

        return $data;

    }
    //CURL
    function curl($url,$data = array(),$post = false){

        if(empty($url)){
            return false;
        }
        // 初始化一个 cURL 对象
        $curl = curl_init();
        // 设置header
        curl_setopt($curl, CURLOPT_HEADER, 0);
        // 设置cURL 参数，要求结果(1保存到字符串中)还是(0输出到屏幕上)。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        if($post){
            // post数据
            curl_setopt($curl, CURLOPT_POST, 1);
            // post的变量
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        }else{ 
            $data = http_build_query($data);
            $url  = $url.'?'.$data;
        }

        // 设置你需要抓取的URL
        curl_setopt($curl, CURLOPT_URL, $url);
        // 运行cURL，请求网页
        $html = curl_exec($curl);
        // 关闭URL请求
        curl_close($curl);

        return $html;
    }
    //滑动验证码
    public function qaptcha(){
        // echo  1;die;
         header("Access-Control-Allow-Origin:*");
        //星号表示所有的域都可以接受，
        header("Access-Control-Allow-Methods:GET,POST");


        session_start();
        $check                     = 'b46d1900d0a894591916ea94ea91bd2c';
        $aResponse['error']        = false;
        $aResponse['timeValidate'] = time();
        $_SESSION['iQapTcha']      = false;
            
        if(isset($_GET['action'])){
            if(htmlentities($_GET['action'], ENT_QUOTES, 'UTF-8') == 'qaptcha'){
                $_SESSION['iQapTcha'] = md5(gethostbyname($_SERVER['SERVER_NAME']).$aResponse['timeValidate'].$check);//md5(md5(加密获取当前域名的服务器端IP+时间戳+统一秘钥))
                //$aResponse['iQapTcha'] = $_SESSION['iQapTcha'];
            if($_SESSION['iQapTcha']){
                    echo json_encode($aResponse);
                }
            else{
                    $aResponse['error'] = 1;
                    echo json_encode($aResponse);
                }
            }else{
                $aResponse['error'] = 2;
                echo json_encode($aResponse);
            }
        }else{
            $aResponse['error'] = 3;
            echo json_encode($aResponse);
        }
    }



    //用户名验证唯一
    public function reg_uname(Request $request){
         header("Access-Control-Allow-Origin:*");
        //星号表示所有的域都可以接受，
        header("Access-Control-Allow-Methods:GET,POST");
         
        $uname = $request -> input('uname');

        if(empty($uname)){
                echo 2;die;
        }else{
            $reg = "/^[a-z_]\w{3,9}$/i";
            if(!preg_match($reg, $uname)){
                    echo 2;die;
            }
        }
        $res = DB::table('username')->where(['uname' => "$uname"])->first();
        if(empty($res)){
            echo 1;die;
        }else{
            echo 0;die;
        }
    }

    //邮箱验证唯一
    public function em(Request $request){
        header("Access-Control-Allow-Origin:*");
        //星号表示所有的域都可以接受，
        header("Access-Control-Allow-Methods:GET,POST");
        
        $email = $request->input('email');

        if(empty($email)){
            echo 3;die;
        }else{
            $reg="/^([a-z0-9]*[-_.]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[.][a-z]{2,3}([.][a-z]{2})?/i";
            // $reg="/^[a-z]([a-z0-9]*[-_]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[\.][a-z]{2,3}([\.][a-z]{2})?$/i";
            if(!preg_match($reg, $email)){
                echo 2;die;
            }

            $res = DB::table('username')->where(['email' => "$email"])->first();
            if(empty($res)){
                echo 1;die;
            }else{
                echo 0;die;
            }
        }
    }


    //手机号验证唯一
    public function reg_tel(Request $request){

        header("Access-Control-Allow-Origin:*");
        //星号表示所有的域都可以接受，
        header("Access-Control-Allow-Methods:GET,POST");

        $tel = $request->input('tel');
        if(empty($tel)){
            echo 3;die;
        }else{
            $reg = "/^1[7,3,8,5]\d{9}$/";
            if(!preg_match($reg, $tel)){
                echo 2;die;
            }
            $arr = DB::table('username')->where(['tel'=>"$tel"])->first();
            if(empty($arr)){
                echo 1;die;
            }else{
                echo 0;die;
            }
        }

    }


    //密码验证
    public function reg_pwd(Request $request){

        header("Access-Control-Allow-Origin:*");
        //星号表示所有的域都可以接受，
        header("Access-Control-Allow-Methods:GET,POST");

        $pwd = $request->input('pwd');
        if(empty($pwd)){
            echo 3;die;
        }else{
            $reg="/^[a-zA-Z\d_]{6,10}$/";
            if(preg_match($reg, $pwd)){
                echo 1;die;
            }else{
                echo 2;die;
            }
            
        }

    }

    //密码验证
    public function reg_cpwd(Request $request){

        header("Access-Control-Allow-Origin:*");
        //星号表示所有的域都可以接受，
        header("Access-Control-Allow-Methods:GET,POST");

        $cpwd = $request->input('cpwd');
        $pwd  = $request->input('pwd');
       if(empty($cpwd)){
            echo 3;die;
        }else{
            if($cpwd == $pwd){
                echo 1;die;
            }else{
                echo 2;die;
            }
            
        }

    }


}
?>
