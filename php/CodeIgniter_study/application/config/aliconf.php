<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 支付宝H5支付
 * @copyright	Copyright (c) 2004 - 2017, Qinhe Co.,Ltd. (http://www.ispeak.cn/)
 * @since	Date 2017/03/31
 * @author	iSpeak Dev Team <fair>
 */
// define('alipay_partnerid', '2088102168984920'); //商户id
// define('alipay_appid', '2016080200151662');
// define('notify_url', 'http://aliwappaytest.duapp.com/notify_url.php'); 
// define('return_url', 'http://aliwappaytest.duapp.com/return_url.php'); 
// define('charset', 'UTF-8'); 
// define('sign_type', 'RSA2');
// define('alipay_private_key', 'MIIEpAIBAAKCAQEA07+cO6EAeJ9CVr6SE6vOb8Tw3Z+6VMgHwBB3MQHs8NqRj1BpRHO7IMdhoLf2pzGkkhwIcIVP1uN6wkTwbkm3D8lhKViXuUaCqIc63rFnXoLqYdBeaindqF3d5z7laGnqJuH8+Py7dMW2bkjThQaLqdlqXSMQh+FlvtrJZIJNNIMLEiu4OneNK8KFqp9ObdeYnj2+4gvew4+VZQzMiJoIazzBtChqFTaZ59Jtk8nj3+6iamLfSyQucvbGbcIT9MnhpOmoNqgCDN03CPXNY6ONaAaXF3FrVDtoVpYwgQ9Hc48kv8LN53ClIh4JgFKmizzuybG4b4kIDyPVJEsusjgxeQIDAQABAoIBAHWP8ovgrcRKl6hVkgC96JWBgEFrxyX/EN5/xc09xnFKyHoLKQbKQmDGkduLh7P4CejdC4p9WYBzN9rYL6zr3Gk1Y25zM3m4Ph6XRgYR9NQyVzMLXwAOUnSB+QvEjdXUyB3iZnNOzjzc8JZ2dIlswuoAPd+L8keeysCV9C6LcZXvEEmdKY0am5h4MLZ+Y0wZSvlQbw9QsC/pfR733gkL6d/rgCwwLB5ezNsKWo6UEojrazUZbJq4fAsrMIa5rZ0HleHFtFB74rZzq49g1wVq+a4k6Q/KPJT9gXS0eoxEtLruGlqMjt4dywuxVGxiz/UnYXlslSHdns8PjANE2qhK4WkCgYEA+8WQ42IZxLSNPVGABS6YMeil5RP3+0ryV9+otxCiDdBlYy2zSO+NgMsQR8R60Wao5UCMZHYoqs7Y5sytAsYpYR4pH2J53eIGUNF2Iv/G+jCbSjuHDNvRGxXV5src2MCvD6sZ2QrKf4fXKMveX+9H3wcY71eRF3b0D8PBl1PAR88CgYEA1035PfEzfmaMlkU7Tbt+VbT20NCuwuRKWlJnTJRkpTwzqZyzsENRBFSAJE/KN30LOrfpaGlbbucA0YCP3YkKfr5r1qRdSTNkYvdJz83RokdtnQ+P8aXkqb4OknSPNjukl/70n0oQKpLNZiU9zZ+/QyvSavec+ldUSuwpTTR3/DcCgYEAvooQUuVlVwbFdUh+uG1A5VSKaEmqfNWFfpdXmePOoOG8OUjWzDXvQy9qzMWXpW4qGAW3ujfqIwqUAGeZ7+WEX2lx+byPOiraWMOMwoQMVEAA5jcSx9p78CGgVh/k720zj4oP78BI7Ccj4E4Zp0TxgYpfhpMgzT9LCbs+gYXymOUCgYA85td7yACS3psh3o9523FO+RNf2ce1ZTIK/ogtx0P44WCTt7m0QDZojSZ6Hv/KVlqYz3D8keDFLB61JDKNrbgERgTnADNLA3h/7gc4+rUzvBSrer1y00QtJ4+jqcjgxFo4jGvRqveLs17aZ+plgaxuabTpgQUsYwt67fZ42kCeXQKBgQCnML6KzhSN2LFrr6XMLMahgTNHkw8XCTkNFFTiqX3uDyZ5ubDVnJTdSacaUgF6rgnDCJXqECJy3i/C0QOLt3PZmL86ZCCnrKlZDRVZWO+4iiR16TMwnNB+/PCfLw2F2hBD19ccMBgK9goH0I2VljHNHtIcT4IOV7kcUYPXiK+I4A==');
// define('alipay_public_key', 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA07+cO6EAeJ9CVr6SE6vOb8Tw3Z+6VMgHwBB3MQHs8NqRj1BpRHO7IMdhoLf2pzGkkhwIcIVP1uN6wkTwbkm3D8lhKViXuUaCqIc63rFnXoLqYdBeaindqF3d5z7laGnqJuH8+Py7dMW2bkjThQaLqdlqXSMQh+FlvtrJZIJNNIMLEiu4OneNK8KFqp9ObdeYnj2+4gvew4+VZQzMiJoIazzBtChqFTaZ59Jtk8nj3+6iamLfSyQucvbGbcIT9MnhpOmoNqgCDN03CPXNY6ONaAaXF3FrVDtoVpYwgQ9Hc48kv8LN53ClIh4JgFKmizzuybG4b4kIDyPVJEsusjgxeQIDAQAB');
// define('gatewayUrl', 'https://openapi.alipaydev.com/gateway.do');  //沙箱
//define('gatewayUrl', 'https://openapi.alipay.com/gateway.do');

$config['alipay_config'] = array (
		//应用ID,您的APPID。
		'app_id' => "2016080200151662",

		//商户私钥，您的原始格式RSA私钥
		'merchant_private_key' => "MIIEpAIBAAKCAQEA07+cO6EAeJ9CVr6SE6vOb8Tw3Z+6VMgHwBB3MQHs8NqRj1BpRHO7IMdhoLf2pzGkkhwIcIVP1uN6wkTwbkm3D8lhKViXuUaCqIc63rFnXoLqYdBeaindqF3d5z7laGnqJuH8+Py7dMW2bkjThQaLqdlqXSMQh+FlvtrJZIJNNIMLEiu4OneNK8KFqp9ObdeYnj2+4gvew4+VZQzMiJoIazzBtChqFTaZ59Jtk8nj3+6iamLfSyQucvbGbcIT9MnhpOmoNqgCDN03CPXNY6ONaAaXF3FrVDtoVpYwgQ9Hc48kv8LN53ClIh4JgFKmizzuybG4b4kIDyPVJEsusjgxeQIDAQABAoIBAHWP8ovgrcRKl6hVkgC96JWBgEFrxyX/EN5/xc09xnFKyHoLKQbKQmDGkduLh7P4CejdC4p9WYBzN9rYL6zr3Gk1Y25zM3m4Ph6XRgYR9NQyVzMLXwAOUnSB+QvEjdXUyB3iZnNOzjzc8JZ2dIlswuoAPd+L8keeysCV9C6LcZXvEEmdKY0am5h4MLZ+Y0wZSvlQbw9QsC/pfR733gkL6d/rgCwwLB5ezNsKWo6UEojrazUZbJq4fAsrMIa5rZ0HleHFtFB74rZzq49g1wVq+a4k6Q/KPJT9gXS0eoxEtLruGlqMjt4dywuxVGxiz/UnYXlslSHdns8PjANE2qhK4WkCgYEA+8WQ42IZxLSNPVGABS6YMeil5RP3+0ryV9+otxCiDdBlYy2zSO+NgMsQR8R60Wao5UCMZHYoqs7Y5sytAsYpYR4pH2J53eIGUNF2Iv/G+jCbSjuHDNvRGxXV5src2MCvD6sZ2QrKf4fXKMveX+9H3wcY71eRF3b0D8PBl1PAR88CgYEA1035PfEzfmaMlkU7Tbt+VbT20NCuwuRKWlJnTJRkpTwzqZyzsENRBFSAJE/KN30LOrfpaGlbbucA0YCP3YkKfr5r1qRdSTNkYvdJz83RokdtnQ+P8aXkqb4OknSPNjukl/70n0oQKpLNZiU9zZ+/QyvSavec+ldUSuwpTTR3/DcCgYEAvooQUuVlVwbFdUh+uG1A5VSKaEmqfNWFfpdXmePOoOG8OUjWzDXvQy9qzMWXpW4qGAW3ujfqIwqUAGeZ7+WEX2lx+byPOiraWMOMwoQMVEAA5jcSx9p78CGgVh/k720zj4oP78BI7Ccj4E4Zp0TxgYpfhpMgzT9LCbs+gYXymOUCgYA85td7yACS3psh3o9523FO+RNf2ce1ZTIK/ogtx0P44WCTt7m0QDZojSZ6Hv/KVlqYz3D8keDFLB61JDKNrbgERgTnADNLA3h/7gc4+rUzvBSrer1y00QtJ4+jqcjgxFo4jGvRqveLs17aZ+plgaxuabTpgQUsYwt67fZ42kCeXQKBgQCnML6KzhSN2LFrr6XMLMahgTNHkw8XCTkNFFTiqX3uDyZ5ubDVnJTdSacaUgF6rgnDCJXqECJy3i/C0QOLt3PZmL86ZCCnrKlZDRVZWO+4iiR16TMwnNB+/PCfLw2F2hBD19ccMBgK9goH0I2VljHNHtIcT4IOV7kcUYPXiK+I4A==",
		
		//异步通知地址：必须在公网中测试
		'notify_url' => "http://cistudy.duapp.com/index.php/Alipay_wapPay/alipay_notify",
		
		//同步跳转:可在本地测试
// 		'return_url' => "http://cistudy.duapp.com/index.php/Alipay_wapPay/alipay_return",
		'return_url' => "http://ci_study/index.php/Alipay_wapPay/alipay_return",

		//编码格式
		'charset' => "UTF-8",

		//签名方式
		'sign_type'=>"RSA2",

		//支付宝网关
		'gatewayUrl' => "https://openapi.alipaydev.com/gateway.do",	
// 		'gatewayUrl' => "https://openapi.alipay.com/gateway.do",  //正式环境

		//支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥,不是自己生成的公钥。
		'alipay_public_key' => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAqB+uflTSGh7yHpri0GJG3HJefmCyCk/z9vUX/8TOM5O2iFAG9vVz+p80X8GyPNT9KB6mhZxLx/bffL0KoU2sH4Z5lNN/9ZrXjVp4l4UCopq+kdZr/SV3fRA+VorPxP9g9VNzLVRfgrrhsWgKGzX+glaMZ7I0KCEaal8OCWt/tlGr60/PZvXt6bkHkGocmsWmqJdHTjgJJT8+gi5EUj2/OHtZ4K64hGzv/+vnkIdcT4usIaBHy7IfDsQpWPn54sqSVQHV00i1ztMrvYh7KeE2LvHxzsx7ylD+plaYpIKjqucCQ/ddy9WJKM2Xzt+Llp7LXoLogo/5G+a12dIHtxJAcQIDAQAB",

		//商户UID
		'alipay_partnerid' =>"2088102169652765",
		
		//支付宝支付渠道
		'fundChannel_conf'=>array (
				'COUPON'=>'支付宝红包',
				'ALIPAYACCOUNT'=>'支付宝余额',
				'POINT'=>'集分宝',
				'DISCOUNT'=>'折扣券',
				'PCARD'=>'预付卡',
				'FINANCEACCOUNT'=>'余额宝',
				'MCARD'=>'商家储值卡',
				'MDISCOUNT'=>'商户优惠券',
				'MCOUPON'=>'商户红包',
				'PCREDIT'=>'蚂蚁花呗'
		)
);
