<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2017/5/24
 * Time: 9:24
 */

namespace frontend\controllers;


use frontend\models\Book;
use yii\web\Controller;
use Yii\db\Query;
class TestController extends Controller{

    public function actionShow(){
          $data=Book::find()->asArray()->all();
        return $this->render('show',['data'=>$data]);
    }

    public function actionDel(){
        $id=\Yii::$app->request->get('id');
        if($id){

            $del=Book::findOne($id);
            if($del !==null && !$del->delete()) {
                echo "删除失败";
            }else{
                echo "删除成功";
            }
        }

    }

}