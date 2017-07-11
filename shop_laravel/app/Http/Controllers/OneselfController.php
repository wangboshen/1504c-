<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2017/7/8
 * Time: 16:05
 */

namespace App\Http\Controllers;


use Cache;
use Illuminate\Support\Facades\Input;

use Symfony\Component\HttpFoundation\Request;
use Illuminate\Support\Facades\DB;


class OneselfController extends Controller
{
    //查询登录的身份
    public function oneself(Request $request){
        $id=Cache::get('id');
        if(empty($id)){
            $datas="1";
            $datas=json_encode($datas);
        }else{
            $callback=$request->input('callback');
            $data=DB::table('username')->where('uid',$id)->first();
            $datas=json_encode($data);
        }

        return $callback.'('.$datas.')';
    }

    //返回学历的信息
    public function xueli(Request $request){
        $callback=$request->input('callback');
        $class=DB::table('class')->take(9)->get();
        $classs=$this->getsort($class);
        $classes=json_encode($classs);
        return $callback.'('.$classes.')';
    }

    //封装的无限极递归的参数
    public function getsort($data,$pid=0,$level=''){
        static  $arr=array();
        foreach($data as $k=>$v){
            if($v['pid']==$pid){
                $v['level']=$level;
                $arr[]=$v;
                $this->getsort($data,$v['cid'],$level.'★★★');
            }
        }
        return  $arr;
    }

    //开始添加课程的信息（个人的）
    public function selfclass(Request $request){
        $callback=$request->input('callback');
        $uid=Cache::get('id');
        $id=$request->input('id');
        $update=DB::table('username')->where('uid',$uid)->update(['class'=>$id]);
        if($update){
            return $callback.'(1)';
        }
    }

//    //一个公共的方法看是否登录
//    public function denglu(){
//        $id=Cache::get('id');
//        if(empty($id)){
//            return 1;
//        }else{
//            return 2;
//        }
//    }


    public function pays_money(Request $request){
        $money=$request->input('money');
        $callback=$request->input('callback');
        Cache::put('money',$money,60);
        return $callback.'(1)';

    }

    //支付的过程
    public function pays(Request $request){
            $buy_email=$request->input('buyer_email');
            $callback=$request->input('callback');
            if(!empty($buy_email)){
                $uid=Cache::get('id');
                $moneys=Cache::get('money');
                $time=date('Y-m-d H:i:d',time());
                $sel=DB::table('study_pay')->where('uid',$uid)->first();
                if($sel){
                    $pay=DB::table('study_pay')->where('uid',$uid)->update([
                        'money'=>$sel['money']+$moneys,
                    ]);
                }else{
                    $pay=DB::table('study_pay')->insert([
                        'pay_id'=>'',
                        'uid'=>$uid,
                        'datetime'=>$time,
                        'money'=>$moneys
                    ]);
                }
                if($pay){
                    return view('oneself/success');
                }
            }
    }
    //登录后查询她的余额
    public function yu_e(Request $request){
        $uid=Cache::get('id');
        $callback=$request->input('callback');
        $data=DB::table('study_pay')->where('uid',$uid)->first();
        $money=json_encode($data['money']);
        return $callback.'('.$money.')';
    }

    //问题的返回
    public function wenti(Request $request){
        $callback=$request->input('callback');
        $data=DB::table('jiang')->get();
        $datas=json_encode($data);
        return $callback.'('.$datas.')';
    }

    //看答案是否准确
    public function wenti_da(Request $request){
        //1.首先看是不是已经获取了积分
        //2.如果没有那么进行对问题对就加积分
        $callback=$request->input('callback');
        $wenzi=$request->input('wenzi');
        $ti_id=$request->input('ti_id');
        $uid=Cache::get('id');
        $sel=DB::table('ji_log')->where('uid',$uid)->get();
        if($sel){
            $hao=1;
        }else{
            $ti=DB::table('jiang')->where('jiang_id',$ti_id)->first();
            $da=$ti['jiang_da'];
            if($wenzi==$da){
                DB::table('ji_log')->insert([
                    'jilog_id'=>'',
                    'uid'=>$uid,
                    'datetime'=>date('Y-m-d H:i:s',time()),
                    'jiang_id'=>$ti_id
                ]);
                DB::table('username')->where('uid',$uid)->increment('ji_fen',20);
                $hao=0;
            }else{
                $hao=2;
            }
        }
        $stats=json_encode($hao);
        return $callback.'('.$stats.')';
    }








}