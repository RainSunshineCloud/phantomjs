<?php 

/**
 * 获取请求浏览器相关设置
 * @param  boolean $loadImages [description]
 * @param  [type]  $userAgent  [description]
 * @return [type]              [description]
 */
function getCapabilities($loadImages = false,$userAgent=1)
{
	$arr = [
            'browserName' => 'ie',
            'platform' => 'window',
        ];

	switch ($userAgent) {
		case 1:
			$userAgent = 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36';
		case 2:
			$userAgent = 'Mozilla/5.0 (X11; U; Linux x86_64; zh-CN; rv:1.9.2.10) Gecko/20100922 Ubuntu/10.10 (maverick) Firefox/3.6.10';
		case 3:
			$userAgent = 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/534.57.2 (KHTML, like Gecko) Version/5.1.7 Safari/534.57.2';
	}

	$arr['phantomjs.page.settings.loadImages'] = $loadImages;
	$arr['phantomjs.page.settings.userAgent'] = $userAgent;

	
	$capabilities = new \Facebook\WebDriver\Remote\DesiredCapabilities($arr);


}

/**
 * 判断判断ajax 是否完成
 * @param  [type] $driver    [description]
 * @param  string $framework [description]
 * @return [type]            [description]
 */
function waitForAjax($driver, $framework='jquery')
{
    // javascript framework
    switch($framework){
        case 'jquery':
            $code = "return jQuery.active;"; break;
        case 'prototype':
            $code = "return Ajax.activeRequestCount;"; break;
        case 'dojo':
            $code = "return dojo.io.XMLHTTPTransport.inFlight.length;"; break;
        default:
            throw new Exception('Not supported framework');
    }
    // wait for at most 30s, retry every 2000ms (2s)
    $driver->wait(30, 200)->until(
        function ($driver) use($code) {
            return !$driver->executeScript($code);
        }
    );
}

/**
 * 刷新页面
 * @param  [type] $driver [description]
 * @param  [type] $before [description]
 * @param  [type] $redis  [description]
 * @return [type]         [description]
 */
function refreshPage($driver,$before,$redis)
{
  $end_time = time() + 60;
    $page = $driver->getPageSource();
    $text = $html->plaintext;
    if ($now != $before || $before == false) {
      $redis->set('getcontent',$now);
      $redis->set('getcontent_time',time());
      file_put_contents('/app/getcontent/test.html',$page);
      $before = $now;
    }
    $driver->navigate()->refresh();
}
