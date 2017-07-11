<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use DB;
class Grade extends Model{

    //搜索单条
    function getone($where,$a,$b){
        return DB::table('class')->where($where,$a,$b)->get();
    }

    //搜索分类
    function subject($table,$where,$a,$b){
        return DB::table($table)->where($where,$a,$b)->get();
    }

    //limit
   function limit($table,$l){
       return DB::table($table)->limit($l)->get();
   }
}