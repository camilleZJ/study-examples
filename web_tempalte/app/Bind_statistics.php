<?php
header("content-type:text/html;charset=utf-8");

class Bind_statistics
{
	public function index(){}

	//绑定数据处理
	public function bind_data($starttime, $endttime, $sid)
	{
		$res = file_get_contents('http://58.215.165.157:8874/share_bind_stat?bdate='.$starttime.'&edate='.$endttime.'&sid='.$sid.'&json=1');
		if(FALSE === $res)
		{
			echo '服务器在维护...';
			exit;
		}

		$invalid_total = 0;
		$repeat_total = 0;
		$temp_arr = array();
		$hot_fail_arr = array();
		$hot_success_arr = array();
		$is_fail_arr = array();
		$is_success_arr = array();

		$reslut = json_decode($res, true);
		if (count($reslut) === 0)
		{
			return FALSE;
		}
		foreach ($reslut as $keys =>$val)
		{
			//去掉重复数据
			$temp_val = $val['regip'] . '-' . $val['min(reg.uid)'];
			if (in_array($temp_val, $temp_arr))
		    {
		    	$repeat_total += 1;
		    	unset($reslut[$keys]);
		    	continue;
		    }
		    else
		    {
		    	$temp_arr[] = $temp_val;
		    }

		    //记录无效数据
		    //按时不排除$val['refer'] == 'h5.iphone' || $val['refer'] == 'h5.android'
			if((( $val['refer'] == 'mp.qq' || $val['refer'] == 'www.ishow' || $val['refer'] == 'ispeak.cn' || $val['refer'] == 'pc_client' || $val['refer'] == 'mp.weixin') && empty($val['status'])) || $val['status'] =='255')
			{
				$invalid_total += 1;
				unset($reslut[$keys]);
				continue;
			}

			//统计处理
			if ('126' == $val['reg_partnerid'])
			{
				if(empty($val['status'])) //统计热门失败数据
				{
					$hot_fail_arr[] = $val;
				}
				else  //统计热门成功数据
				{
					$hot_success_arr[] = $val;
				}
			}
			else
			{
				//过滤无效版本号:安卓-3.2.3.07032  安卓-3.2.3.07041  ios-3.2.8
				$version = (int)str_replace('.', '', $val['version2']);
				$refer = strtolower($val['refer']);
				if(FALSE !== strpos($refer, 'android')) //安卓
				{
					if($version < 32307032)
					{
						$invalid_total += 1;
						unset($reslut[$keys]);
						continue;
					}

					if(empty($val['status']))
					{
						$is_fail_arr[] = $val;
					}
					else
					{
						$is_success_arr[] = $val;
					}
				}
				else //IOS
				{
					if($version < 328)
					{
						$invalid_total += 1;
						unset($reslut[$keys]);
						continue;
					}

					if(empty($val['status']))
					{
						$is_fail_arr[] = $val;
					}
					else
					{
						$is_success_arr[] = $val;
					}
				}
			}
		}

		unset($res, $reslut, $temp_arr);
		//$invalid_total += $repeat_total;
		return array('invalid_total' => $invalid_total, 'repeat_total' => $repeat_total, 'hot_fail_arr' => $hot_fail_arr, 'hot_success_arr' => $hot_success_arr, 'is_fail_arr' => $is_fail_arr, 'is_success_arr' => $is_success_arr);
	}

	//日志数据处理
	public function log_data($log_url)
	{
		$handle = @fopen($log_url, 'r');
		if(FALSE === $handle)
		{
			echo '日志文件打开失败，请查看日志文件是否存在';
			exit;
		}

		$i = 1; // 行数
		$length = 4096;//默认1024一行的大小
		$res_array = array();
		while (FALSE === feof($handle))
		{
			$buffer = fgets($handle, $length);
			//处理行
			if ((FALSE !== strpos($buffer, '{')) && (FALSE !== strpos($buffer, 'ip')) && (FALSE !== strpos($buffer, 'uid')))
			{
				$buffer2 = substr($buffer, strpos($buffer,'{'));
				$buffer1 = json_decode($buffer2, TRUE);
				if (array_key_exists('ip', $buffer1) && array_key_exists('uid', $buffer1)) {
					$res_array[] = $buffer1;
				}else{
					unset($buffer1);
				}
			}
			else
			{
				unset($buffer);
			}
			$i++;
		}
		fclose($handle);
// 		return $res_array;
		$res = array();
		foreach ($res_array as $k => $v)
		{
			$uid = $v['uid'];
			$res[$uid] = $v;
		}
		return $res;
	}

