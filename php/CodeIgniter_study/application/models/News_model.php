<?php
// defined('BASEPATH') OR exit('No direct script access allowed');
class News_model extends CI_Model{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();  //加载数据库，默认加载的事默认数据库，若指定加载的数据库database(db数组中指定数据库key，true)访问默认数据库是就用$this->db 
	}
	
	public function get_news($slug = FALSE){
		if($slug === FALSE){
			$query=$this->db->get('news');  //get(表名)
			return $query->result_array();
		}
		
		$query=$this->db->get_where('news',array('slug'=>$slug)); //get_where(表名，条件数组array('key'=>value))
		return $query->row_array();
	}
	
	public function add_news(){
		
	}
	
}