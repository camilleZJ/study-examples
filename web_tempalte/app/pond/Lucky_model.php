<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lucky_model extends MY_Model
{
	private $gift_db = NULL;
	private $gift_id = array(10388);//水雷

	public function __construct()
	{
		parent::__construct();
		$this->error = new stdClass;
		$this->gift_db = $this->load->database('gift_db', TRUE);
	}

	public function __destruct()
	{
		if (isset($this->gift_db))
		{
			$this->gift_db->close();
			$this->gift_db = NULL;
		}
		$this->error = NULL;
	}

	/**
	 * 获取奖池开始结束时间
	 */
	public function get_pond_time($partnerid = 126)
	{
		$this->gift_db->select('page_begin_time,page_end_time')
			->from('hd_config_201512')
			->order_by('page_begin_time', 'DESC')
			->where('partnerid', $partnerid)
			->limit(1);
		if (FALSE === ($query = $this->gift_db->get()))
		{
			$this->error = (object)$this->gift_db->error();

			return FALSE;
		}
		elseif (0 === $query->num_rows())
		{
			return NULL;
		}
		$res = $query->row_array();
		$query->free_result();

		return $res;
	}

	public function get_info()
	{
		$data = array('total' => 0, 'rowss' => array(), 'pool' => array());

		$this->gift_db->select('FLOOR(pool_num1*2/3) as userpool, pool_num2 as anchorpool, page_begin_time, page_end_time')
			->from('hd_config_201512')
			->order_by('page_begin_time', 'DESC')
			->where('partnerid', 126)
			->limit(1);

		if (FALSE === ($query = $this->gift_db->get()))
		{
			$this->error = (object)$this->gift_db->error();

			return FALSE;
		}
		$data['pool'] = $query->row_array();
		$query->free_result();

		$start_time = $data['pool']['page_begin_time'];
		$end_time = $data['pool']['page_end_time'];
		unset($query, $data['pool']['page_begin_time'], $data['pool']['page_end_time']);

		$total= $this->gift_db->where('dt >=', $start_time)->where('dt <=', $end_time)->where('partnerid', 126)->count_all_results('hd_log_201512', FALSE);
		if ($total> 0)
		{
			$this->gift_db->select('send_name as sendernickname, send_uid, giftid, to_name as accepternickname, to_uid, viewroomid as roomid, send_award as rewardpoint, to_award as topint, dt as dates')
				->order_by('dt', 'desc');
			if (FALSE === ($query = $this->gift_db->get()))
			{
				$this->error = (object)$this->gift_db->error();

				return FALSE;
			}
			$data['rowss'] = $query->result_array();
			$query->free_result();
		}

		return $data;
	}

	public function winner_list()
	{
		$data = array();

		$this->gift_db->select('FLOOR(pool_num1*2/3) as userpool, pool_num2 as anchorpool, page_begin_time, page_end_time')
			->from('hd_config_201512')
			->order_by('page_begin_time', 'DESC')
			->where('partnerid', 126)
			->limit(1);

		if (FALSE === ($query = $this->gift_db->get()))
		{
			$this->error = (object)$this->gift_db->error();

			return FALSE;
		}
		$data['pool'] = $query->row_array();
		$query->free_result();

		$start_time = $data['pool']['page_begin_time'];
		$end_time = $data['pool']['page_end_time'];
		unset($query, $data['pool']);

		$data['total'] = $this->gift_db->where('dt >=', $start_time)->where('dt <=', $end_time)->where('partnerid', 126)->count_all_results('hd_log_201512', FALSE);
		if ($data['total'] > 0)
		{
			$this->gift_db->select('send_name as sendernickname, send_uid, giftid, to_name as accepternickname, to_uid, viewroomid as roomid, send_award as rewardpoint, to_award as topint, dt as dates')
				->order_by('dt', 'desc');
			if (FALSE === ($query = $this->gift_db->get()))
			{
				$this->error = (object)$this->gift_db->error();

				return FALSE;
			}
			$data['datas'] = $query->result_array();
			$query->free_result();
		}

		return $data;
	}

	/**
	 * 计算主播榜
	 * @param	$limit
	 * @param	$type 2 主播榜   3富豪榜
	 */
	public function rank_lists($limit = 3, $type = 2, $bdate, $edate)
	{
		$giftid_arr = $this->gift_id;
		if (FALSE === $this->gift_db->conn_id)
		{
			$this->error = (object)$this->gift_db->error();
			return FALSE;
		}
		if (2 == $type)
		{
			$this->gift_db->select('`to_uid` as `uid`, sum( to_award ) as `total`');
		}
		elseif (3 == $type)
		{
			$this->gift_db->select('send_uid as uid, sum( send_award ) as total');
		}
		else
		{
			return NULL;
		}
		$this->gift_db->from('hd_log_201512')
				->where_in('giftid', $giftid_arr)
				->where('dt >=', $bdate)
				->where('dt <=', $edate)
				->where('partnerid', 126)
				->group_by('uid')
				->having('total >', 0)
				->order_by('total', 'DESC')
				->limit($limit);

		if (FALSE === ($query = $this->gift_db->get()))
		{
			$this->error = (object)$this->gift_db->error();
			return FALSE;
		}

		if (0 === $query->num_rows())
		{
			return NULL;
		}
		$res = $query->result_array();
		$query->free_result();
		return $res;
	}

	public function rank_self($uid, $type, $bdate, $edate)
	{
		$giftid_arr = $this->gift_id;
		if (FALSE === $this->gift_db->conn_id)
		{
			$this->error = (object)$this->gift_db->error();
			return FALSE;
		}
		if (2 == $type)
		{
			$uid_field = 'to_uid';
			$select_sql =  'to_award';
		}
		elseif (3 == $type)
		{
			$uid_field = 'send_uid';
			$select_sql =  'send_award';
		}
		else
		{
			return NULL;
		}
		$commen_sql = 'giftid IN('. implode(',', $giftid_arr) . ') AND partnerid = 126 AND dt >= "' . $bdate . '" AND dt <= "' . $edate . '"';
		$sql = "
			SELECT
				SUM( {$select_sql} ) AS self_total
			FROM hd_log_201512
			WHERE {$commen_sql}
			AND {$uid_field} = {$uid}
			GROUP BY {$uid_field}
			HAVING self_total >0";

		if (FALSE === ($query = $this->gift_db->query($sql)))
		{
			$this->error = (object)$this->gift_db->error();
			return FALSE;
		}
		elseif (0 === $query->num_rows())
		{
			return NULL;
		}
		$res = $query->row_array();
		$query->free_result();
		$self_total = $res['self_total'];
		unset($sql, $query, $res);
		$sql = "
			SELECT
				COUNT(1) AS self_rank
			FROM (
				SELECT
					{$uid_field}, SUM( {$select_sql} ) AS total
				FROM hd_log_201512
				WHERE {$commen_sql}
				GROUP BY {$uid_field}) a
				WHERE total > {$self_total}";
		if (FALSE === ($query = $this->gift_db->query($sql)))
		{
			$this->error = (object)$this->gift_db->error();
			return FALSE;
		}
		elseif (0 === $query->num_rows())
		{
			return NULL;
		}
		$res = $query->row_array();
		$query->free_result();
		$self_rank = $res['self_rank'] + 1;
		unset($sql, $query, $res);
		return array('self_rank' => $self_rank, 'self_total' => $self_total);
	}

	public function my_records($uid)
	{
		if (FALSE === $this->gift_db->conn_id)
		{
			$this->error = (object)$this->gift_db->error();
			return FALSE;
		}
		$this->gift_db->select('send_uid, send_name, send_award, to_uid, to_name, to_award, dt')
			->from('hd_log_201512')
			->where('state',1)
			->where_in('giftid',$this->gift_id)
			->where('send_uid',$uid)
			->or_where('to_uid',$uid)
			->where('partnerid',126)
			->order_by('dt','desc');
		if (FALSE === ($query = $this->gift_db->get()))
		{
			$this->error = (object)$this->gift_db->error();
			return FALSE;
		}
		if (0 === $query->num_rows())
		{
			return NULL;
		}
		$res = $query->result_array();
		$query->free_result();
		return $res;
	}
}
