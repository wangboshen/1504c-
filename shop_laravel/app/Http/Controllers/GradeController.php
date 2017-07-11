<?php
namespace app\Http\Controllers;

use Illuminate\Routing\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Grade;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Max-Age: 1000');
class GradeController extends Controller{

    //年级
    public function grade(){
        $model = new Grade();
        $data = $model->getone('pid','=','0');
        echo json_encode($data);
    }
   //年级级别查询
    public function gclass(Request $request){
        $cid = $request->input('cid');
        $model = new Grade();
        $data = $model->getone('pid','=',"$cid");
        echo json_encode($data);
    }

    //课程查询
    public function subject(Request $request){
        $cid = $request->input('cid');
        $model = new Grade();
        $data = $model->getone('pid','=',"$cid");
        echo json_encode($data);
    }

    //课程详情分类
    public function dofenlei(Request $request){
        $cid = $request->input('cid');
        $model = new Grade();
        $data = $model->subject('class_title','class_id','=',"$cid");
        echo json_encode($data);
    }

    //全部等级
    public function allrank(Request $request){
        $cid = $request->input('cid');
       // $cid = 25;
        $model = new Grade();
        $data = $model->subject('class_jie','class_id','=',"$cid");
       // print_r($data);
        echo json_encode($data);
    }

    //分类等级
    public function fenrank(Request $request){
        $title_id = $request->input('title_id');
        $model = new Grade();
        $data = $model->subject('class_jie','title_id','=',"$title_id");
        echo json_encode($data);

    }

    //分类详情
    public function finfo(Request $request){
        $jie_id = $request->input('jie_id');
        $model = new Grade();
        $data = $model->subject('class_jie','jie_id','=',"$jie_id");
        echo json_encode($data);
    }

    //所有详情
    public function info(Request $request){
        $cid = $request->input('class_id');
        $model = new Grade();
        $data = $model->subject('class_jie','class_id','=',"$cid");
        echo json_encode($data);
    }

    /*
     * 首页详情
     *
     */

    public function indexinfo(){
        $model = new Grade();
        $data = $model->subject('class_title','status','=','1');
        echo json_encode($data);
    }

    //在勤学网的同学
    public function classmate(){
        $model = new Grade();
        $data = $model->limit('username',4);
        echo json_encode($data);
    }

}