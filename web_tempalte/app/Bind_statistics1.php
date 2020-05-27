<?php 
header("content-type:text/html;charset=utf-8");

class Bind_statistics
{
	public function index()
	{
		$sid = $_GET['sid'];
		$starttime = (FALSE == $_GET['bdate']) ? date('Y-m-d') : $_GET['bdate'];
		$endttime = (FALSE == $_GET['edate']) ? date('Y-m-d') : $_GET['edate'];

		//对时间做判断
		if(FALSE === ($btime = strtotime($starttime)) || FALSE === ($etime = strtotime($endttime)))
		{
			echo '时间参数有误！';
			exit;
		}
		$checktime = ($etime - $btime)/(24*3600);
		if ($etime > strtotime('today'))
		{
			echo "结束时间不正确";
			exit;
		}
		if ($checktime > 1)
		{
			echo "输入时间太长了，不能超过1天哦！";
			exit;
		}
		
		
		$res = $this->bind_data(date('Y-m-d', $btime), date('Y-m-d', $etime), $sid);
		if(FALSE === $res)
		{
			echo '该时间段内没有绑定数据，请换个时间段';
			exit;
		}
	
		$invalid_total = $res['invalid_total'];
		$repeat_total = $res['repeat_total'];
		$hot_suc_total = count($res['hot_success_arr']);
		$is_suc_total = count($res['is_success_arr']);
		$hot_fail_total = count($res['hot_fail_arr']);
		$is_fail_total = count($res['is_fail_arr']);
// 		echo '无效总数：' . $invalid_total . '<br>';
// 		echo '重复总数：' . $repeat_total . '<br>';		
		echo 'HOT成功总数：' . $hot_suc_total . '<br>';
		echo 'IS成功总数：' . $is_suc_total . '<br>';
		echo 'HOT无效总数：' . $hot_fail_total . '<br>';
		echo 'IS无效总数：' . $is_fail_total . '<br>';
		echo '统计总数：' . ($hot_suc_total + $is_suc_total + $hot_fail_total+ $is_fail_total) . '<br>';
	
		echo '-------------------HOT失败分析如下-----------------------------------<br>';
		if($hot_fail_total > 0)
		{
			$hot_res = $this->fail_analysis($res['hot_fail_arr'], $btime);
			
			echo '先注册后打开：' . count($hot_res['time_error_arr']). '<br>';
			if(count($hot_res['time_error_arr']) > 0)
			{
				echo '<br>----HOT先注册后打开列表如下-----<br>';
				$this->format_res($hot_res['time_error_arr']);
			}
			
			echo 'ua不一致（time_error:先注册后打开）：' . count($hot_res['ua_diff_arr']['time_error']). '<br>';
			echo 'ua不一致(time_suc：时间顺序正常)：' . count($hot_res['ua_diff_arr']['time_suc']). '<br>';
			//echo '<pre>';print_r($hot_res['ua_diff_arr']);
			if (count($hot_res['ua_diff_arr']['time_error']) > 0)
			{
				echo '<br>----HOTua不一致（time_error:先注册后打开）列表如下-----<br>';
				$this->format_res($hot_res['ua_diff_arr']['time_error']);
			}
			if (count($hot_res['ua_diff_arr']['time_suc']) > 0)
			{
				echo '<br>----HOTua不一致（time_suc：时间顺序正常）列表如下-----<br>';
				$this->format_res($hot_res['ua_diff_arr']['time_suc']);
			}
			
			
			echo '设备类型不一致（time_error:先注册后打开）：' . count($hot_res['device_diff_arr']['time_error']). '<br>';
			echo '设备类型不一致(time_suc：时间顺序正常)：' . count($hot_res['device_diff_arr']['time_suc']). '<br>';			
			//echo '<pre>';print_r($hot_res['device_diff_arr']);
			if (count($hot_res['device_diff_arr']['time_error']) > 0)
			{
				echo '<br>----HOT设备类型（time_error:先注册后打开）不一致列表如下-----<br>';
				$this->format_res($hot_res['device_diff_arr']['time_error']);
			}
			if (count($hot_res['device_diff_arr']['time_suc']) > 0)
			{
				echo '<br>----设备类型不一致(time_suc：时间顺序正常)列表如下-----<br>';
				$this->format_res($hot_res['device_diff_arr']['time_suc']);
			}

			echo '其他：'.count($hot_res['others']).'<br>';
		}
	
		echo '--------------IS失败分析如下--------------------------------------------<br>';		
		if($is_fail_total > 0)
		{
			$is_res = $this->fail_analysis($res['is_fail_arr'], $btime);
			echo '先注册后打开：' . count($is_res['time_error_arr']). '<br>';	
			if(count($is_res['time_error_arr']) > 0)
			{
				echo '<br>----HOT先注册后打开列表如下-----<br>';
				$this->format_res($is_res['time_error_arr']);
			}
				
			echo 'ua不一致（time_error:先注册后打开）：' . count($is_res['ua_diff_arr']['time_error']). '<br>';
			echo 'ua不一致(time_suc：时间顺序正常)：' . count($is_res['ua_diff_arr']['time_suc']). '<br>';
			//echo '<pre>';print_r($is_res['ua_diff_arr']);
			if (count($is_res['device_diff_arr']['time_error']) > 0)
			{
				echo '<br>----HOT设备类型（time_error:先注册后打开）不一致列表如下-----<br>';
				$this->format_res($is_res['device_diff_arr']['time_error']);
			}
			if (count($is_res['device_diff_arr']['time_suc']) > 0)
			{
				echo '<br>----设备类型不一致(time_suc：时间顺序正常)列表如下-----<br>';
				$this->format_res($is_res['device_diff_arr']['time_suc']);
			}
				
			echo '设备类型不一致（time_error:先注册后打开）：' . count($is_res['device_diff_arr']['time_error']). '<br>';
			echo '设备类型不一致(time_suc：时间顺序正常)：' . count($is_res['device_diff_arr']['time_suc']). '<br>';
			//echo '<pre>';print_r($is_res['device_diff_arr']);
			if (count($is_res['device_diff_arr']['time_error']) > 0)
			{
				echo '<br>----HOT设备类型（time_error:先注册后打开）不一致列表如下-----<br>';
				$this->format_res($is_res['device_diff_arr']['time_error']);
			}
			if (count($is_res['device_diff_arr']['time_suc']) > 0)
			{
				echo '<br>----设备类型不一致(time_suc：时间顺序正常)列表如下-----<br>';
				$this->format_res($is_res['device_diff_arr']['time_suc']);
			}
			
			echo '其他：'.count($is_res['others']);
		}	
	}
	
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
			if($val['refer'] == 'h5.iphone' || $val['refer'] == 'h5.android' || $val['refer'] == 'mp.qq' || $val['refer'] == 'www.ishow' || $val['refer'] == 'ispeak.cn' || $val['refer'] == 'pc_client' || $val['refer'] == 'mp.weixin' || $val['status'] =='255')
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
				if(FALSE === strpos($refer, 'android')) //安卓
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
		$log_data = $this->log_data('http://58.215.165.153:8068/static/logs/log-' . date('Y-m-d', $btime) . '.log');				
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
						$h5_ua_info = $this->ua_info($val['h5ua']);
						$reg_ua_info = $this->ua_info($log_data[$temp_key]['ua']);
						$data[$key]['reg_ua'] = $log_data[$temp_key]['ua'];
						if($h5_ua_info == $reg_ua_info) //同机型
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
						$h5_ua_info = $this->ua_info($val['h5ua']);
						$reg_ua_info = $this->ua_info($log_data[$temp_key]['ua']);
						$data[$key]['reg_ua'] = $log_data[$temp_key]['ua'];
						if($h5_ua_info == $reg_ua_info) //同机型
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
	
