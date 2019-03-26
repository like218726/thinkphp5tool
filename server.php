<?php
use Workerman\Lib\Db;
use Workerman\MySQL\Connection;
use Workerman\Worker;
require_once __DIR__ . '/vendor/workerman/workerman/Autoloader.php';
require_once __DIR__ . '/vendor/workerman/workerman/MYSQL/Connection.php';

// 创建一个Worker监听2345端口，使用http协议通讯
$worker = new Worker("websocket://0.0.0.0:2346");

// 启动4个进程对外提供服务
$worker->count = 4;
 	global $dbconnection;    
 	$database = require_once './application/database.php';
 	
 	$dbconnection = new Connection($database['hostname'], $database['hostport'], $database['username'], $database['password'], $database['database']);

   // worker进程启动后创建一个text Worker以便打开一个内部通讯端口
$worker->onWorkerStart = function ($worker){

    // 开启一个内部端口，方便内部系统推送数据，Text协议格式 文本+换行符
    $inner_text_worker = new Worker('text://0.0.0.0:2345');
    $inner_text_worker->onMessage = function ($connection, $buffer){
        // 使用uid判断需要向哪个页面推送数据
        // $data数组格式，里面有uid，表示向那个uid的页面推送数据
        $data = json_decode($buffer, true);
        $uid = $data['uid'];

        // 通过workerman，向uid的页面推送数据
        $res = sendMessageByUid($uid, json_encode($data['data']));
    };
    $inner_text_worker->listen();
};


$worker->uidConnections = [];

// 当有客户端发来消息时执行的回调函数, 客户端需要表明自己是哪个uid
$worker->onMessage = function ($connection, $data){
// 客户端传递的是json数据
        $message_data = json_decode($data, true);  
		global $dbconnection;        
        if(!$message_data)
        {
            return ;
        }   	
 		
        // 根据类型执行不同的业务
        switch($message_data['type'])
        {
        	
            // 客户端回应服务端的心跳
            case 'ping':  
            	global $worker;
			    if(!isset($connection->uid)){
			        // 没验证的话把第一个包当做uid（这里为了方便演示，没做真正的验证）
			        $connection->uid = $message_data['device_id'];
			        $worker->uidConnections[$connection->uid] = $connection;
			
			        $connection->send("{'type':'succ', 'message':'连接成功'}");
			    }
                break ;        
 
            // 客户端登录 message格式: {type:login, name:xx, room_id:1} ，添加到客户端，广播给所有客户端xx进入聊天室           
            case 'register':
            	$data = '';
            	if (!$message_data['device_id']) {
            		$data = "{'type':'reg', 'device_id':".$message_data['device_id'].", 'status':'error'}";
            	}
            	
				$result = $dbconnection->update('tp_device')->cols(array('is_android'=>'1'))->where('id='.$message_data['device_id'])->query();
          
				if ($result) {
            		$data = "{'type':'reg', 'device_id':".$message_data['device_id'].", 'status':'ok'}";
            	} else {
            		$data = "{'type':'reg', 'device_id':".$message_data['device_id'].", 'status':'error2'}";
            	}
            	$connection->send($data);
            	break;    

            //登陆	
            case 'login':
            	$id = $message_data['device_id'];	
            	$result = $dbconnection->select('*')->from('tp_device')->where("id= '$id' ")->limit('1')->row(); 
				if (!$result || $result['status'] == 20 ) {
					$data = "{'type':'server_login','device_id':".$id.",'status':'error'}";
				}
				if ($result['is_login'] == 0) {
					$dbconnection->update('tp_device')->cols(array('is_login'=>'1'))->where('id='.$id)->query();
					$data = "{'type':'server_login','device_id':".$id.",'status':'ok'}";
				} else {
					$data = "{'type':'server_login','device_id':".$id.",'status':'error'}";
				}
				
				$connection->send($data);
				break;
               	
            default:
  				;
  				break;   	
        }
};

$worker->onClose = function ($connection){
    global $worker;
    global $dbconnection;   
    /*if(isset($connection->uid)){
        unset($worker->uidConnections[$connection->uid]);
    }*/
    $create_time = date('Y-m-d H:i:s',time());
    $dbconnection->query("INSERT INTO `tp_scan_log` ( `device_id`,`member_id`,`type`,`create_time`)VALUES ( $connection->uid, '0', 'close','".$create_time."')");
};

/**
 * 
 * 群发
 * @param unknown_type $message
 */
function broadCast($message){
    global $worker;
    foreach ($worker->uidConnections as $connection){
        $connection->send($message);
    }
}

// 向客户端某一个uid推送数据
function sendMessageByUid($uid, $message){
    global $worker;
    global $dbconnection;   
    if(isset($worker->uidConnections[$uid])){
    	
    	$create_time = date('Y-m-d H:i:s',time());
    	$dbconnection->query("INSERT INTO `tp_scan_log` ( `device_id`,`member_id`,`type`,`create_time`)VALUES ( $uid, '0', 'send','".$create_time."')");

        $connection = $worker->uidConnections[$uid];
        $connection->send($message);
        
        return true;
    }else{
    	$create_time = date('Y-m-d H:i:s',time());
    	$dbconnection->query("INSERT INTO `tp_scan_log` ( `device_id`,`member_id`,`type`,`create_time`)VALUES ( $uid, '0', 'send_error','".$create_time."')");
    }
    return false;
}

Worker::runAll();