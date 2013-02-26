<?php 
/**
 * Модель для работы с обзорами
 * @author ashmits
 *
 */
final class Model_Reviews extends Model_Common
{
	
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Макс.значение сортировки для заголовокв глав обзора
	 * @param int $reviews_id
	 * @return NULL|int
	 */
	function get_max_sort_headers_by_reviews($reviews_id)
	{
		$this->db->select_max('sort');
		$this->db->where('reviews_id', intval($reviews_id));
		$result = $this->db->get('reviews_headers');
		
		if (!empty($result) and $result->num_rows() == 1)
			return $result->row()->sort;
		else
			return 0;
	}
	
	/**
	 * метод выводит все статьи обзора
	 * @param int $reviews_id
	 * @param int $status
	 * @return array
	 */
	function get_reviews_articles_by_review_id($reviews_id, $status = null)
	{
		
		$this->db->where("reviews_id", intval($reviews_id));
		
		if (!empty($status))
		{
			$this->db->where("article_status", intval($status));
		}
		
		$this->db->select('*')->from('reviews_articles');
		
		$result = $this->db->get();
		
		if ($result and $result->num_rows() > 0)
		{
			return $result->result_array();
		}
		
		return null;
		
	}

	/**
	 * метод выводит все интервью обзора
	 * @param unknown_type $reviews_id
	 * @param unknown_type $status
	 * @return NULL
	 */
	function get_reviews_interviews_by_review_id($reviews_id, $status = null)
	{
	
		$this->db->where("reviews_id", intval($reviews_id));
	
		if (!empty($status))
		{
			$this->db->where("interview_status", intval($status));
		}
	
		$this->db->select('*')->from('reviews_interviews');
	
		$result = $this->db->get();
	
		if ($result and $result->num_rows() > 0)
		{
			return $result->result_array();
		}
	
		return null;
	
	}

	/**
	 * метод выводит все кейсы обзора
	 * @param unknown_type $reviews_id
	 * @param unknown_type $status
	 * @return NULL
	 */
	function get_reviews_cases_by_review_id($reviews_id, $status = null)
	{
	
		$this->db->where("reviews_id", intval($reviews_id));
	
		if (!empty($status))
		{
			$this->db->where("case_status", intval($status));
		}
	
		$this->db->select('*')->from('reviews_cases');
	
		$result = $this->db->get();
	
		if ($result and $result->num_rows() > 0)
		{
			return $result->result_array();
		}
	
		return null;
	
	}
	
	
	
	
	/**
	 * Обзор по ID и доп.условиям
	 * @author ashmits by 29.01.2013 15:54
	 * @param int $reviews_id
	 * @param string $where
	 * @return array
	 */
	function get_reviews_by_id($reviews_id, $where=null)
	{
		if (!empty($where))
		{
			$this->db->where($where);
		}
		
		$this->db->select('reviews.*, users.user_name')->from('reviews')
		->join('users', 'reviews.users_user_id = users.user_id', 'left')->where("reviews.id", intval($reviews_id));
		
		$result = $this->db->get();
		
		if ($result and $result->num_rows() == 1)
		{
			return $result->row_array();
		}
		else
		{
			return null;
		}
		
	}

	
	function get_reviews_articles_by_id($articles_id, $where=null)
	{
		if (!empty($where))
		{
			$this->db->where($where);
		}
	
		$this->db->select('reviews_articles.*, users.user_name')->from('reviews_articles')
		->join('users', 'reviews_articles.users_user_id = users.user_id', 'left')->where("reviews_articles.id", intval($articles_id));
	
		$result = $this->db->get();
	
		if ($result and $result->num_rows() == 1)
		{
			return $result->row_array();
		}
		else
		{
			return null;
		}
	
	}

	
	function get_reviews_interview_by_id($interview_id, $where=null)
	{
		
		if (!empty($where))
		{
			$this->db->where($where);
		}
	
		$this->db->select('reviews_interviews.*, users.user_name')->from('reviews_interviews')
		->join('users', 'reviews_interviews.users_user_id = users.user_id', 'left')->where("reviews_interviews.id", intval($interview_id));
	
		$result = $this->db->get();
		
		if ($result and $result->num_rows() == 1)
		{
			return $result->row_array();
		}
		else
		{
			return null;
		}
	
	}

	
	function get_reviews_case_by_id($case_id, $where=null)
	{
		
		if (!empty($where))
		{
			$this->db->where($where);
		}
	
		$this->db->select('reviews_cases.*, users.user_name')->from('reviews_cases')
		->join('users', 'reviews_cases.users_user_id = users.user_id', 'left')->where("reviews_cases.id", intval($interview_id));
	
		$result = $this->db->get();
	
		if ($result and $result->num_rows() == 1)
		{
			return $result->row_array();
		}
		else
		{
			return null;
		}
	
	}
	
	
	
