<?php 
/**
 * Отображение данных
 * @author ashmits by 24.12.2012 17:48
 *
 */
class Reviews_Views
{
	
	private $CI = null;
	private $section = array();
	public $ids = array();
	public $page = 0;
	private $hash = null;
	private $hash_array = array();
	private $sections = array();
	private $content_types;
	
	function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->model(array('model_common','model_reviews'));
		$this->CI->load->library(array('my_users','unit_test'));
	}
	
	/**
	 * Список обзоров в админке
	 * @return unknown
	 */
	public function get_admin_reviews_list()
	{
		$reviews = $this->CI->model_reviews->get_reviews_list($this);
		return $reviews;
	}
	
	/**
	 * Условия для фильтра обзоров
	 * @return string
	 */
	public function get_reviews_list_conditions()
	{
		if (!$this->hash_array)
		{
			return "";
		}
		
	}
	
	/**
	 * Сортировка для обзоров
	 * @return string
	 */
	public function get_reviews_list_order()
	{
		
		if ($this->hash_array)
		{
			
			if (!empty($this->hash_array) and !empty($this->hash_array['sort_field']) and !empty($this->hash_array['sort_type']))
				return (string)$this->hash_array['sort_field'] . " " . (string)$this->hash_array['sort_type'];
				
		}
		
		return "date desc";
		
	}
	
	public function set_hash($hash)
	{
		$this->hash = (string)$hash;
		$this->hash_array = Common_Helper::hash_to_array($this->hash);
	}
	
	
	public function set_page($page)
	{
		$this->page = intval($page);
	}
	
	public function get_page()
	{
		return $this->page;
	}
	
	/**
	 * Список таблиц обзора
	 * @author ashmits by 15.01.13 12:30
	 * @param int $reviews_id
	 * @return array|NULL
	 */
	function get_tables_list_by_reviews($reviews_id, $status = null)
	{
		$where = array("reviews_id" => intval($reviews_id));
		if (isset($status))
		{
			$where = array_merge($where, array("table_status" => intval($status)));
		}
		
		$tables = $this->CI->model_reviews->get_reviews_tables_list($where);
		
		if (count($tables))
		{
			return $tables;
		}
		
		return null;
	}
	
	/**
	 * Выводим разделы сайта
	 * @author ashmits by 27.12.2012 10:58
	 * @return array|NULL
	 */
	function get_sections()
	{
		$sections = $this->CI->model_common->select("sections", null, null, "section_create_date desc");
		if (count($sections))
		{
			return $sections;
		}
		else
		{
			return null;
		}
	}
	
	/**
	 * Выводим дерево разделов
	 * @param unknown_type $parent_id
	 * @param unknown_type $level
	 * @return array
	 */
	function get_children_sections_by_parent($parent_id = 0, $level = 0)
	{
		/**
		 * Если массив разделов пуст, заполняем
		 */
		if (empty($this->sections))
		{
			$this->get_all_sections();
		}
		
		$sections = array();
		$i = 0;
		foreach ($this->sections as $key => $val)
		{
			if ($this->sections[$key]['section_parent_id'] == $parent_id)
			{
				$sections[$i] = $val;
				$sections[$i]['level'] = $level;
				$sections[$i]['children'] = $this->get_children_sections_by_parent($val['section_id'],($level+1));
				$i++;
			}
		}
		
		return $sections;
		
	}
	
	/**
	 * Выводим все разделы
	 */
	function get_all_sections()
	{
		$this->sections = $this->CI->model_common->select("sections");
	}
	
	function get_children_section_ids($section_id)
	{
		
		$sections = $this->CI->model_common->select("sections",array("section_parent_id" => intval($section_id)));
		
		if (count($sections))
		{
			foreach ($sections as $key=>$val)
			{
				$this->ids[] = $val['section_id'];
				$this->get_children_section_ids($sections[$key]['section_id']);
			}
		}
		
	}
	
	
	/**
	 * Формирует отображение списка обзоров
	 * @param unknown_type $page
	 * @return multitype:unknown string
	 */
	function get_reviews_list($page)
	{
		
		$reviews = $this->CI->model_common->select("reviews",null,null,"id desc", intval($page), PER_PAGE_REVIEWS);
		
		if (count($reviews) > 0)
		{
			foreach ($reviews as $key=>$val)
			{
				$user = $this->CI->model_common->select_one("users", array("user_id" => $val['users_user_id']));
				if (!empty($user))
				{
					$reviews[$key]['user_name'] = (string) $user['user_name'];
				}
			}
			
			$total_reviews = $this->CI->model_common->select_count("reviews");
			
			return array("reviews" => $reviews, "total_reviews" => $total_reviews);
			
		}
		
		return null;
		
	}
	
	/**
	 * Заголовки обзора
	 * @author ashmits by 28.12.2012 13:13
	 * @param int $reviews_id
	 * @return unknown|multitype:
	 */
	function get_headers_by_reviews($reviews_id)
	{
		
		$headers = $this->CI->model_common->select("reviews_headers", array("reviews_id" => intval($reviews_id)), null, "sort");
		$content_types = $this->CI->config->item('reviews_content_types');
		$this->content_types = $content_types;
		
		if (!empty($headers))
		{
			foreach ($headers as $key=>$val)
			{
				//переводим строку в массив для удобства работы в шаблонах
				if (strlen(trim($headers[$key]['structure'])) != '')
				{
					$headers[$key]['structure'] = explode(",", $headers[$key]['structure']);
					if (count($headers[$key]['structure']) > 0)
					{
						foreach($headers[$key]['structure'] as $k=>$v)
						{
							$headers[$key]['structure'][$k] = $content_types[$v];
						}
						//переносим в массив значение из конфига
						//$headers[$key]['structure'] = array_map(function($value) use ($content_types){ return $content_types[$value]; }, $headers[$key]['structure']);
					}
				}
			}
		}
		
		return $headers;
		
	}
	
	private function set_content_types($value)
	{
		return $this->content_types[$value];
	}
	
	/**
	 * Формирует структуру обзора для front
	 * @param int $reviews_id
	 * @return array
	 */
	function get_reviews_structure($reviews_id)
	{
		$headers = $this->get_headers_by_reviews($reviews_id);
		
		if ($headers)
		{
			foreach($headers as $key=>$val)
			{
				if (!empty($val['structure']))
				{
					$counter = 1;
					$header_id = intval($val['id']);
					$this->CI->smarty->assign('header_name', $val['name']);
					$this->CI->smarty->assign('header_id', $header_id);
					$this->CI->smarty->assign('header_sort', $val['sort']);
						
					foreach($val['structure'] as $key2=>$val2)
					{
						
						if (isset($val2['data']))
						{

								
							switch ($val2['data'])
							{
								case 'articles':
									$articles = $this->get_articles_by_reviews_headers($reviews_id, $header_id);
									if (count($articles) > 0)
									{
										$this->CI->smarty->assign('articles', $articles);
										$this->CI->smarty->assign('counter', $counter);
										$data = $this->CI->smarty->fetch($val2['template_list']);
										$headers[$key]['data'][] = $data;
										$counter += 1;
									}
								break;
								case 'cases':
									$cases = $this->get_cases_by_reviews_headers($reviews_id, $header_id);
									if (count($cases) > 0)
									{
										$this->CI->smarty->assign('cases', $cases);
										$this->CI->smarty->assign('counter', $counter);
										$data = $this->CI->smarty->fetch($val2['template_list']);
										$headers[$key]['data'][] = $data;
										$counter += 1;
									}
								break;
								case 'interviews':
									$interviews = $this->get_interviews_by_reviews_headers($reviews_id, $header_id);
									if (count($interviews) > 0)
									{
										$this->CI->smarty->assign('interviews', $interviews);
										$this->CI->smarty->assign('counter', $counter);
										$data = $this->CI->smarty->fetch($val2['template_list']);
										$headers[$key]['data'][] = $data;
										$counter += 1;
									}
								break;
								case 'tables':
									$tables = $this->get_tables_by_reviews_headers($reviews_id, $header_id);
									if (count($tables) > 0)
									{
										foreach($tables as $k=>$v)
										{
											$tables[$k]['structure'] = Common_Helper::table_configure_by_limit($v['structure'], $v['description']);
										}
										$this->CI->smarty->assign('tables', $tables);
										$this->CI->smarty->assign('counter', $counter);
										$data = $this->CI->smarty->fetch($val2['template_list']);
										$headers[$key]['data'][] = $data;
										$counter += 1;
									}
								break;
							}
						}
					}
				}
			}
		}
		
		return $headers;
		
	}
	
	
	/**
	 * Список интервью обзора
	 * @author ashmits by 28.12.2012 15:40
	 * @param int $reviews_id
	 * @param int $user_id - ID автора интервью
	 * @return array
	 */
	function get_reviews_interviews($reviews_id, $user_id = null, $status = null)
	{
		$where = array("reviews_id" => intval($reviews_id));
		if (!empty($user_id))
		{
			$where = array_merge($where, array("users_user_id" => $user_id));
		}
		
		if (isset($status))
		{
			$where = array_merge($where, array("interview_status" => intval($status)));
		}
		
		$interviews = $this->CI->model_reviews->get_reviews_interviews_list($where);
		if (count($interviews))
		{
			return $interviews;
		}
		else
		{
			return null;
		}
	}
	
	/**
	 * Список статей обзора
	 * @author ashmits by 28.12.2012 15:53
	 * @param int $reviews_id
	 * @param int $user_id
	 * @return array|NULL
	 */
	function get_reviews_articles($reviews_id, $user_id = null, $status = null)
	{
		$where = array("reviews_id" => intval($reviews_id));
		
		if (!empty($user_id))
		{
			$where = array_merge($where, array("users_user_id" => $user_id));
		}
		
		if (isset($status))
		{
			$where = array_merge($where, array("article_status" => intval($status)));
		}
		
		$articles = $this->CI->model_reviews->get_reviews_articles_list($where);
		
		if (count($articles))
		{
			return $articles;
		}
		else
		{
			return null;
		}
		
	}
	
	/**
	 * Статьи по обзору и заголовку
	 * @author ashmits by 15.01.2013 17:28
	 * @param int $reviews_id
	 * @param int $headers_id
	 * @param int $status
	 * @return array|NULL
	 */
	function get_articles_by_reviews_headers($reviews_id, $headers_id, $status = 1)
	{
		$where = array("reviews_id" => intval($reviews_id), "reviews_headers_id" => intval($headers_id), "article_status" => intval($status));
		$articles = $this->CI->model_common->select("reviews_articles", $where, null, "date desc");
		
		if (count($articles))
			return $articles;
		
		return null;
		
	}
	
	
	function get_interviews_by_reviews_headers($reviews_id, $headers_id, $status = 1)
	{
		$where = array("reviews_id" => intval($reviews_id), "reviews_headers_id" => intval($headers_id), "interview_status" => intval($status));
		$interviews = $this->CI->model_common->select("reviews_interviews", $where, null, "date desc");
	
		if (count($interviews))
			return $interviews;
	
		return null;
	
	}
	
	
	function get_cases_by_reviews_headers($reviews_id, $headers_id, $status = 1)
	{
		$where = array("reviews_id" => intval($reviews_id), "reviews_headers_id" => intval($headers_id), "case_status" => intval($status));
		$cases = $this->CI->model_common->select("reviews_cases", $where, null, "date desc");
	
		if (count($cases))
			return $cases;
	
		return null;
	
	}
	
	
	function get_tables_by_reviews_headers($reviews_id, $headers_id, $status = 1)
	{
		$where = array("reviews_id" => intval($reviews_id), "reviews_headers_id" => intval($headers_id), "table_status" => intval($status));
		$tables = $this->CI->model_common->select("reviews_tables", $where, null, "date desc");
	
		if (count($tables))
			return $tables;
	
		return null;
	
	}
	
	
	function get_reviews_cases($reviews_id, $user_id = null, $status = null)
	{
		
		$where = array("reviews_id" => intval($reviews_id));
		
		if (!empty($user_id))
		{
			$where = array_merge($where, array("users_user_id" => $user_id));
		}
		
		if (isset($status))
		{
			$where = array_merge($where, array("case_status" => intval($status)));
		}
		
		$cases = $this->CI->model_reviews->get_reviews_cases_list($where);
		
		if (count($cases))
		{
			return $cases;
		}
		else
		{
			return null;
		}
		
	}
	
}

?>