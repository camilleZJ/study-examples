<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$this->load->view('welcome_message');
	}
	
	public function test(){
		echo ENVIRONMENT;  //开发环境

		//开启分析器：TRUE-开启   FALSE-关闭
		$this->output->enable_profiler(TRUE);
		
		//对分析器里的分析项局部进行关闭FALSE（开启TRUE）
		$sections = array(
				'queries' => FALSE   //querie是数据库查询分析项
		);
		$this->output->set_profiler_sections($sections);
		//全局开启或关闭某个分析项在
		
	}
}
