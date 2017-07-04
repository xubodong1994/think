<?php
namespace app\index\controller;

class Index
{
    public function index()
    {
//        return '<style type="text/css">*{ padding: 0; margin: 0; } .think_default_text{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:)</h1><p> ThinkPHP V5<br/><span style="font-size:30px">十年磨一剑 - 为API开发设计的高性能框架</span></p><span style="font-size:22px;">[ V5.0 版本由 <a href="http://www.qiniu.com" target="qiniu">七牛云</a> 独家赞助发布 ]</span></div><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_bd568ce7058a1091"></thinkad>';
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


		if (file_put_contents("/tmp/1.log", (date('Y-m-d H:i:s', $timestamp))." ".$str." ".$signature." ".$echostr.PHP_EOL, FILE_APPEND));
		
		if ($str == $signature && $echostr) {
			echo $echostr;
			exit;
		} else {
			$this->responseMsg();
		}
	}

	private function responseMsg() {
		//xml data
		$postArr = file_get_contents('php://input');
		$postObj = simplexml_load_string($postArr);
		$template = "<xml>
					<ToUserName><![CDATA[%s]]></ToUserName>
					<FromUserName><![CDATA[%s]]></FromUserName>
					<CreateTime>%s</CreateTime>
					<MsgType><![CDATA[%s]]></MsgType>
					<Content><![CDATA[%s]]></Content>
					</xml>";
		$timestamp = time();
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
				if (file_put_contents("/tmp/1.log", (date('Y-m-d H:i:s', $timestamp))." ".$toUser." subscribes ".$fromUser.' '.$postArr.PHP_EOL, FILE_APPEND));

				$time = $timestamp;
				$msgType = 'text';
				$content = '我是'.$toUser.'　我爲自己代言';
				$info = sprintf($template, $toUser, $fromUser, $time, $msgType, $content);
				echo $info;
			}
		}

		if (strtolower($postObj->MsgType) == 'text' && trim($postObj->Content) == '图文') {

			$arr = array(
				array(
					'title'=>'imooc',
					'description'=>'imooc is very cool',
					'picUrl'=>'http://www.imooc.com/static/img/common/logo.png',
					'url'=>'http://www.imooc.com',
				),
				array(
					'title'=>'hao123',
					'description'=>'hao123 is very cool',
					'picUrl'=>'https://www.baidu.com/img/bdlogo.png',
					'url'=>'http://www.hao123.com',
				),
				array(
					'title'=>'qq',
					'description'=>'qq is very cool',
					'picUrl'=>'http://www.imooc.com/static/img/common/logo.png',
					'url'=>'http://www.qq.com',
				),
			);

			$template = "<xml>
					<ToUserName><![CDATA[%s]]></ToUserName>
					<FromUserName><![CDATA[%s]]></FromUserName>
					<CreateTime>%s</CreateTime>
					<MsgType><![CDATA[%s]]></MsgType>
					<ArticleCount>".count($arr)."</ArticleCount>
					<Articles>";
			foreach($arr as $k=>$v) {
				$template .="<item>
					<Title><![CDATA[".$v['title']."]]></Title>
					<Description><![CDATA[".$v['description']."]]</Description>
					<PicUrl><![CDATA[".$v['picUrl']."]]><PicUrl>
					<Url><![CDATA[".$v['url']."]]></Url>
					</item>";
			}

			$template .= "</Articles>
				</xml> ";
			$toUser = $postObj->FromUserName;
			$fromUser = $postObj->ToUserName;
			if (file_put_contents("/tmp/1.log", (date('Y-m-d H:i:s', $timestamp))." ".$toUser." send text ".$fromUser.' '.$postArr.PHP_EOL, FILE_APPEND));

			$time = $timestamp;
			$info = sprintf($template, $toUser, $fromUser, $time, 'news');
			echo $info;

		} else if (strtolower($postObj->MsgType) == 'text') {
			switch(trim($postObj->Content)) {
			case 1:
				$content = "1 is the num";
				break;
			case 2:
				$content = "2 is the num";
				break;
			case 3:
				$content = "<a href='http://www.imooc.com'>慕课</a>";
				break;
			default:
				$content = $postObj->Content;
				break;
			}
			$toUser = $postObj->FromUserName;
			$fromUser = $postObj->ToUserName;
			if (file_put_contents("/tmp/1.log", (date('Y-m-d H:i:s', $timestamp))." ".$toUser." send text ".$fromUser.' '.$postArr.PHP_EOL, FILE_APPEND));

			$time = $timestamp;
			$msgType = 'text';
			$info = sprintf($template, $toUser, $fromUser, $time, $msgType, $content);
			echo $info;
		}
	}
}
