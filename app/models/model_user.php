<?php
class Model_user extends Model {
	function Model_user()
	{
		parent::Model();
	}

	
	function get_history_changes_list($where = null, $sort = null, $limit_start = 0, $limit_end = PER_PAGE_HISTORY_CHANGES)
	{
		
		$this->db->select('*')->from('change_histories')
		->join('users', 'change_histories.change_user_id=users.user_id');
		
		if (!empty($where))
		{
			$this->db->where($where);
		}
		
		if (!empty($sort))
		{
			$this->db->orderby($sort);
		}
		
		$this->db->limit(intval($limit_end), intval($limit_start));
		
		$result = $this->db->get();
		
		if (!empty($result) and count($result) > 0)
			
			return $result->result_array();
		else
			return null;
		
	}
	
	
	function _select($where = '', $select = '', $limit = "", $order = "")
	{
		$this->db->select($select);
		$this->db->from('user');
		if (!empty($where)) $this->db->where($where);
		if (!empty($order))
		{
			$this->db->orderby($order);
		}
		else
		{
			$this->db->orderby('id', 'desc');
		}

		if (!empty($limit)) $this->db->limit($limit);

		$query = $this->db->get();
		return ($query->result_array());
	}

	function _select_full($where = '', $select = '', $limit = "", $order = "")
	{
		$this->db->select($select);
		$this->db->from('user');
		$this->db->join('avatar', "avatar.id = user.avatar_id", "left");
		if (!empty($where)) $this->db->where($where);
		if (!empty($order))
		{
			$this->db->orderby($order);
		}
		else
		{
			$this->db->orderby('id', 'desc');
		}

		if (!empty($limit)) $this->db->limit($limit);

		$query = $this->db->get();
		return ($query->result_array());
	}

	function _select_page($page=1, $limit = '50', $order = 'rating desc')
	{	$is_admin=false;
		if (isset($_SESSION['user']) && isset($_SESSION['user']['is_admin']) && $_SESSION['user']['is_admin'])
		{
			$where=''; //j.status>0 and j.status<4 and
			$is_admin=true;
		}
		else
		{
			$where='where u.blocked=0'; //j.status>0 and j.status<4 and
		}
		//echo $page;
//		$query = $this->db->query('select u.id, a.url, u.nikname, DATE_FORMAT(u.add_date,"%d/%m/%y") as date, count(j.id) as c, u.useful_comments as uc, u.rating, u.blocked
		$query = $this->db->query('select u.id, a.url, u.nikname, DATE_FORMAT(u.add_date,"%d/%m/%y") as date, sum(if (j.status>0 and j.status<4,1,0)) as c, u.useful_comments as uc, u.rating, u.blocked, u.pos_votes as pos, u.neg_votes as neg
							from user u
							left join project j on j.user_id=u.id
							left join avatar a on a.id=u.avatar_id
							'.$where.'
							group by u.id
							order by '.$order.', id limit '.(($page-1)*$limit).','.$limit);
		$r=$query->result_array();
		/*if ($is_admin) {
			foreach ($r as $k=>$row){

			}
		}*/
		//echo $this->db->last_query();
		return ($r);
	}
	
	function _select_page2($page=1, $limit = '50', $order = 'rating desc')
	{	$is_admin=false;
		if (isset($_SESSION['user']) && isset($_SESSION['user']['is_admin']) && $_SESSION['user']['is_admin'])
		{
			$where=" where reg_date > '2010-04-26'";
			$is_admin=true;
		}
		else
		{
			$where="where u.blocked=0 and reg_date > '2010-04-26'";
		}
		$query = $this->db->query('select u.id, a.url, u.nikname, DATE_FORMAT(u.add_date,"%d/%m/%y") as date, sum(if (j.status>0 and j.status<4,1,0)) as c, u.useful_comments as uc, u.rating, u.blocked, u.pos_votes as pos, u.neg_votes as neg
							from user u
							left join project j on j.user_id=u.id
							left join avatar a on a.id=u.avatar_id
							'.$where.'
							group by u.id
							order by '.$order.', id limit '.(($page-1)*$limit).','.$limit);
		$r=$query->result_array();

		return ($r);
	}	

/*	function _select_spamers($page=1, $limit = '20', $order = 'rating desc')
	{
		if (isset($_SESSION['user']) && isset($_SESSION['user']['is_admin']) && $_SESSION['user']['is_admin'])
		{
			$where=''; //j.status>0 and j.status<4 and
		}
		else
		{
			$where='where u.blocked=0'; //j.status>0 and j.status<4 and
		}


		$result=array();
		$query = $this->db->query('select user_id as uid,count(*) as c from vote group by user_id order by c desc limit '.(($page-1)*$limit).','.$limit);
		$res=$query->result_array();
		foreach ($res as $r){
			$query = $this->db->query('select u.id, a.url, u.nikname, DATE_FORMAT(u.add_date,"%d/%m/%y") as date, sum(if (j.status>0 and j.status<4,1,0)) as c, u.useful_comments as uc, u.rating, u.blocked
							from user u
							left join project j on j.user_id=u.id
							left join avatar a on a.id=u.avatar_id
							'.$where.'
							group by u.id
		}

		//echo $page;
		//		$query = $this->db->query('select u.id, a.url, u.nikname, DATE_FORMAT(u.add_date,"%d/%m/%y") as date, count(j.id) as c, u.useful_comments as uc, u.rating, u.blocked
		$query = $this->db->query('select u.id, a.url, u.nikname, DATE_FORMAT(u.add_date,"%d/%m/%y") as date, sum(if (j.status>0 and j.status<4,1,0)) as c, u.useful_comments as uc, u.rating, u.blocked
							from user u
							left join project j on j.user_id=u.id
							left join avatar a on a.id=u.avatar_id
							'.$where.'
							group by u.id
							order by '.$order.', id limit '.(($page-1)*$limit).','.$limit);

		//echo $this->db->last_query();
		return ($query->result_array());
	}*/

	function _get_count()
	{
		if (isset($_SESSION['user']) && isset($_SESSION['user']['is_admin']) && $_SESSION['user']['is_admin'])
		{
			$query=$this->db->query('select count(*) as c from user');
		}
		else
		{
			$query=$this->db->query('select count(*) as c from user where blocked=0');
		}

		$a=$query->result_array();
		return $a[0]['c'];
	}


	function _insert($data)
	{
		$this->db->insert('user', $data);
		return mysql_insert_id();
	}

	function _update($id, $data)
	{
		$this->db->where('id', $id);
		$this->db->update('user', $data);
	}


	function _delete($data)
	{
		$this->db->delete('user', $data);
		$this->db->query("commit");
	}
}

?>