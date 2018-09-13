<?php 


use Facebook\WebDriver\Remote\{DesiredCapabilities,RemoteWebDriver};

class Page
{
	public $pageText = '';
	public $image_path = '';

	function __construct($remote_driver_host,$save_path,$capabilities)
	{
		$this->redis = new \Redis();
		$this->redis->connect('redis-1');

		$this->driver = RemoteWebDriver::create($remote_driver_host,$capabilities,5000);
		$this->save_path = $save_path;
	}

	/**
	 * 获取之前的标识
	 * @return [type] [description]
	 */
	function getPrevCode($key)
	{
		if ($res = $this->redis->get($key)) {
			return $res;
		}

		if (file_exists($this->save_path)) {
			$res = file_get_contents($this->save_path);
			return $this->getCode($res);
		} else {
			return false;
		}
		
		

	}

	/**
	 * 获取标识位
	 * @param  [type] $driver [description]
	 * @return [type]         [description]
	 */
	public function getCode($html = false)
	{
		if ($html) {
			return md5($html);
		}
		

		return $this->code = md5($this->getPageText());
	}

	/**
	 * 日志
	 * @param  [type] $path [description]
	 * @return [type]       [description]
	 */
	public function log($path,$content)
	{
		 file_put_contents($path.date("Y-m-d").'.txt',date("H:i:s",time()).'-'.$content.PHP_EOL,FILE_APPEND);
	}

	/**
	 * 获取网页
	 * @param  [type] $host [description]
	 * @param  [type] $key  [description]
	 * @return [type]       [description]
	 */
	public function get($host,$key)
	{
	
		$this->driver->get($host);
		waitForAjax($this->driver);
		
		$before_code = $this->getPrevCode($key);
		$now = $this ->getCode();
		$this->redis->set($key,$now);
		if ($now != $before_code || $before_code == false) {
			$this->image();
			file_put_contents($this->save_path,$this->getPageText());
		}
	}


	/**
	 * 调用driver的方法
	 * @param  [type] $call  [description]
	 * @param  [type] $parms [description]
	 * @return [type]        [description]
	 */
	public function  __call($call,$parms) {

		return $this->driver->$call($parms);
	}


	/**
	 * 获取页面文本
	 * @return [type] [description]
	 */
	public function getPageText()
	{
		if ($this->pageText) {
			return $this->pageText;
		}
		return $this->pageText = $this->driver->findElement(Facebook\WebDriver\WebDriverBy::tagName('html'))->getText();
	}

	private function image()
	{
		if ($this->image_path) {
			$this->driver->takeScreenshot($this->image_path);
		}
		
	}

	public function setImagePath($path)
	{
		$this->image_path = $path;
	}
}