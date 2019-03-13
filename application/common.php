<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

/**
 * 导出excel
 * @param $strTable	表格内容
 * @param $filename 文件名
 */
function downloadExcel($strTable,$filename)
{
    header("Content-type: application/vnd.ms-excel");
    header("Content-Type: application/force-download");
    header("Content-Disposition: attachment; filename=".$filename."_".date('Y-m-d').".xls");
    header('Expires:0');
    header('Pragma:public');
    echo '<html><meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'.$strTable.'</html>';
}

/**
 * 将读取到的目录以数组的形式展现出来
 * @return array
 * opendir() 函数打开一个目录句柄，可由 closedir()，readdir() 和 rewinddir() 使用。
 * is_dir() 函数检查指定的文件是否是目录。
 * readdir() 函数返回由 opendir() 打开的目录句柄中的条目。
 * @param array $files 所有的文件条目的存放数组
 * @param string $file 返回的文件条目
 * @param string $dir 文件的路径
 * @param resource $handle 打开的文件目录句柄
 */
function read_all($path)
{
    //定义一个数组
    $files = array();
    //检测是否存在文件
    if (is_dir($path)) {
        //打开目录
        if ($handle = opendir($path)) {
            //返回当前文件的条目
            while (($file = readdir($handle)) !== false) {
                //去除特殊目录
                if ($file != "." && $file != "..") {
                    //判断子目录是否还存在子目录
                    if (is_dir($path . "/" . $file)) {
                        //递归调用本函数，再次获取目录
                        $files[$file] = read_all($path . "/" . $file);
                    } else {
                        //获取目录数组
                        $file_path = $path . "/" . $file;
                        $file_path = iconv('GBK','utf-8',$file_path);
                        $file = iconv('GBK','utf-8',$file);
                        $path = iconv('GBK','utf-8',$path);
                        $file_arr['file_name'] = $file;
                        $file_arr['file_path'] = $path;
                        $files[] = $file_arr;
                    }
                }
            }
            //关闭文件夹
            closedir($handle);
            //返回文件夹数组
            return $files;
        }
    }
}

/**
 * 
 * 模糊化
 * @param mixed $data 数组(一维,二维,多维)或者字符串[默认情况下要模糊化的值必须大于12位]
 * @param int $start 开始位置
 * @param int $end 结束几位
 * @param int $length 要模糊化位数
 * @param int $encrypt 模糊化样式
 * @return mixed 数组或者字符串
 * 
 */
function fuzzy($data, $start=6, $end=6, $length=0, $encrypt='*') { 
	if (!$data) return false;
	if (is_array($data)) {
		$level = arrayLevel($data); 
		$arr = array();
		if ($level == 1) {
			foreach ($data as $key=>$value) {
				$arr[$key] = strlen($value)>($start+$end) ? encryptStr($value, $start, $end, $length, $encrypt) : $value;
			}
		} else {
			foreach ($data as $key=>$value) { 
				if (is_array($value)) {
					$arr[$key] = fuzzy($value, $start, $end, $length=0, $encrypt);
				} else {
					$arr[$key] = strlen($value)>($start+$end) ? encryptStr($value, $start, $end, $length, $encrypt) : $value;
				}
			}
		}
		return $arr;
	} else {
		$temp_len = $length>0 ? $length: (strlen($data) - $start - $end); 
		if ($temp_len>0) {
			$str = $data ? substr_replace($data, str_repeat($encrypt, $temp_len), $start, $temp_len) : "";
		} else {
			$str = $data;
		}
		return $str;
	}
}

/**
 * 
 * 加密字符串
 * @param string $string 需要加密的字符串
 * @param int $start 开始位置
 * @param int $end 结尾数
 * @param int $length 替换的长度
 * @param string $encrypt 页面显示样式
 * 
 */
function encryptStr($string, $start = 6, $end = 6, $length=1, $encrypt = '*') {
	$temp_len = $length>0 ? $length: (strlen($string) - $start - $end); 
    return $string ? substr_replace($string, str_repeat($encrypt, $temp_len), $start, $temp_len) : "";
}

/**
 * 返回数组的维度
 * @param [type] $arr [description]
 * @return [type] [description]
 */
function arrayLevel($arr)
{
    $al = array(0);
    aL($arr, $al);
    return max($al);
}

function aL($arr, &$al, $level = 0)
{
    if (is_array($arr)) {
        $level++;
        $al[] = $level;
        foreach ($arr as $v) {
            aL($v, $al, $level);
        }
    }
}

