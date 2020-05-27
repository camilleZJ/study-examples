<?php
class Level
{
	private $user_DBHost = '119.120.92.168';
	private $user_DBUser = 'is_test_user';
	private $user_DBPass = 'is_test_userpwd';
	private $user_DBName = 'gift_db';

	private $mysqli = NULL;
	
	private function conn($dbname)
	{
		$this->user_DBName = $dbname;
		$this->mysqli = new mysqli($this->user_DBHost, $this->user_DBUser, $this->user_DBPass, $this->user_DBName);
		if ($this->mysqli->connect_errno)
		{
			return FALSE;
		}
		$this->mysqli->set_charset('utf8');
		return TRUE;
	}

	private function close()
	{
		if ($this->mysqli != NULL)
		{
			$this->mysqli->close();
			$this->mysqli = NULL;
		}
	}

	public function get_anchor_total_consume_experience($uid, $partnerid)
	{
		if ($this->conn('gift_db'))
		{
			$res = NULL;
			$sql = "select uid, total_consume_experience from user_experiences where uid = $uid and partnerid = $partnerid";
			if ($result = $this->mysqli->query($sql))
			{
				$row = $result->fetch_array(MYSQLI_ASSOC);
				$result->free();
			}
			return $row;
		}
		$this->close();
	}

	public function get_anchor_gift_log($uid)
	{
		if ($this->conn('business_user'))
		{
			$res = array();
			$sql = "select * from send_gift_log where accepterid = $uid order by logdate asc";
			if ($result = $this->mysqli->query($sql))
			{
				while($row = $result->fetch_array(MYSQLI_ASSOC))
				{
					$res[] = $row;
				}
				$result->free();
			}
			return $res;
		}
		$this->close();
	}

	/**
	 * 根据等级计算经验值
	 * @return 
	 */
	private function get_exp_by_level($level)
	{
		if ($level >= 1)
		{
			return $level*1.5*pow(10,8);
		}
		else
		{
			return 1;
		}
	}

	/**
	 * 根据经验值计算当前等级
	 * @param $exp 当前经验值
	 * @return int 等级
	 */
	private function get_level_by_exp($low, $high, $exp)
	{
		$max_level = 4;
		if ($exp < 1)
		{
		  	return 0;
		}
		if ($exp > $this->get_exp_by_level($max_level))
		{
		  	return $max_level;
		}
		if ($low > $high)
		{
			return 0;
		}
		$mid = floor(($low + $high ) / 2);

		if (($this->get_exp_by_level($mid) <= $exp) && ($this->get_exp_by_level($mid + 1) > $exp))
		{
			return $mid;
		}
		elseif ($this->get_exp_by_level($mid)  < $exp)
		{
			return $this->get_level_by_exp($mid + 1, $high, $exp);
		}
		else
		{
			return $this->get_level_by_exp($low, $mid - 1, $exp);
		}
	}

	/**
	 * 获得主播礼物记录中返点数总和[暂时测试用]
	 */
	public function get_anchor_point($gifts)
	{
		if (FALSE === $gifts)
		{
			return 0;
		}
		$result = 0;
		foreach ($gifts as $gift) {
			$result += $gift['toPoint'];
		}
		return $result;
	}

	/**
	 * 重新整理结果集，计算排名前3礼物发送者&发送礼物总量
	 */
	private function get_user_gifts_send($data)
	{
		/*计算同一sender，所发送的礼物总量*/
		$result = array();
		foreach ($data as $key => $value) {
			$result[$value['senderid']]['explist'][] = $value['toPoint'];
			$result[$value['senderid']]['sendernickname'] = $value['sendernickname'];
		}

		foreach ($result as $key => $value) {
			//$res[$key] = array_sum($value);
			$result[$key]['exp'] = array_sum($value['explist']);
			unset($result[$key]['explist']);
		}
		//降序排序
		uasort($result, function($value1,$value2){
			$exp1 = $value1['exp'];
			$exp2 = $value2['exp'];
			if ($exp1 == $exp2)
			{
				return 0;
			}
			return $exp1 < $exp2 ? 1 : -1;
		});
		//只要前3名
		$info = array();
		foreach ($result as $key => $value) {
			$info[] = $key;
			if (count($info) >= 3)
			{
				break;
			}
		}

		/*重新整理数组*/
		$final_res = array();
		for ($i=0;$i<count($info);$i++)
		{
			$t = $result[$info[$i]];
			$t['senderid'] = $info[$i];
			$final_res[] = $t;
		}
		unset($result);
		unset($info);
		return $final_res;
	}

	public function level_growing($uid)
	{
		//获取主播所有礼物记录
		$gifts = $this->get_anchor_gift_log($uid);
		//获取主播经验总值：现在是提取所有礼物返点总和，并不确定是否为这个值
		$anchor_current_exp = $this->get_anchor_point($gifts);
		//计算主播当前等级
		$anchor_current_level = $this->get_level_by_exp(1, 4, $anchor_current_exp);

		var_dump(number_format($anchor_current_exp));
		var_dump('主播当前等级：'.$anchor_current_level);

		$i = 1;
		$tmp_total_exp = 0;
		$lv_up_gift = array();//协助升级礼物集合
		$growing_gift = array();//等级成长[送礼者&钻石]信息集合
		while($i<=$anchor_current_level)
		{
			//从等级1 --> 主播到当前等级
			$current_level_exp = $this->get_exp_by_level($i);
			//var_dump(number_format($current_level_exp));
			//遍历所有礼物
			foreach ($gifts as $key => $gift)
			{
				/*-------------测试输出------------------*/
				$senderid = $gift['senderid'];
				$logid = $gift['logid'];
				$logdate = $gift['logdate'];
				/*----------------------------------------*/
				$point = $gift['toPoint'];
				/*依次叠加toPoint*/
				if (($tmp_total_exp+$point) < $current_level_exp)
				{
					/*未超过升级所需求经验值时，计入等级成长数组*/
					$growing_gift[$i][] = $gift;
					$tmp_total_exp += $point;
					//var_dump("$i --> total exp: $tmp_total_exp | current row toPoint: $point --> logid: $logid | senderid: $senderid | logdate: $logdate");
				}
				else
				{
					//当超过升级数值时,当前礼物计入lv_up_gift
					$gift['help_lv'] = $i;
					$lv_up_gift[$i] = $gift;
					$i++;
					$tmp_total_exp += $point;
					//var_dump(" @ $i --> total exp: $tmp_total_exp | current row toPoint: $point --> logid: $logid | senderid: $senderid | logdate: $logdate");
					break;
				}
				unset($gifts[$key]);
			}
		}
		
		foreach ($growing_gift as $key => $gift) {
			$growing_gift[$key] = $this->get_user_gifts_send($gift);
		}
		/*var_dump($growing_gift);
		var_dump($lv_up_gift);*/
		foreach ($growing_gift as $key => $value) {
			$growing_gift[$key]['level_up_helper'] = $lv_up_gift[$key]['logid'];
			$growing_gift[$key]['level_up_date'] = $lv_up_gift[$key]['logdate'];
		}
		unset($lv_up_gift);
		var_dump($growing_gift);
	}
}

$level_info = new Level();

$level_info->level_growing('80061905');
//$level_info->gift_test('80000019');