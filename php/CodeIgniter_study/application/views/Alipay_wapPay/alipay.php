<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>支付宝支付测试</title>
<style>
    *{margin:0;padding:0;}
    ul,ol{list-style:none;}
    body{font-family: "Helvetica Neue",Helvetica,Arial,"Lucida Grande",sans-serif;}
    .hidden{display:none;}
    .new-btn-login-sp{padding: 1px;display: inline-block;width: 75%;}
    .new-btn-login {background-color: #02aaf1;color: #FFFFFF;font-weight: bold;border: none;width: 100%;height: 30px;border-radius: 5px;font-size: 16px;}
    #main{width:100%;margin:0 auto;font-size:14px;}
    .red-star{color:#f00;width:10px;display:inline-block;}
    .null-star{color:#fff;}
    .content{margin-top:5px;}
    .content dt{width:100px;display:inline-block;float: left;margin-left: 20px;color: #666;font-size: 13px;margin-top: 8px;}
    .content dd{margin-left:120px;margin-bottom:5px;}
    .content dd input {width: 85%;height: 28px;border: 0;-webkit-border-radius: 0;-webkit-appearance: none;}  
    #btn-dd{margin: 20px;text-align: center;}
    .one_line{display: block;height: 1px;border: 0;border-top: 1px solid #eeeeee;width: 100%;margin-left: 20px;}
    .am-header {display: -webkit-box;display: -ms-flexbox;display: box;width: 100%;position: relative;padding: 7px 0;-webkit-box-sizing: border-box;-ms-box-sizing: border-box;box-sizing: border-box;background: #1D222D;height: 50px;text-align: center;-webkit-box-pack: center;-ms-flex-pack: center;box-pack: center;-webkit-box-align: center;-ms-flex-align: center;box-align: center;}
    .am-header h1 {-webkit-box-flex: 1;-ms-flex: 1;box-flex: 1;line-height: 18px;text-align: center;font-size: 18px;font-weight: 300;color: #fff;}
</style>
</head>
<body text=#000000 bgColor="#ffffff" leftMargin=0 topMargin=4>
<header class="am-header">
        <h1>支付宝手机网站支付接口快速通道测试</h1>
</header>
<div id="main">
        <form name=alipayment action='alipay' method=post target="_blank">
            <div id="body" style="clear:left">
                <dl class="content">
                    <dt>商户订单号：</dt>
                    <dd><input id="WIDout_trade_no" name="WIDout_trade_no" /></dd>
                    <hr class="one_line">
                    
                    <dt>订单名称：</dt>
                    <dd><input id="WIDsubject" name="WIDsubject" /></dd>
                    <hr class="one_line">
                    
                    <dt>付款金额：</dt>
                    <dd><input id="WIDtotal_amount" name="WIDtotal_amount" /></dd>
                    
                    <hr class="one_line">
                    <dt>商品描述：</dt>
                    <dd><input id="WIDbody" name="WIDbody" /></dd>
                    <hr class="one_line">
                    
                    <dt></dt>
                    <dd id="btn-dd">
                        <span class="new-btn-login-sp">
                            <button class="new-btn-login" type="submit" style="text-align:center;">确 认支付</button>
                        </span>
                    </dd>
                    
                </dl>
            </div>
		</form>
	</div>
<script language="javascript">
	function GetDateNow() {
		var vNow = new Date();
		var sNow = "";
		sNow += String(vNow.getFullYear());
		sNow += String(vNow.getMonth() + 1);
		sNow += String(vNow.getDate());
		sNow += String(vNow.getHours());
		sNow += String(vNow.getMinutes());
		sNow += String(vNow.getSeconds());
		sNow += String(vNow.getMilliseconds());
		document.getElementById("WIDout_trade_no").value =  sNow;
		document.getElementById("WIDsubject").value = "测试";
		document.getElementById("WIDtotal_amount").value = "0.01";
        document.getElementById("WIDbody").value = "购买测试商品0.01元";
	}
	GetDateNow();
</script>
</body>
</html>
