<?php
namespace app\index\controller;

class Index
{
    public function index()
    {
        return '<style type="text/css">*{ padding: 0; margin: 0; } .think_default_text{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:)</h1><p> ThinkPHP V5<br/><span style="font-size:30px">十年磨一剑 - 为API开发设计的高性能框架</span></p><span style="font-size:22px;">[ V5.0 版本由 <a href="http://www.qiniu.com" target="qiniu">七牛云</a> 独家赞助发布 ]</span></div><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_bd568ce7058a1091"></thinkad>';
    }
	public function login() {
		$nonce = $_GET['nonce'];
		$token = "wowangle222";
		$timestamp = $_GET['timestamp'];
		$echostr = isset($_GET['echostr'])?$_GET['echostr']:'';
		$signature = $_GET['signature'];
		$array = array($nonce, $timestamp, $token);
		sort($array);
		$str = sha1(implode('', $array));
		if ($str == $signature && $echostr) {
			echo $echostr;
			exit;
		} else if ($str == $signature) {
			$this->responseMsg();
		}
	}

	public function responseMsg() {
		//xml data
		$postArr = file_get_contents('php://input');
		$postObj = simplexml_load_string($postArr);
		/*$postObj->ToUserName = '';
		$postObj->FromUserName = '';
		$postObj->CreateTime = '';
		$postObj->MsgType = '';
		$postObj->Event = '';*/
		//is the event
		if (strtolower($postObj->MsgType) == 'event') {
			if (strtolower($postObj->Event == 'subscribe')) {
				$toUser = $postObj->FromUserName;
				$fromUser = $postObj->ToUserName;
				$time = time();
				$msgType = 'text';
				$content = '我是許錚博　我爲自己代言';
				$template = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							</xml>";
				$info = sprintf($template, $toUser, $fromUser, $time, $msgType, $content);
				echo $info;
				if (file_put_contents("/tmp/log/1.log", "info:".$info.'\n'."postarr".$postArr.'\n', FILE_APPEND));
			}
		}
	}
}
