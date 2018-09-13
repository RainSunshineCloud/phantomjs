<?php

require_once(__DIR__.'/../vendor/autoload.php');
require_once(__DIR__.'/../lib/functions.php');
require_once(__DIR__.'/../lib/Page.class.php');

$host = 'http://www.pk10jhw.com/m/m11-1-3.html';
$path = __DIR__.'/pages/index.html';
$log_path = __DIR__.'/logs/';
$remote_driver_host = 'http://phantomjs-1:8910';

$module = new Page($remote_driver_host,$path,getCapabilities());

$end_time = time() + 60;
// for($i=0;$i >= 0;$i++){
	$module->setImagePath($log_path.'1.jpg');
	$module->get($host,'test');
	$module->log($log_path,$module->code);
	$module->driver->navigate()->refresh();
	// usleep(600000);
// 	if ($end_time -time() < 1){
// 	  	$module->driver->quit();
// 	  	break;
// 	}
// }