	/**
	 * Список обзоров в админке
	 * @param Reviews_Views $views вьюха обзоров
	 * @return array
	 */
	public function get_reviews_list(Reviews_Views $views)
	{
		
		$where = $views->get_reviews_list_conditions();
		$order = $views->get_reviews_list_order();

		$this->db->select('*')->from('reviews')
		->join('users','reviews.users_user_id=users.user_id','left');
		
		if ($where)
		{
			$this->db->where($where);
		}
		
		if ($order)
		{
			$this->db->orderby($order);
		}
		
		$this->db->limit(PER_PAGE_REVIEWS, $views->page);
		
		$result = $this->db->get();
		//echo $this->last_query();
		if ($result and $result->num_rows() > 0)
		{
			return array('reviews' => $result->result_array(), 'total_rows' => $this->get_reviews_total_rows($views));
		}
		
		return null;
		
	}
	
	/**
	 * Общее количество обзоров по условию для пейджинга
	 * @param Reviews_Views $views вьюха обзоров
	 * @return number
	 */
	private function get_reviews_total_rows(Reviews_Views $views)
	{
		$where = $views->get_reviews_list_conditions();
		$total_rows = $this->select_count("reviews", $where);
		return (int)$total_rows;
	}
	
	public function set_reviews_headers_sort($reviews_id, $sort=0)
	{
		$this->db->where("reviews_id", intval($reviews_id));
		$this->db->update("reviews_headers", array("sort" => intval($sort)));
	}
	
	public function get_reviews_articles_list($where = null)
	{
		
		if (!empty($where))
		{
			$this->db->where($where);
		}
		$this->db->select('*')->from('reviews_articles')
		->join('users','users_user_id = user_id', 'left')->order_by('date', 'desc');
		
		$result = $this->db->get();
		
		if ($result and $result->num_rows() > 0)
			return $result->result_array();
		else
			return null;
		
	}

	
	public function get_reviews_interviews_list($where = null)
	{
		
		if (!empty($where))
		{
			$this->db->where($where);
		}
		$this->db->select('*')->from('reviews_interviews')
		->join('users','users_user_id = user_id', 'left')->order_by('date', 'desc');
	
		$result = $this->db->get();
	
		if ($result and $result->num_rows() > 0)
			return $result->result_array();
		else
			return null;
	
	}
	

	public function get_reviews_cases_list($where = null)
	{
		
		if (!empty($where))
		{
			$this->db->where($where);
		}
		$this->db->select('*')->from('reviews_cases')
		->join('users','users_user_id = user_id', 'left')->order_by('date', 'desc');
	
		$result = $this->db->get();
	
		if ($result and $result->num_rows() > 0)
			return $result->result_array();
		else
			return null;
	
	}
	
	public function get_reviews_tables_list($where)
	{
		
		if (!empty($where))
		{
			$this->db->where($where);
		}
		$this->db->select('*')->from('reviews_tables')
		->join('users','users_user_id = user_id', 'left')->order_by('date', 'desc');
		
		$result = $this->db->get();
		
		if ($result and $result->num_rows() > 0)
			return $result->result_array();
		else
			return null;
		
	}
	
	
}

?>