	public function fail_analysis($data, $btime)
	{
		//$log_data = $this->log_data('http://58.215.165.153:8068/static/logs/log-' . date('Y-m-d', $btime) . '.log');
		$log_data = $this->log_data('./log-' . date('Y-m-d', $btime) . '.log');
		$time_error_arr= array();
		$device_diff_arr = array('time_error' => array(), 'time_suc'=>array());
		$ua_diff_arr = array('time_error' => array(), 'time_suc'=>array());;
		$others = array();
		foreach ($data as $key => $val)
		{
			if($val['h5time'] >= $val['regtime'])
			{
				//先注册后打开   h5_client:1-pc 2-ios  16-安卓,注册只能在手机端
				if(($val['h5_client'] == 16 && FALSE !== strpos(strtolower($val['refer']), 'android')) || ($val['h5_client'] == 2 && FALSE === strpos(strtolower($val['refer']), 'android')))
				{
					//打开链接和注册设备类型相同（设备类型：ios、andriod）
					$temp_key = $val['min(reg.uid)'];
					if(array_key_exists($temp_key, $log_data))
					{
						$data[$key]['reg_ua'] = $log_data[$temp_key]['ua'];
						if($this->checkUa($val['h5ua'], $log_data[$temp_key]['ua'])) //同机型
						{
							$time_error_arr[] = $data[$key];
						}
						else
						{
							$ua_diff_arr['time_error'][] = $data[$key];
						}
					}
					else
					{
						$ua_diff_arr['time_error'][] = $val;
					}
				}
				else
				{
					//打开和注册的设备类型不同（设备类型：pc、ios、andriod）
					$device_diff_arr['time_error'][] = $val;
				}
			}
			else  //正确时间顺序
			{
				if(($val['h5_client'] == 16 && FALSE !== strpos(strtolower($val['refer']), 'android')) || ($val['h5_client'] == 2 && FALSE === strpos(strtolower($val['refer']), 'android')))
				{
					//打开链接和注册设备类型相同（设备类型：ios、andriod）
					$temp_key = $val['min(reg.uid)'];
					if(array_key_exists($temp_key, $log_data))
					{
						$data[$key]['reg_ua'] = $log_data[$temp_key]['ua'];
						if($this->checkUa($val['h5ua'], $log_data[$temp_key]['ua'])) //同机型
						{
							$others[] = $data[$key];
						}
						else
						{
							$ua_diff_arr['time_suc'][] = $data[$key];
						}
					}
					else
					{
						$ua_diff_arr['time_suc'][] = $val;
					}
				}
				else
				{
					//打开和注册的设备类型不同（设备类型：pc、ios、andriod）
					$device_diff_arr['time_suc'][] = $val;
				}
			}
		}
		return array('time_error_arr' => $time_error_arr, 'device_diff_arr' => $device_diff_arr, 'ua_diff_arr' => $ua_diff_arr, 'others' => $others);
	}

