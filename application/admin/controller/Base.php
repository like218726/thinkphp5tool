<?php

namespace app\admin\controller;

use think\Controller;

class Base extends Controller {
   
	//省市区选择
	function address_select($el_id = "order_time",$tag = ''){
		$html = '<span id="addr_select" init="0" data=""></span>';		
		$html .= "<script>city_select('addr_select','',0);</script>";
		return $html;
	}    
    
}