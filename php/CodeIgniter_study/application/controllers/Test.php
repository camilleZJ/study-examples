<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller {

	public function index()
	{

		$memcache=new Memcache();
		$memcache->connect('127.0.0.1','11211');
// 		$memcache->set('name','zhaojing');
		echo $memcache->get('name');
	}
	
	public function wap_alipay(){
		//加载config文件夹中的指定文件
		$config=$this->config->load('aliconf',TRUE);
		
		//获取加载的文件内容
		$appid=alipay_appid; //获取常量alipay_appid
	 	$this->config->item('app_id','aliconf');  //获取数组中的app_id
	 	
	 	//加载libraries文件夹中的指定文件
	 	$this->load->library('alipay/function.php',$config);
	 	$this->load->library('alipay/function.php');
	}
	
	
}