	/**
	 * 处理ua信息
	 * @param	string	$ua			ua信息
	 * @return	array	$data		手机品牌 手机系统版本 手机build信息
	 */
	private function handleUa($ua)
	{
		$useragent1 = stripslashes($ua);
		$ua1 = str_replace(' ', '', strtolower($useragent1));
		$ua_arr1 = explode('(', $ua1);
		$str1 = isset($ua_arr1[1]) ? $ua_arr1[1] : '';
		$ua_arr2 = explode(')', $str1);
		$ua_str = isset($ua_arr2[0]) ? $ua_arr2[0] : '';
		if (FALSE == $ua_str)
		{
			return FALSE;
		}
		unset($useragent1,$ua1,$ua_arr1, $str1, $ua_arr2);
	
		if (FALSE !== strpos($ua_str, 'android'))
		{
			$tmp_arr1 = explode('android', $ua_str);
			$tmp_str1 = $tmp_arr1[1];
			$tmp_arr2 = explode(';', $tmp_str1);
			$data['version'] = isset($tmp_arr2[0]) ? $tmp_arr2[0] : '';
			$tmp_arr3 = explode('build/', stripslashes($ua_str));
			$res1 = explode(';',$tmp_arr3[0]);		
			$data['mobilebrand'] = array_pop($res1);
			$tmp_arr4 = explode(';', $tmp_arr3[1]);
			$data['build'] = $tmp_arr4[0];
			unset($tmp_arr1, $tmp_str1, $tmp_arr2, $tmp_arr3, $tmp_arr4,$ua_str);
		}
		elseif (FALSE !== strpos($ua_str, 'macosx'))
		{
			$tmp_arr1 =  explode('os', $ua_str);
			$tmp_str1 = $tmp_arr1[1];
			$tmp_arr2 = explode('like', $tmp_str1);
			$tmp_str2 = $tmp_arr2[0];
			$tmp_str3 = str_replace(array("_", "."), array("", ""), $tmp_str2);
			$data['version'] = stripslashes($tmp_str3);
			if (FALSE !== strpos($ua_str, 'ipad'))
			{
				$data['mobilebrand'] = 'ipad';
			}
			else
			{
				$data['mobilebrand'] = 'iphone';
			}
			$data['build'] = '';
			unset($tmp_arr1, $tmp_str1, $tmp_arr2, $tmp_str2, $tmp_str3,$ua_str);
		}
		else
		{
			unset($ua_str);
			return FALSE;
		}
		return $data;
	}
	
	/**
	 * 核对ua信息
	 * @param	string	$ua			ua信息1
	 * @param	string	$ua			ua信息2
	 * @return	boolean			true  false
	 */
	private function checkUa($ua1,$ua2)
	{
		if(FALSE == ($res1 = $this->handleUa($ua1)))
		{
			return FALSE;
		}
		if(FALSE == ($res2 = $this->handleUa($ua2)))
		{
			return FALSE;
		}
		if($res1['version'] == $res2['version'] && $res1['mobilebrand'] == $res2['mobilebrand'] && $res1['build'] == $res2['build'])
		{
			return TRUE;
		}
		return FALSE;
	}
	
}



//index()
$sid = @isset($_GET['sid'])?$_GET['sid']:'';
$starttime = (FALSE == $_GET['bdate']) ? date('Y-m-d') : $_GET['bdate'];
$endttime = (FALSE == $_GET['edate']) ? date('Y-m-d') : $_GET['edate'];

//对时间做判断
if(FALSE === ($btime = strtotime($starttime)) || FALSE === ($etime = strtotime($endttime)))
{
	echo '时间参数有误！';
	exit;
}
$checktime = ($etime - $btime)/(24*3600);
// if ($etime > strtotime('today'))
// {
// 	echo "结束时间不正确";
// 	exit;
// }
if ($checktime > 1)
{
	echo "输入时间太长了，不能超过1天哦！";
	exit;
}

$test = new Bind_statistics();
$res = $test->bind_data(date('Y-m-d', $btime), date('Y-m-d', $etime), $sid);
if(FALSE === $res)
{
	echo '该时间段内没有绑定数据，请换个时间段';
	exit;
}
// $invalid_total = $res['invalid_total']; //无效总数(不包含重复的)
// $repeat_total = $res['repeat_total']; //重复总数
$hot_suc_total = count($res['hot_success_arr']);
$is_suc_total = count($res['is_success_arr']);

