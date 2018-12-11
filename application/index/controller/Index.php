<?php
namespace app\index\controller;

use think\Controller;

class Index extends Controller
{

	public function index(){
		$menu = [
			'gwash'=>[
				'address'=>['name'=>'国家省市区乡镇','url'=>'https://github.com/like218726/thinkphp5toolkit/tree/master/Addr_select'],
				'excel_output'=>['name'=>'excel导出','url'=>'https://github.com/like218726/thinkphp5toolkit/tree/master/Excel_output'],
				'upload'=>['name'=>'文件上传','url'=>'https://github.com/like218726/thinkphp5toolkit/tree/master/Upload'],
				'JsSdk'=>['name'=>'JsSdk前端参数','url'=>'https://github.com/like218726/thinkphp5toolkit/tree/master/JsSdk'],
				'CreateToken'=>['name'=>'创建接口用户Token','url'=>'https://github.com/like218726/thinkphp5toolkit/tree/master/CreateToken'],
				'GetWechatPublicUserInfo'=>['name'=>'关注微信公众号后通过授权登陆自动获取其信息','url'=>'https://github.com/like218726/thinkphp5toolkit/tree/master/GetWechatPublicUserInfo'],
				'WechatMassage'=>['name'=>'关注微信公众号后通过授权登陆自动获取其信息','url'=>'https://github.com/like218726/thinkphp5toolkit/tree/master/WechatMassage'],
			],
			'demo'=>[
				'dir'=>['name'=>'遍历循环目录下的文件','url'=>'https://github.com/like218726/thinkphp5toolkit/tree/master/Addr_select'],
				'excel_output'=>['name'=>'excel导出','url'=>'https://github.com/like218726/thinkphp5toolkit/tree/master/Excel_output'],
			],
		];
		$this->assign("menu",$menu);
		return $this->fetch();
	}
}
