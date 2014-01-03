<?php
$wechatObj = new wechat();
$wechatObj->responseMsg();
class wechat {
	public function responseMsg() {

		//---------- 接 收 数 据 ---------- //

		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"]; //获取POST数据

		//用SimpleXML解析POST过来的XML数据
		$postObj = simplexml_load_string($postStr,'SimpleXMLElement',LIBXML_NOCDATA);

		$fromUsername = $postObj->FromUserName; //获取发送方帐号（OpenID）
		$toUsername = $postObj->ToUserName; //获取接收方账号
		$keyword = trim($postObj->Content); //获取消息内容
		$time = time(); //获取当前时间戳


		//---------- 返 回 数 据 ---------- //

		//返回消息模板
		$textTpl = "<xml>
		<ToUserName><![CDATA[%s]]></ToUserName>
		<FromUserName><![CDATA[%s]]></FromUserName>
		<CreateTime>%s</CreateTime>
		<MsgType><![CDATA[%s]]></MsgType>
		<Content><![CDATA[%s]]></Content>
		<FuncFlag>0</FuncFlag>
		</xml>";

		$msgType = "text"; //消息类型
		$contentStr = Weather($keyword);//'我是管理员，请问有什么可以帮到您吗？'; //返回消息内容

		//格式化消息模板
		$resultStr = sprintf($textTpl,$fromUsername,$toUsername,
		$time,$msgType,$contentStr);
		echo $resultStr; //输出结果
	}
}

/*
获取自动机器人回复函数
*/
function SimSimi($keyword) {

	//----------- 获取COOKIE ----------//
	$url = "http://www.simsimi.com/";
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_HEADER,1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	$content = curl_exec($ch);
	list($header, $body) = explode("\r\n\r\n", $content);
	preg_match("/set\-cookie:([^\r\n]*);/iU", $header, $matches);
	$cookie = $matches[1];
	curl_close($ch);

	//----------- 抓 取 回 复 ----------//
	$url = "http://www.simsimi.com/func/req?lc=ch&msg=$keyword";
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_REFERER, "http://www.simsimi.com/talk.htm?lc=ch");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_COOKIE, $cookie);
	$content = json_decode(curl_exec($ch),1);
	curl_close($ch);

	if($content['result']=='100') {
		$content['response'];
		return $content['response'];
	} else {
		return '我还不会回答这个问题...';
	}
}
/*
获取天气子函数
*/
function Weather($keyword){
	$url = "http://api2.sinaapp.com/search/weather/?appkey=0020130430&appsecert=fa6095e113cd28fd&reqtype=text&keyword=".urlencode($keyword);
	$weatherJson = file_get_contents($url);
	$weather = json_decode($weatherJson, true);
	if($weather['text']['content']){
	    return $weather['text']['content'];
	}else{
	    return "不存在该地点的天气，你的输入有误！";
	}
}

?>
