<?php

namespace app\demo\controller;

class Md5Decode extends Base {
	
	public function index() {
		if ($this->request->isGet()) {
			$md5 = "c1c95b382230eb9e27a60c4baceb5f2e";
			$uid = "hhp-ImZRY";
			$token = strtolower(md5('1878399009'));
			$url = "http://www.ttmd5.com/do.php?c=Api&m=crack&uid=$uid&token=$token&cipher=$md5";
			$data = file_get_contents($url);
			return $this->ajaxSuccess('成功', 1, $data);		
		}
	}
}