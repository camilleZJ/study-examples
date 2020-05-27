<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Etc/GMT-8');
define('TIMESTAMP', isset($_SERVER['REQUEST_TIME']) ? floatval($_SERVER['REQUEST_TIME']) : time());

class Alipay_wapPay extends CI_Controller{
	
	public $alipay_config;
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Order_model');
		$this->config->load('aliconf',TRUE);
		$this->alipay_config=$this->config->item('alipay_config','aliconf');
		$this->load->library('alipay/AlipayTradeService',$this->alipay_config,'payResponse');
		//$payResponse = new AlipayTradeService($config);  //load方法就不用实例化了
	}
	
	public function alipay(){
		
		if (!empty($_POST['WIDout_trade_no'])&& trim($_POST['WIDout_trade_no'])!=""){
			//订单信息
			$data=$this->input->post();
			$orderData['out_trade_no']=$data['WIDout_trade_no'];
			$orderData['total_amount']=$data['WIDtotal_amount'];
			$orderData['subject']=$data['WIDsubject'];  //订单名称
			$orderData['body']=$data['WIDbody']; //订单描述
			$orderData['time']=time();
			$timeout_express="1m"; //超时时间
			
			//订单存入数据库
			$this->load->model('order_model');
			$affected_rows=$this->order_model->add_order($orderData);
			
			if($affected_rows>0){
				$this->load->library('alipay/AlipayTradeWapPayContentBuilder','','payRequestBuilder');
				$this->payRequestBuilder->setBody($orderData['body']);
				$this->payRequestBuilder->setSubject($orderData['subject']);
				$this->payRequestBuilder->setOutTradeNo($orderData['out_trade_no']);
				$this->payRequestBuilder->setTotalAmount($orderData['total_amount']);
				$this->payRequestBuilder->setTimeExpress($timeout_express);
					
				$result=$this->payResponse->wapPay($this->payRequestBuilder,$this->alipay_config['return_url'],$this->alipay_config['notify_url']);
				return '';
			}else{
				show_error('订单提交失败或重复提交！');
			}
		}
		
		$this->load->view('Alipay_wapPay/alipay');
	}
	
	//支付宝页面跳转同步通知页面：根据支付结果处理订单
	public function alipay_return(){
		
		$arr=$_GET;
		$result = $this->payResponse->check($arr);	
		if($result) {  //验证成功
			//请在这里加上商户的业务逻辑程序代码
		
			//获取支付宝的通知返回参数，可参考技术文档中页面跳转同步通知参数列表
			$out_trade_no = htmlspecialchars($_GET['out_trade_no']);
			$trade_no = htmlspecialchars($_GET['trade_no']);
			$res="验证成功<br />外部订单号：".$out_trade_no;
		}else {  //验证失败
			$res="验证失败";
		}
		$this->load->vars('get_arr',$arr);
		$this->load->vars('result',$result);
		$this->load->vars('return_result',$res);
		$this->load->view('Alipay_wapPay/alipay_return');
	}
	
    //支付宝服务器异步通知页面：处理同步通知遗漏的订单
	public function alipay_notify(){
		
		$arr=$_POST;
		$this->payResponse->writeLog('---异步通知:'.var_export($_POST,true));
		$result = $this->payResponse->check($arr);
		if($result) {//验证成功

			$out_trade_no = $arr['out_trade_no'];
			$trade_status = $arr['trade_status'];
			$total_amount=$arr['receipt_amount'];  //商家实际收到的款项,单位：元
			$bill_info=json_decode($arr['fund_bill_list']);//支付金额信息
			
			$orderData=array();
			$orderData['trade_no']=$arr['trade_no'];
			$orderData['status']=1;
			$orderData['buyer_aliAccount']=$arr['buyer_logon_id'];//买家支付宝账号
			$orderData['fundChannel']=$bill_info['fundChannel'];//支付渠道
			
			$this->load->model('order_model');
			if($arr['seller_id']==$this->alipay_config['alipay_partnerid'] && $arr['app_id'] == $this->alipay_config['app_id']){  //判断本次通知是否是异常通知
				
				if($trade_status == 'TRADE_FINISHED') { //1.不支持退款，支付成功即交易完成  2.可退款，支付成功后先发送TRADE_SUCCESS状态，待退款日期超过可退款期限后，支付宝系统再发送该交易状态通知
					$this->order_model->update_order($out_trade_no,$total_amount,$orderData);
				}
				else if ($trade_status == 'TRADE_SUCCESS') {  //可退款前提下，支付成功后，支付宝系统发送该交易状态通知（当退款期超时后再发送TRADE_FINISHED通知状态）
					$this->order_model->update_order($out_trade_no,$total_amount,$orderData);
				}
			}
  
			echo "success";		//请不要修改或删除
		}else {
			//验证失败
			echo "fail";	//请不要修改或删除
		}
		
	}
	
	public function test(){
// 		const AES_ENCODING_KEY = 'VDYNdytrjzRZoRAbivcgJWgPSMQn4wmfeWaOUaB39VY=';
// 		const AES_IV_LENGTH = 16;
// 		const AES_MODE = MCRYPT_MODE_CBC;
// 		const AES_CIPHER = MCRYPT_RIJNDAEL_128;

		
// 		include_once APPPATH.'libraries/alipay/AopEncrypt.php';
		$screct_key='VDYNdytrjzRZoRAbivcgJWgPSMQn4wmfeWaOUaB39VY=';
		
		$pubKey= 'MIIEpAIBAAKCAQEA07+cO6EAeJ9CVr6SE6vOb8Tw3Z+6VMgHwBB3MQHs8NqRj1BpRHO7IMdhoLf2pzGkkhwIcIVP1uN6wkTwbkm3D8lhKViXuUaCqIc63rFnXoLqYdBeaindqF3d5z7laGnqJuH8+Py7dMW2bkjThQaLqdlqXSMQh+FlvtrJZIJNNIMLEiu4OneNK8KFqp9ObdeYnj2+4gvew4+VZQzMiJoIazzBtChqFTaZ59Jtk8nj3+6iamLfSyQucvbGbcIT9MnhpOmoNqgCDN03CPXNY6ONaAaXF3FrVDtoVpYwgQ9Hc48kv8LN53ClIh4JgFKmizzuybG4b4kIDyPVJEsusjgxeQIDAQABAoIBAHWP8ovgrcRKl6hVkgC96JWBgEFrxyX/EN5/xc09xnFKyHoLKQbKQmDGkduLh7P4CejdC4p9WYBzN9rYL6zr3Gk1Y25zM3m4Ph6XRgYR9NQyVzMLXwAOUnSB+QvEjdXUyB3iZnNOzjzc8JZ2dIlswuoAPd+L8keeysCV9C6LcZXvEEmdKY0am5h4MLZ+Y0wZSvlQbw9QsC/pfR733gkL6d/rgCwwLB5ezNsKWo6UEojrazUZbJq4fAsrMIa5rZ0HleHFtFB74rZzq49g1wVq+a4k6Q/KPJT9gXS0eoxEtLruGlqMjt4dywuxVGxiz/UnYXlslSHdns8PjANE2qhK4WkCgYEA+8WQ42IZxLSNPVGABS6YMeil5RP3+0ryV9+otxCiDdBlYy2zSO+NgMsQR8R60Wao5UCMZHYoqs7Y5sytAsYpYR4pH2J53eIGUNF2Iv/G+jCbSjuHDNvRGxXV5src2MCvD6sZ2QrKf4fXKMveX+9H3wcY71eRF3b0D8PBl1PAR88CgYEA1035PfEzfmaMlkU7Tbt+VbT20NCuwuRKWlJnTJRkpTwzqZyzsENRBFSAJE/KN30LOrfpaGlbbucA0YCP3YkKfr5r1qRdSTNkYvdJz83RokdtnQ+P8aXkqb4OknSPNjukl/70n0oQKpLNZiU9zZ+/QyvSavec+ldUSuwpTTR3/DcCgYEAvooQUuVlVwbFdUh+uG1A5VSKaEmqfNWFfpdXmePOoOG8OUjWzDXvQy9qzMWXpW4qGAW3ujfqIwqUAGeZ7+WEX2lx+byPOiraWMOMwoQMVEAA5jcSx9p78CGgVh/k720zj4oP78BI7Ccj4E4Zp0TxgYpfhpMgzT9LCbs+gYXymOUCgYA85td7yACS3psh3o9523FO+RNf2ce1ZTIK/ogtx0P44WCTt7m0QDZojSZ6Hv/KVlqYz3D8keDFLB61JDKNrbgERgTnADNLA3h/7gc4+rUzvBSrer1y00QtJ4+jqcjgxFo4jGvRqveLs17aZ+plgaxuabTpgQUsYwt67fZ42kCeXQKBgQCnML6KzhSN2LFrr6XMLMahgTNHkw8XCTkNFFTiqX3uDyZ5ubDVnJTdSacaUgF6rgnDCJXqECJy3i/C0QOLt3PZmL86ZCCnrKlZDRVZWO+4iiR16TMwnNB+/PCfLw2F2hBD19ccMBgK9goH0I2VljHNHtIcT4IOV7kcUYPXiK+I4A==';		
		

		
	}
	
	//AES加密算法
	private function encrypt($str,$screct_key){
		//AES, 128 模式加密数据 CBC
		$screct_key = base64_decode($screct_key);
		$str = trim($str);
		$str = $this->addPKCS7Padding($str);
		
		
		$iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128,MCRYPT_MODE_CBC),1);
		$encrypt_str =  mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $screct_key, $str, MCRYPT_MODE_CBC, $iv);
		return base64_encode($iv.$encrypt_str);
	}
	//填充算法
	private function addPKCS7Padding($source){
		$source = trim($source);
		$block = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);	
		$pad = $block - (strlen($source) % $block);
		if ($pad <= $block) {
			$char = chr($pad);
			$source .= str_repeat($char, $pad);
		}
		return $source;
	}
	//AES解密算法
	function decrypt($str,$screct_key){
		//AES, 128 模式加密数据 CBC
		$str = base64_decode($str);
		$screct_key = base64_decode($screct_key);
		
		//$iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128,MCRYPT_MODE_CBC),1);
		$iv_length=mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128,MCRYPT_MODE_CBC);
		$iv = substr($str, 0, $iv_length);
		
		$encrypt_str =  mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $screct_key, substr($str, $iv_length), MCRYPT_MODE_CBC, $iv);
		$encrypt_str = $this->stripPKSC7Padding($encrypt_str);
		return $encrypt_str;
	
	}
	//移去填充算法
	function stripPKSC7Padding($source){
		$source = trim($source);
		$char = substr($source, -1);
		$num = ord($char);
		if($num==62)return $source;
		$source = substr($source,0,-$num);
		return $source;
	}
	
}