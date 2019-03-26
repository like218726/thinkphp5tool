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
				'upload'=>['name'=>'增加图片上传和文件上传并进行验证','url'=>'https://github.com/like218726/thinkphp5toolkit/tree/master/Upload'],
				'JsSdk'=>['name'=>'JsSdk前端参数','url'=>'https://github.com/like218726/thinkphp5toolkit/tree/master/JsSdk'],
				'CreateToken'=>['name'=>'创建接口用户Token','url'=>'https://github.com/like218726/thinkphp5toolkit/tree/master/CreateToken'],
				'GetWechatPublicUserInfo'=>['name'=>'关注微信公众号后通过授权登陆自动获取其信息','url'=>'https://github.com/like218726/thinkphp5toolkit/tree/master/GetWechatPublicUserInfo'],
				'WechatMassage'=>['name'=>'公众号内消息推送','url'=>'https://github.com/like218726/thinkphp5toolkit/tree/master/WechatMassage'],
				'PopUpWindow'=>['name'=>'layui弹出窗口的内容为列表','url'=>'https://github.com/like218726/thinkphp5toolkit/tree/master/PopUpWindow'],
			],
			'demo'=>[
				'scandirlfile'=>['name'=>'遍历循环目录下的文件','url'=>'https://github.com/like218726/thinkphp5toolkit/tree/master/ScanDirlFile'],
				'fuzzy'=>['name'=>'模糊化字符串或者数组','url'=>'https://github.com/like218726/thinkphp5toolkit/tree/master/Fuzzy'],
				'video'=>['name'=>'视频播放','url'=>'https://github.com/like218726/thinkphp5toolkit/tree/master/Video'],
				'md5'=>['name'=>'MD5在线解密','url'=>'https://github.com/like218726/thinkphp5toolkit/tree/master/Md5'],
			],			
			'yuet'=>[
				'ThinkphpPaginate'=>['name'=>'Thinkphp5详情页里面Tab列表分页','url'=>'https://github.com/like218726/thinkphp5toolkit/tree/master/ThinkphpPaginate'],
				'address'=>['name'=>'地区联动','url'=>'https://github.com/like218726/thinkphp5toolkit/tree/master/Address'],
				'wxoauth'=>['name'=>'微信授权登陆','url'=>'https://github.com/like218726/thinkphp5toolkit/tree/master/WXOauth'],
				'message'=>['name'=>'短信发送','url'=>'https://github.com/like218726/thinkphp5toolkit/tree/master/Message'],
				'wxappletpay'=>['name'=>'短信发送','url'=>'https://github.com/like218726/thinkphp5toolkit/tree/master/WxAppletPay'],
			],
		];
		$this->assign("menu",$menu);
		return $this->fetch();
	}
}
