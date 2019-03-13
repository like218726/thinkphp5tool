<?php

namespace app\demo\controller;

use think\Controller;

class Dir extends Base {
	
	public function index() {
		if ($this->request->isGet()) {
			$path = 'D:\music';
			
			return $this->ajaxSuccess(read_all($path));
		}
	}
	
}