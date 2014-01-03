<?php
/**
  * 微信公众平台实现自动回复天气预报功能
  *@link http://www.phpddt.com
  */
 
//define your token
define("TOKEN", "phpddt");
$wechatObj = new wechatCallbackapiTest();
$wechatObj->responseMsg();
 
class wechatCallbackapiTest
{
 
    public function responseMsg()
    {
		//get post data, May be due to the different environments
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
 
      	//extract post data
		if (!empty($postStr)){
                
              	$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
                $fromUsername = $postObj->FromUserName;
                $toUsername = $postObj->ToUserName;
                $keyword = trim($postObj->Content);
                $time = time();
                $textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							<FuncFlag>0</FuncFlag>
							</xml>";             
				if(!empty( $keyword ))
                {
              		$msgType = "text";
                	$url = "http://api2.sinaapp.com/search/weather/?appkey=0020130430&appsecert=fa6095e113cd28fd&reqtype=text&keyword=".urlencode($keyword);
                        $weatherJson = file_get_contents($url);
                        $weather = json_decode($weatherJson, true);
                        if($weather['text']['content']){
                            $contentStr = $weather['text']['content'];
                        }else{
                            $contentStr = "不存在该地点的天气，你的输入有误！";
                        }
                      
                	$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                	echo $resultStr;
                }else{
                	echo "Input something...";
                }
 
        }else {
        	echo "";
        	exit;
        }
    }
 
}
 
?>
