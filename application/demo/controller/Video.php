<?php

namespace app\demo\controller;

class Video extends Base {
	
	public function index() {
		if ($this->request->isGet()) {
			return $this->fetch();
		}
	}
}