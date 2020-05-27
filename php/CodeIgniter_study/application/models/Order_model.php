<?php
// defined('BASEPATH') OR exit('No direct script access allowed');
class Order_model extends CI_Model{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();  //加载数据库，默认加载的事默认数据库，若指定加载的数据库database(db数组中指定数据库key，true)访问默认数据库是就用$this->db 
	}
	
	//存贮订单信息
	public function add_order($data){
		
		$code=0;
		$out_trade_no=$data['out_trade_no'];
		$query=$this->db->get_where('orders',array('out_trade_no'=>$out_trade_no)); 

		if($query->num_rows()>0){  	//该订单已经提交过了
			$code=0;
		}else{
			$this->db->insert('orders', $data);
			$code=$this->db->affected_rows();  
		}
		return $code;
	}
	
	//更新订单信息
	public function update_order($out_trade_no,$total_amount,$data){
		
		//$code=0;
		$query=$this->db->get_where('orders',array('out_trade_no'=>$out_trade_no));
		if ($query->num_rows() > 0){  //判断该订单号的订单是否存在
			$row = $query->row_array();
			if($total_amount==$row['total_amount']){ //判断实际支付金额与请求金额是否一致
				if($row['status']==0){ //订单还未处理，订单状态：未支付
					$this->db->where('out_trade_no', $out_trade_no);
					$this->db->update('orders', $data);
				    //$code=$this->db->affected_rows();
				}
			}
		}
		$query->free_result();
		//return $code;
	}

}