	private function ua_info($useragent)
	{
		$ua = strtolower($useragent);
		$start = strpos($ua, '(') + 1;
		$ua_info = substr($ua, $start, (strpos($ua, ')') - $start));
		return $ua_info;
	}
	
	private function format_res($data)
	{
		$htm = '<hr><table><th>regip</th><th>min(reg.uid)</th><th>用户ID</th><th>regtime</th><th>bind_time</th><th>refer</th><th>reg_partnerid</th><th>h5partnerid</th><th>reg_subpartnerid</th><th>h5_client</th><th>h5ip</th><th>onclick</th><th>h5ua</th><th>h5time</th><th>推广员ID</th><th>h5_sid</th><th>Copy_Failed</th><th>绑定备注</th><th>status</th><th>version1</th><th>version2</th><th>loginip</th><th>reg_ua</th>';
		foreach ($data as $v)
		{
			$htm .= '<tr>';
			$htm .= '<td>'. $v['regip'] . '</td>';
			$htm .= '<td>'. $v['min(reg.uid)'] . '</td>';
			$htm .= '<td>'. $v['用户ID'] . '</td>';
			$htm .= '<td>'. $v['regtime'] . '</td>';
			$htm .= '<td>'. $v['bind_time'] . '</td>';
			$htm .= '<td>'. $v['refer'] . '</td>';
			$htm .= '<td>'. $v['reg_partnerid'] . '</td>';
			$htm .= '<td>'. $v['h5partnerid'] . '</td>';
			$htm .= '<td>'. $v['reg_subpartnerid'] . '</td>';
			$htm .= '<td>'. $v['h5_client'] . '</td>';
			$htm .= '<td>'. $v['h5ip'] . '</td>';
			$htm .= '<td>'. $v['onclick'] . '</td>';
			$htm .= '<td>'. $v['h5ua'] . '</td>';
			$htm .= '<td>'. $v['h5time'] . '</td>';
			$htm .= '<td>'. $v['推广员ID'] . '</td>';
			$htm .= '<td>'. $v['h5_sid'] . '</td>';
			$htm .= '<td>'. $v['Copy_Failed'] . '</td>';
			$htm .= '<td>'. $v['绑定备注'] . '</td>';
			$htm .= '<td>'. $v['status'] . '</td>';
			$htm .= '<td>'. $v['version1'] . '</td>';
			$htm .= '<td>'. $v['version2'] . '</td>';
			$htm .= '<td>'. $v['loginip'] . '</td>';
			if(isset($v['reg_ua']))
			{
				$htm .= '<td>'. $v['reg_ua'] . '</td>';
			}
			$htm .= '</tr>';
		}
		$htm .= '</table><hr>';
		echo $htm;
	}
	
}

$test = new Bind_statistics();
$test->index();
