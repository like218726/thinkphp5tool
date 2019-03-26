<?php
namespace app\socket\controller;

use think\Controller;

class Index extends Controller
{
    //定义是否检测登录

    public function index()
    {
    	return view('index/index');
    }
}
