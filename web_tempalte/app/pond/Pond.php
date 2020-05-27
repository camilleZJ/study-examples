<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pond extends MY_Controller
{
	private $bdate 			= "";
	private $edate 			= "";
	private $sessuser 		= NULL;

	public function __construct()
	{
		parent::__construct();
		$this->load->driver('cache');
		$this->sessuser = array();
		$this->init_user();
		if (FALSE == ($times = $this->get_pond_time()))
		{
			show_error('缺少奖池配置，请稍后再试！', 200, '服务器错误');
		}
		$this->bdate = $times['page_begin_time'] == "" ? "0000-00-00 00:00:00" : $times['page_begin_time'];
		$this->edate = $times['page_end_time'] == "" ? "0000-00-00 00:00:00" : $times['page_end_time'];
	}

	public function index()
	{
		$aid = $this->uri->segment(3);
		$uid = $this->uri->segment(4);
		if (NULL === $uid || FALSE === $this->input->valid_sint32($uid) || 0 === $uid)
		{
			show_error('参数错误(4001)', 401);
		}
		elseif ($uid !== $this->sessuser['appuid'])
		{
			$this->init_user($uid);
		}

		$partnerid = $this->input->get('partnerid');

		if (FALSE === $this->input->valid_sint32($partnerid))
		{
			$partnerid = 126;
		}

		if (NULL === ($showroomid = $this->input->get('chlShowId')) || FALSE === $this->input->valid_sint32($showroomid) || 0 === $showroomid)
		{
			$showroomid = 0;
		}
		$cachekey = sprintf('ispeak:gift:pond:infos:%d', 0);//cache key
		if (FALSE === ($data = $this->cache->get($cachekey)))
		{
			$this->load->model('Lucky_model');
			if (FALSE === ($data = $this->Lucky_model->get_info()))
			{
				show_error(sprintf('%s(%d)', $this->Lucky_model->error->message, $this->Lucky_model->error->code), 200, '服务器错误');
			}
			$this->cache->save($cachekey, $data, 10);
		}

		$data['page'] = 1;
		$data['pages'] = ceil($data['total']);
		if (count($data['pool']) > 0)
		{
			$data['pool']['anchorpool'] = str_split($data['pool']['anchorpool']);
			$data['pool']['userpool'] = str_split($data['pool']['userpool']);
		}
		if (count($data['rowss']) > 0)
		{
			$data['rowss'] = $this->set_pager_info($data['rowss']);
		}

		$this->load->vars('conke', $this->parse_cookie_data(json_encode($this->sessuser)));
		$this->load->vars('aid', $aid);
		$this->load->vars('userface_address', 'http://userface.ispeak.cn/');
		$this->load->vars('weburl', base_url('static/template/images'));
		$this->load->vars('room', $showroomid);
		$this->load->vars('bdate', strtotime($this->bdate));
		$this->load->vars('edate', strtotime($this->edate));
		$this->load->vars('now', TIMESTAMP);
		$this->load->vars('data', $data);
		$this->load->view('pond.hd.index.php');
	}

	public function get_winners_list()
	{
		$cachekey = sprintf('ispeak:gift:winnerlist:infos:%d', 0);//cache key
		$cacheid = 'H5_17MAYA_HOLIDAY_WINNER_LISTS';
		if (FALSE === ($data = $this->cache->get($cachekey)))
		{
			$this->load->model('Lucky_model');
			if (FALSE === ($data = $this->Lucky_model->winner_list()))
			{
				show_error(sprintf('%s(%d)', $this->Lucky_model->error->message, $this->Lucky_model->error->code), 200, '服务器错误');
			}
			$this->cache->save($cachekey, $data, 10);
		}
		if (count($data['datas']) > 0)
		{
			$data['datas'] = $this->set_pager_info($data['datas']);
		}
		return (array)$data;
	}

	public function pool()
	{
		$cachekey = sprintf('ispeak:gift:pool:infos:%d', 0);//cache key
		$cacheid = 'H5_17MAYA_HOLIDAY_POOL_LISTS';
		if (FALSE === ($data = $this->cache->get($cachekey)))
		{
			$this->load->model('Lucky_model');
			if (FALSE === ($data = $this->Lucky_model->get_info()))
			{
				show_error(sprintf('%s(%d)', $this->Lucky_model->error->message, $this->Lucky_model->error->code), 200, '服务器错误');
			}
			unset($data['rowss']);
			$this->cache->save($cachekey, $data, 10);
		}
		$self = count($data['pool']);
		$this->output->json(array('code' => 0, 'self' => $self, 'data' => $data['pool']));
	}

	private function get_pond_time()
	{
		$partnerid = 126;
		$cachekey = 'H5_17MAYA_GIFT_POND_CONFIG_TIMES';//cache key
		if (FALSE === ($times = $this->cache->get($cachekey)))
		{
			$this->load->model('Lucky_model');
			if (FALSE === ($times = $this->Lucky_model->get_pond_time($partnerid)))
			{
				show_error(sprintf('%s(%d)', $this->Lucky_model->error->message, $this->Lucky_model->error->code), 200, '服务器错误');
			}
			elseif (NULL === $times)
			{
				//show_error('缺少奖池配置，请稍后再试！', 200, '服务器错误');
				$times = array('page_begin_time'=>'0000-00-00 00:00:00','page_end_time'=>'0000-00-00 00:00:00');
			}
			else
			{
				$this->cache->save($cachekey, $times, 300);
			}
		}
		return (array)$times;
	}

	public function rank($aid = 0, $type = 2, $return = FALSE)
	{
		if (FALSE === $return)
		{
			if (TRUE !== $this->input->is_ajax_request())
			{
				show_error('请求出错(4001)', 401);
			}
			$type = (int)$this->input->post('titleid');
			$aid = (int)$this->input->post('aid');
		}
		if (! in_array($type, array(1,2, 3)))
		{
			if ($this->input->is_ajax_request())
			{
				$this->output->json(array('code' => 4001, 'message' => '请求出错'));
			}
			show_error('请求出错(4001)', 401);
		}

		if ((NULL === $aid || FALSE === $this->input->valid_sint32($aid) || 0 === $aid) && $type !==1)
		{
			if ($this->input->is_ajax_request())
			{
				$this->output->json(array('code' => 4002, 'message' => '参数错误'));
			}
			show_error('参数错误(4002)', 401);
		}
		$cacheid = 'H5_17MAYA_HOLIDAY_RANK_LISTS_' . $type;
		if (FALSE === ($data = $this->cache->get($cacheid)))
		{
			$this->load->model('Lucky_model');
			if (FALSE === ($res = $this->Lucky_model->rank_lists(10, $type, $this->bdate, $this->edate)))
			{
				if ($this->input->is_ajax_request())
				{
					$this->output->json(array('code' => 5031, 'message' => '服务出错'));
				}
				show_error('服务出错(5031)', 503);
			}
			elseif (NULL === $res)
			{
				$data = array();
			}
			else
			{
				$uids = array();
				foreach ($res as $val)
				{
					$uids[] = $val['uid'];
				}
				$rs = $this->api_request('user/infos', array('uid' => implode(',', $uids)), 'POST', 'curl', 'user/infos');
				if (FALSE === $rs)
				{
					if ($this->input->is_ajax_request())
					{
						$this->output->json(array('code' => 5033, 'message' => '服务不可用'));
					}
					show_error('服务不可用(5033)', 503);
				}
				elseif (0 != $rs->code)
				{
					if ($this->input->is_ajax_request())
					{
						$this->output->json(array('code' => $rs->code, 'message' => '服务不可用'));
					}
					show_error('服务不可用(' . $rs->code . ')', 503);
				}
				$infos = $rs->infos;
				foreach ($res as &$row)
				{
					$uid = $row['uid'];
					$row['total'] = number_format($row['total']);
					if ($infos->{$uid}->icon == 0)
					{
						$row['icon'] = $infos->{$uid}->avatar->medium;
					}
					else
					{
						$row['icon'] = 'default/2126M.png';
					}
					$row['nickname'] = $infos->{$uid}->nickname;
				}
				$data = $res;
				unset($res, $rs, $infos);
				#批量获取用户等级
				$lel_res = $this->api_request('user/level', array('uid' => implode(',', $uids)), 'POST', 'curl', 'user/level');
				if (FALSE === $lel_res)
				{
					if ($this->input->is_ajax_request())
					{
						$this->output->json(array('code' => 5033, 'message' => '服务不可用'));
					}
					show_error('服务不可用(5033)', 503);
				}
				elseif (0 != $lel_res->code)
				{
					if ($this->input->is_ajax_request())
					{
						$this->output->json(array('code' => $lel_res->code, 'message' => '服务不可用'));
					}
					show_error('服务不可用(' . $lel_res->code . ')', 503);
				}
				unset($uids);
				foreach ($data as &$v)
				{
					$tid = $v['uid'];
					$v['level'] = $lel_res->{$tid};
					unset($tid);
				}
				unset($lel_res);
			}
			$this->cache->save($cacheid, $data, 8);
		}
		if ($type == 1)
		{
			$winner = array();
			$winner = (array)$this->get_winners_list();
			$this->output->json(array('code' => 0,'data' => $winner['datas']));
		}
		$tid = $type == 2 ? $aid : $this->sessuser['appuid'];
		// $tid = '80062243';
		$self_cacheid = 'H5_17MAYA_HOLIDAY_RANK_SELF_' . $type . $tid;
		if (FALSE === ($self = $this->cache->get($self_cacheid)))
		{
			$self = array('self_rank' => 0, 'self_total' => 0);
			$this->load->model('Lucky_model');
			if (FALSE === ($res = $this->Lucky_model->rank_self($tid, $type, $this->bdate, $this->edate)))
			{
				if ($this->input->is_ajax_request())
				{
					$this->output->json(array('code' => 5031, 'message' => '服务出错'));
				}
				show_error('服务出错(5031)', 503);
			}
			elseif (NULL !== $res)
			{
				$self = array('self_rank' => $res['self_rank'], 'self_total' => $res['self_total']);
				unset($res);
				$path = sprintf('user/%d/extend/square', $tid);
				$rs = $this->api_request($path, array(), 'GET', 'curl', $path);
				if (FALSE === $rs)
				{
					if ($this->input->is_ajax_request())
					{
						$this->output->json(array('code' => 5033, 'message' => '服务不可用'));
					}
					show_error('服务不可用(5033)', 503);
				}
				elseif (0 != $rs->code)
				{
					if ($this->input->is_ajax_request())
					{
						$this->output->json(array('code' => $rs->code, 'message' => '服务不可用'));
					}
					show_error('服务不可用(' . $rs->code . ')', 503);
				}
				if ($rs->icon == 0)
				{
					$self['icon'] = $rs->avatar->medium;
				}
				else
				{
					$self['icon'] = 'default/2126M.png';
				}
				$self['nickname'] = $rs->nickname;
				$self['level'] = $rs->extend->level;
				unset($path, $rs);
			}
			$this->cache->save($self_cacheid, $self, 8);
		}
		if (TRUE === $return)
		{
			return array('self' => $self, 'data' => $data);
		}
		if (empty($data))
		{
			$this->output->json(array('code' => 4040));
		}
		$this->output->json(array('code' => 0, 'self' => $self, 'data' => $data));
	}

	private function init_user($uid = FALSE)
	{
		if (FALSE == ($conke = $this->input->get('conke')) ||
		 NULL === ($this->sessuser = json_decode($this->parse_cookie_data($conke, 'decode'), TRUE)) ||
		 1 > $this->sessuser['appuid'] ||
		 ($uid !== FALSE && $uid != $this->sessuser['appuid']))
		{
			if (FALSE === $uid)
			{
				$uid = $this->uri->segment(4);
				if (NULL === $uid || FALSE === $this->input->valid_sint32($uid) || 0 === $uid)
				{
					log_message('error', $this->uri->uri_string() . "--no_user_data--"."\n".json_encode($this->input->request_headers()));
					if ($this->input->is_ajax_request())
					{
						$this->output->json(array('code' => 4000, 'message' => '参数错误'));
					}
					show_error('参数错误(4000)', 401);
				}
			}
			$time = $this->input->get('time');
			if (NULL === $time)
			{
				if ($this->input->is_ajax_request())
				{
					$this->output->json(array('code' => 4001, 'message' => '参数错误'));
				}
				show_error('请在个人设置界面检查更新，<br/>或在官网www.021jilang.com下载最新版本', 401);
			}
			elseif (FALSE === $this->input->valid_sint32($time) || 0 === $time || TIMESTAMP > $time)
			{
				if ($this->input->is_ajax_request())
				{
					$this->output->json(array('code' => 4002, 'message' => '参数错误'));
				}
				show_error('参数错误(4002)', 401);
			}
			$sessionkey = $this->input->get('sessionkey');
			if (FALSE == $sessionkey)
			{
				if ($this->input->is_ajax_request())
				{
					$this->output->json(array('code' => 4003, 'message' => '参数错误'));
				}
				show_error('请在个人设置界面检查更新，<br/>或在官网www.021jilang.com下载最新版本', 401);
			}

			$res = $this->api_request('platform/privatekey/login', array(), 'GET', TRUE, FALSE, TRUE);
			if (FALSE === $res)
			{
				if ($this->input->is_ajax_request())
				{
					$this->output->json(array('code' => 5031, 'message' => '服务不可用'));
				}
				show_error('服务不可用(5031)', 503);
			}
			elseif (0 !== $res->code)
			{
				if ($this->input->is_ajax_request())
				{
					$this->output->json(array('code' => $res->code, 'message' => '服务不可用'));
				}
				show_error('服务不可用('. $res->code .')', 503);
			}
			$valid = FALSE;
			foreach ($res->keys as $item)
			{
				$k = is_object($item) ? $item->keyinfo : $item['keyinfo'];
				if ($sessionkey === md5($uid . $time . $k) )
				{
					$valid = TRUE;
					break;
				}
			}
			if (FALSE === $valid)
			{
				if ($this->input->is_ajax_request())
				{
					$this->output->json(array('code' => 4004, 'message' => '参数错误'));
				}
				show_error('参数错误(4004)', 401);
			}
			$this->sessuser['appuid'] = $uid;
		}
	}

	private function set_pager_info($data)
	{
		foreach ($data as &$value)
		{
			$unix = strtotime($value['dates'], TIMESTAMP);
			$value['dates'] = date('m.d H:i', $unix);
			$value['sendernickname'] = htmlspecialchars($value['sendernickname'], ENT_IGNORE ,'UTF-8');
			$value['accepternickname'] = htmlspecialchars($value['accepternickname'], ENT_IGNORE ,'UTF-8');
			$value['rewardpoint'] = number_format($value['rewardpoint']);
			if ($value['topint'] != 0)
				$value['topint'] = number_format($value['topint']);
		}

		return $data;
	}

	public function records($return = FALSE)
	{
		if (FALSE === $return)
		{
			if (TRUE !== $this->input->is_ajax_request())
			{
				show_error('请求出错(4001)', 401);
			}
		}
		$uid = $this->sessuser['appuid'];
		$cacheid = 'H5_17MAYA_HOLIDAY_RECORDS_' . $uid;
		if (FALSE === ($data = $this->cache->get($cacheid)))
		{
			$this->load->model('Lucky_model');
			if (FALSE === ($res = $this->Lucky_model->my_records($uid)))
			{
				if ($this->input->is_ajax_request())
				{
					$this->output->json(array('code' => 5031, 'message' => $this->Lucky_model->error));
				}
			}
			else if (NULL === $res)
			{
				$data = array();
			}
			else
			{
				$data = $res;
				foreach ($data as $key => $value)
				{
					if ($uid == $value['to_uid'])
					{
						$data[$key]['to_me'] = TRUE;
					}
					else
					{
						$data[$key]['to_me'] = FALSE;
					}
					$data[$key]['dt'] = date('Y.m.d H:i',strtotime($value['dt']));
					$data[$key]['send_name'] = htmlspecialchars($value['send_name'], ENT_IGNORE ,'UTF-8');
					$data[$key]['to_name'] = htmlspecialchars($value['to_name'], ENT_IGNORE ,'UTF-8');
				}
			}
			$this->cache->save($cacheid, $data, 10);
		}
		$this->output->json(array('code' => 0, 'data' => $data, 'total'=>count($data)));
	}

}
