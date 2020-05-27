<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>支付宝wap支付-同步通知</title>
</head>
<body>
<div>
	<span>校验结果：</span>
	<span><?=$return_result?></span>
</div>
<hr>

<div>
	<span>同步通知返回结果：</span>
	<?php foreach ($get_arr as $row=>$val):?>
	<span><?=$row?> : <?=$val?></span><br>
	<?php endforeach;?>
</div>
<hr>

<div>
	<span>check结果：</span>
	<span><?=$result?></span>
</div>

</body>
</html>