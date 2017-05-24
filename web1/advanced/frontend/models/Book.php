<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 2017/5/24
 * Time: 9:26
 */

namespace frontend\models;

use yii\base\Model;
use yii\db\ActiveRecord;

class Book extends ActiveRecord{

    public static function tableName(){

        return 'cate_book';
    }


}