/**
 * 
 * 判断是否为国内IP
 * return bool
 * 
 */
function check_ip () {
	ini_set('user_agent','Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 2.0.50727; .NET CLR 3.0.04506.30; GreenBrowser)');	
	if(getenv("HTTP_CLIENT_IP")){
		$ip = getenv("HTTP_CLIENT_IP");
	}else if(getenv("HTTP_X_FORWARDED_FOR")){
		$ip = getenv("HTTP_X_FORWARDED_FOR");
	}else if(getenv("REMOTE_ADDR")){
		$ip = getenv("REMOTE_ADDR");
	}else{
		$ip = "NULL";
	}
	
	if ($ip == '127.0.0.1') {
		return true;
	} else {
		$url = "http://ip.taobao.com/service/getIpInfo.php?ip=".$ip;
		$resp = file_get_contents($url);
		if(!empty($resp))
		{
			$info = json_decode($resp, true);
			if($info['data']['country'] != '中国')
			{
				return false;
			} else {
				return true;
			}
		}	
	}
}

/**
  * 发送验证码短信
  * @param $mobile  手机号码
  * @param $code    验证码
  * @return bool    短信发送成功返回true失败返回false
  *
  * 验证码模板：${product}用户注册验证码：${code}。请勿将验证码告知他人并确认该申请是您本人操作！
  */
function send_sms_reg( $mobile, $code)
{ 
	$alicloud = config('ALICLOUD');	
	$sms_template_code = $alicloud['TemplateCode']; //模板编号
	$sign_name = $alicloud['SignName']; //签名名称
	if(empty($sms_template_code) || empty($sign_name)){
		return false;
	}
	$product = $alicloud['sms_product'];        
	// 短信模板参数拼接字符串,如果模板里有$product变量就开启82行,反之83号
//	$sms_cfg = json_encode(array('code'=>$code,'product'=>$product));  
	$sms_cfg = json_encode(array('code'=>$code));  
	// 发送验证码短信
	$sms_send = sendSms($mobile, $sms_template_code, $sms_cfg, $sign_name);
	$success = $sms_send->Code == 'OK' ? true : false; //成功标识
	$return_code = $sms_send->Code; //返回的编码
	$sub_code = $sms_send->Message; //错误码

	if ($success)
	{
		$db = model("VerificationCode"); 
		$map = array(
			'telphone' => $mobile,
			'verification_code' => $code,
			'is_used' => 0,
			'create_time' => array('gt', time() - 10 * 60),
 		);
		$data = $db->where($map)->find();

		// 没有就插入验证码,供验证用
		empty($data) && $db->insert(array('telphone' => $mobile, 'verification_code' => $code, 'create_time' => time(), 'ip'=>get_client_ip()));
		return array(true,'','');
	} else {
		return array(false,$return_code,$sub_code);
	}
}

/**
 * 
 * 发送短信
 * @param unknown_type $mobile
 * @param unknown_type $sms_template_code
 * @param unknown_type $param_str
 * @param unknown_type $sign_name
 */
function sendSms($mobile, $sms_template_code, $param_str, $sign_name='注册验证')
{
	vendor('dysms.Sms');
	$sms = new Sms();

	$alicloud = config('ALICLOUD');	
	$sms->appkey = $alicloud['AccessKeyId'];
	$sms->secretKey = $alicloud['AccessKeySecret'];

	$res = $sms->sendSms($mobile, $sms_template_code, $param_str, $sign_name);
	return $res;
}

/**
 * 获取客户端IP地址
 * @param integer $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
 * @param boolean $adv 是否进行高级模式获取（有可能被伪装） 
 * @return mixed
 */
function get_client_ip() {
	$ip = '';
	if (isset($_SERVER['HTTP_CDN_SRC_IP']) && $_SERVER['HTTP_CDN_SRC_IP']) {
		$ip = $_SERVER['HTTP_CDN_SRC_IP'];
	} else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR']) {
		$allIps = $_SERVER['HTTP_X_FORWARDED_FOR'];
		$allIpsArray = explode(',', $allIps);
		$ip = $allIpsArray[0];
	} else if (isset($_SERVER['HTTP_X_REAL_IP']) && $_SERVER['HTTP_X_REAL_IP']) {
		$ip = $_SERVER['HTTP_X_REAL_IP'];
	} else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR']) {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	if (empty($ip)) {
		$ip = '0.0.0.0';
	}
	return $ip;
}