//HOT失败分析
$hot_time_diff = array();
$hot_ua_diff = array();
$hot_device_diff = array();
$hot_others_total = array();
if(count($res['hot_fail_arr']) > 0)
{
	$hot_res = $test->fail_analysis($res['hot_fail_arr'], $btime);
	$hot_time_diff = $hot_res['time_error_arr'];
	$hot_ua_diff = array_merge($hot_res['ua_diff_arr']['time_error'], $hot_res['ua_diff_arr']['time_suc']);
	$hot_device_diff = array_merge($hot_res['device_diff_arr']['time_error'], $hot_res['device_diff_arr']['time_suc']);
	$hot_others_total = $hot_res['others'];
	unset($hot_res);
}
$hot_total = $hot_suc_total + count($res['hot_fail_arr']);

//IS失败分析
$is_time_diff = array();
$is_ua_diff = array();
$is_device_diff = array();
$is_others_total = array();
if(count($res['is_fail_arr']) > 0)
{
	$is_res = $test->fail_analysis($res['is_fail_arr'], $btime);
	$is_time_diff = $is_res['time_error_arr'];
	$is_ua_diff = array_merge($is_res['ua_diff_arr']['time_error'], $is_res['ua_diff_arr']['time_suc']);
	$is_device_diff = array_merge($is_res['device_diff_arr']['time_error'], $is_res['device_diff_arr']['time_suc']);
	$is_others_total = $is_res['others'];
	unset($is_res);
}
$is_total = $is_suc_total + count($res['is_fail_arr']);
unset($res);
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>注册绑定情况统计</title>
<meta name="viewport" content="width=device-width,maximum-scale=1.5,initial-scale=1.0,user-scalable=yes">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<link href="//cdn.bootcss.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet">
<style>
html,body{font-size:13px;cursor:default;font-family:"Microsoft YaHei UI","Microsoft YaHei",DengXian,SimSun,"Segoe UI",Tahoma,Helvetica,sans-serif;height:100%;}
::-webkit-scrollbar{width:8px;height:10px}
::-webkit-scrollbar-button:start:decrement{background-repeat:no-repeat;width:12px;height:3px}
::-webkit-scrollbar-button:end:increment{background-repeat:no-repeat;width:12px;height:3px}
::-webkit-scrollbar-track{border:0;background-color:#f6f7f8}
::-webkit-scrollbar-track-piece{background-color:#fff}
::-webkit-scrollbar-thumb{width:8px;background-color:#BEC2C8;border:1px solid #B2B6BB;border-radius:5px}
::-webkit-scrollbar-thumb:vertical{border-width:1px;min-height:24px}
::-webkit-scrollbar-thumb:horizontal{border-width:1px;min-width:24px}
::-webkit-scrollbar-thumb:vertical:hover{background-color:#77797C;border:1px solid #6B6E71}
::-webkit-scrollbar-thumb:horizontal:hover{background-color:#77797C;border:1px solid #6B6E71}
::-webkit-scrollbar-thumb:vertical:active{background-color:#D6DAE0;border:1px solid #D1D5DC}
::-webkit-scrollbar-thumb:horizontal:active{background-color:#D6DAE0;border:1px solid #D1D5DC}
.page-container{padding:45px 10px 0;}
.table{margin-bottom:0;}
.table + .table{margin-top:10px;}
.table:first-child{margin-top:0;}
.table-bordered thead tr th,
.table-bordered tbody tr th,
.table-bordered thead tr td,
.table-bordered tbody tr td{border-width:1px;border-color:#ddd;border-style:solid;padding:6px;vertical-align:middle;}
.table-bordered tbody tr td{font-size:12px;}

.table-bg{text-align:center;background-color:#efefef;background-image:-webkit-gradient(linear, 0 0%, 0 100%, from(#fefefe), to(#efefef));background-image:-webkit-linear-gradient(top, #fefefe 0%, #efefef 100%);background-image:-moz-linear-gradient(top, #fefefe 0%, #efefef 100%);background-image:-ms-linear-gradient(top, #fefefe 0%, #efefef 100%);}
.breadcrumb{position:fixed;left:0;top:0;right:0;margin-bottom:0;border-radius:0;border-bottom:1px solid #e5e5e5;background-color:#f5f5f5;height:34px;line-height:inherit;z-index:1000;}

.table{text-align:center;}
.table-title{font-weight:bold;}
.table th span{padding-left:30px;}
#fail-detail{margin-top:100px;width:1900px;}
#hot-time, #hot-device, #hot-ua, #hot-others, #is-time, #is-device, #is-ua, #is-others{cursor:pointer;}
</style>
</head>
<body>
<ol class="breadcrumb">
	<!-- li><i class="glyphicon glyphicon-home"></i>&nbsp;<a href="#">管理首页</a></li -->
	<li class="active">数据统计</li>
</ol>
<div class="page-container">
	<table class="table table-bordered">
	<!--
		<!?php if(isset($message)):?>
		<thead>
			<tr>
				<th class="table-bg">提示</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td style="text-align:center;padding:30px 0;font-size:14px;"><!?php echo $message;?></td>
			</tr>
		</tbody>
		<!?php else:?>
	 -->
		<thead>
			<tr>
				<th class="table-bg" colspan="10"><?php echo date('Y/m/d', $btime).'-'.date('Y/m/d', $etime);?><span>注册绑定情况统计</span></th>
			</tr>
		</thead>
		<tbody>
			<tr class="table-title">
				<td></td>
				<td rowspan="2">总条数</td>
				<td rowspan="2">绑定成功</td>
				<td colspan="6">无效条数</td>
				<td rowspan="2">失败条数</td>
			</tr>
			<tr class="table-title">
				<td></td>
				<td colspan="2">先注册后打开</td>
				<td colspan="2">设备类型不一致</td>
				<td colspan="2">UA不一致</td>
			</tr>
			<tr>
				<td class="table-title">热门</td>
				<td><?php echo $hot_total;?></td>
				<td><?php echo $hot_suc_total;?></td>
				<td class="table-bg" id="hot-time" colspan="2"><?php echo count($hot_time_diff);?></td>
				<td id="hot-device" colspan="2"><?php echo count($hot_device_diff);?></td>
				<td id="hot-ua" colspan="2"><?php echo count($hot_ua_diff);?></td>
				<td id="hot-others" class="table-bg"><?php echo count($hot_others_total);?></td>
			</tr>
			<tr>
				<td class="table-title">IS</td>
				<td><?php echo $is_total;?></td>
				<td><?php echo $is_suc_total;?></td>
				<td class="table-bg" id="is-time" colspan="2"><?php echo count($is_time_diff);?></td>
				<td id="is-device" colspan="2"><?php echo count($is_device_diff);?></td>
				<td id="is-ua" colspan="2"><?php echo count($is_ua_diff);?></td>
				<td id="is-others" class="table-bg"><?php echo count($is_others_total);?></td>
			</tr>
			<tr>
				<td class="table-title">总计</td>
				<td><?php echo ($hot_total + $is_total);?></td>
				<td><?php echo ($hot_suc_total + $is_suc_total);?></td>
				<td class="table-bg" colspan="2"><?php echo (count($hot_time_diff) + count($is_time_diff));?></td>
				<td colspan="2"><?php echo (count($hot_device_diff) + count($is_device_diff));?></td>
				<td colspan="2"><?php echo (count($hot_ua_diff) + count($is_ua_diff));?></td>
				<td class="table-bg"><?php echo (count($hot_others_total) + count($is_others_total));?></td>
			</tr>
		</tbody>
		<!-- ?php endif;? -->
	</table>

	<table class="table table-bordered" id="fail-detail"></table>
</div>

<script src="//cdn.bootcss.com/jquery/1.9.1/jquery.min.js"></script>
<script src="//cdn.bootcss.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
<script>
function format_table(json_text, title)
{
	var json_obj = JSON.parse(json_text);
	var htm = '<thead><tr>';
	if(json_obj.length == 0)
	{
		htm += '<th class="table-bg">'+ title +'</th>';
		htm += '</tr></thead>';
		htm += '<tbody><tr><td style="text-align:center;padding:30px 0;font-size:14px;">没有数据</td></tr>';
	}
	else
	{
		htm += '<th class="table-bg" colspan="23">'+ title +'</th>';
		htm += '</tr></thead>';
		htm += '<tbody><tr class="table-title"><td>regip</td><td>min(reg.uid)</td><td>用户ID</td><td>regtime</td><td>h5time</td><td>refer</td><td>h5_client</td><td>h5partnerid</td><td>reg_partnerid</td><td>reg_subpartnerid</td><td>h5ip</td><td>onclick</td><td>bind_time</td><td>推广员ID</td><td>h5_sid</td><td>Copy_Failed</td><td>绑定备注</td><td>status</td><td>version1</td><td>version2</td><td>loginip</td><td>reg_ua + h5ua</td></tr>';
		$.each(json_obj, function(k, v){
			htm += '<tr><td>'+ v['regip'] +'</td>';
			htm += '<td>'+ v['min(reg.uid)'] +'</td>';
			htm += '<td>'+ v['用户ID'] +'</td>';
			htm += '<td>'+ v['regtime'] +'</td>';
			htm += '<td>'+ v['h5time'] +'</td>';
			htm += '<td>'+ v['refer'] +'</td>';
			htm += '<td>'+ v['h5_client'] +'</td>';
			htm += '<td>'+ v['h5partnerid'] +'</td>';
			htm += '<td>'+ ((null == v['reg_partnerid'])?' ':v['reg_partnerid']) + '</td>';
			htm += '<td>'+ ((null == v['reg_subpartnerid'])?' ':v['reg_subpartnerid']) + '</td>';
			htm += '<td>'+ v['h5ip'] +'</td>';
			htm += '<td>'+ ((null == v['onclick'])?' ':v['onclick']) + '</td>';			
			htm += '<td>'+ ((null == v['bind_time'])?' ':v['bind_time']) + '</td>';
			htm += '<td>'+ v['推广员ID'] +'</td>';
			htm += '<td>'+ v['h5_sid'] +'</td>';
			htm += '<td>'+ ((null == v['Copy_Failed'])?' ':v['Copy_Failed']) + '</td>';
			htm += '<td>'+ ((null == v['绑定备注'])?' ':v['绑定备注']) + '</td>';
			htm += '<td>'+ ((null == v['status'])?' ':v['status']) + '</td>';
			htm += '<td>'+ ((null == v['version1'])?' ':v['version1']) + '</td>';
			htm += '<td>'+ ((null == v['version2'])?' ':v['version2']) + '</td>';
			htm += '<td>'+ ((null == v['loginip'])?' ':v['loginip']) + '</td>';
			htm += '<td style="text-align:left;">'+ ((undefined == v['reg_ua'])?'-':v['reg_ua']) +'<br>'+ v['h5ua'] + '</td></tr>';
		});
	}
	htm += '</tbody>';
	$('#fail-detail').html(htm);
}

$(document).on("click", "#hot-time", function(){
	format_table('<?php echo json_encode($hot_time_diff);?>', '热门-先注册后打开数据列表');
}).on("click", "#hot-device", function(){
	format_table('<?php echo json_encode($hot_device_diff);?>', '热门-设备类型不一致数据列表');
}).on("click", "#hot-ua", function(){
	format_table('<?php echo json_encode($hot_ua_diff);?>', '热门-UA不一致数据列表');
}).on("click", "#hot-others", function(){
	format_table('<?php echo json_encode($hot_others_total);?>', '热门-失败数据列表');
}).on("click", "#is-time", function(){
	format_table('<?php echo json_encode($is_time_diff);?>', 'IS-先注册后打开数据列表');
}).on("click", "#is-device", function(){
	format_table('<?php echo json_encode($is_device_diff);?>', 'IS-设备类型不一致数据列表');
}).on("click", "#is-ua", function(){
	format_table('<?php echo json_encode($is_ua_diff);?>', 'IS-UA不一致数据列表');
}).on("click", "#is-others", function(){
	format_table('<?php echo json_encode($is_others_total);?>', 'IS-失败数据列表');
});
</script>
</body>
</html>
