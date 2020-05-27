<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pages extends CI_Controller {

	public function view($page = 'home')
	{
		if ( ! file_exists(APPPATH.'views/pages/'.$page.'.php'))
		{
			show_404();  // Whoops, we don't have a page for that!
		}
		
		$data['title'] = ucfirst($page); // Capitalize the first letter
		
		//按顺序加载所需的视图
		$this->load->view('templates/header', $data);  //view()的第二个参数用于向视图传递参数
		$this->load->view('pages/'.$page, $data);
		$this->load->view('templates/footer', $data);
	}
}
