<?php 
/**
 * Класс валидации данных
 * @author ashmits by 10.12.2012 16:33
 *
 */
class Validate
{
	private $CI = null;
	
	public function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->model(array('model_reviews','model_news'));
		$this->CI->load->helper('show_errors');
	}
	
	function set_url($url, $require = false, $message = null)
	{
		if ($require)
		{
			if (trim($url) == "")
				show_error($message);
		}
		
		if (trim($url) != "")
		{
			if (!My_Url_Helper::validate_url($url))
			{
				show_error("Невалидный урл");
			}
		}
		
		return (string)$url;
		
	}
	
	function set_image($image, $require = false, $message = null)
	{
		if ($require)
		{
			if (empty($image))
			{
				show_error($message);
			}
		}
		
		if (trim($image) != "")
		{
			
			$imageinfo = @getimagesize($image);
			if (!$imageinfo or empty($imageinfo['mime']))
			{
				show_error("Не валидная картинка");
			}
				
		}
		
		return (string)$image;
		
	}
	
	
	function set_active_user()
	{
		$CI =& get_instance();
		$CI->load->library('my_users');
		if (empty($CI->my_users->active_admin_user['user_id']))
		{
			show_error("Нет активного пользователя");
		}
		return (int) $CI->my_users->active_admin_user['user_id'];
	}
	
	/**
	 * Валидация текстовых данных
	 * @param значение $value
	 * @param unknown_type $require
	 * @param unknown_type $message
	 * @return unknown
	 */
	function set_text($value, $require = false, $message = null)
	{
		if ($require)
		{
			
			if (trim($value) == "")
			{
				show_error($message);
			}
		}
		//return trim(htmlspecialchars($value, ENT_QUOTES));
		return trim($value);
	}
	
	/**
	 * Проверка на целое число
	 * @param unknown_type $value
	 * @param unknown_type $require
	 * @param unknown_type $message
	 * @return int
	 */
	function set_int($value, $require = false, $message = NULL)
	{
		
		if ($require)
		{
			if (!preg_match("/^\d+$/", $value))
			{
				show_error($message);
			}
		}
		
		return intval($value);
		
	}
	
	/**
	 * Проверка на дату, если даты нет, генерируем новую
	 * @param unknown_type $value
	 * @param unknown_type $require
	 * @param unknown_type $message
	 * @return string
	 */
	function set_date($value=null, $require = false, $message = NULL)
	{
		if ($require)
		{
			if (trim($value) == "")
			{
				show_error("Не указана дата");
			}
		}
		
		if (empty($value))
		{
			$value = date("Y-m-d H:i:s");
		}
		else
		{
			$value = date("Y-m-d H:i:s", strtotime($value));
		}
		
		return ($value);
		
	}
	
	/**
	 * 
	 * @param unknown_type $value
	 * @param unknown_type $require
	 * @param unknown_type $message
	 * @return unknown
	 */
	function set_number($value, $require = false, $message = NULL)
	{
		if ($require)
		{
			if (!is_numeric($value))
			{
				show_error($message);
			}
		}
		return $value;
	}
	
	/**
	 * Валидация Email
	 * @param unknown_type $value
	 * @param unknown_type $require
	 * @param unknown_type $message
	 * @return unknown
	 */
	function set_email($value, $require = false, $message = NULL)
	{
		$CI =& get_instance();
		$CI->load->helper('email');
		if ($require)
		{
			if (trim($value) != "")
			{
				if (!valid_email($value))
				{
					show_error($message);
				}
			}
		}
		return ($value);
	}
	
	/**
	 * Транслитерация
	 * @param string $value
	 * @return string
	 */
	function set_translit($value)
	{
		$value = Common_Helper::rus_to_translit($value);
		
		return ($value);
		
	}
	
	/**
	 * Валидация пароля
	 * @param unknown_type $value
	 * @param unknown_type $required
	 * @param unknown_type $message
	 */
	function set_password($value, $require = false, $message = null)
	{

		if ($require)
		{
			if (trim($value) == "")
			{
				show_error($message);
			}
		}
		
		if (!Common_Helper::is_sha1($value))
		{
			$value = sha1($value);
		}
		
		return $value;
		
	}
	
	
	function set_admin_group($group_name, $require = false, $message = null)
	{
		
		$config = get_config();
		$all_admin_groups =  $config['admin_groups'];
		
		if ($require)
		{
			if (trim($group_name) == "")
			{
				show_error($message);
			}
		}
		
		if (!array_key_exists($group_name, $all_admin_groups))
		{
			show_error("Невалидный идентификатор группы пользователей");
		}
		
		return $group_name;
		
	}
	
	/**
	 * Валидация обзора по ID
	 * @param int $reviews_id
	 */
	function validate_reviews_by_id($reviews_id=null, $validate_current_user = true)
	{
		
		$reviews_id = $this->set_int($reviews_id, true, "Не указан ID обзора");
		
		$where = array();
		//добавление условия по выборке обзоров
		if ($validate_current_user)
		{
			$this->CI->load->library('my_users');
			$where_group = $this->CI->my_users->get_where_by_user_group();
			if (!empty($where_group))
			{
				$where = array_merge($where, $where_group);
			}
		}
		$reviews = $this->CI->model_reviews->get_reviews_by_id(intval($reviews_id), $where);
		
		if (empty($reviews))
		{
			Show_Errors::show_permission_error("Обзора с таким ID не существует или недостаточно прав на его просмотр и редактирование");
		}
		
		return $reviews;
		
	}
	
	
	/**
	 * валидация статьи
	 * @param int $articles_id
	 * @param bool $validate_current_user
	 * @return unknown
	 */
	function validate_articles_by_id($articles_id, $validate_current_user = true)
	{
		
		$articles_id = $this->set_int($articles_id, true, "Не указан ID статьи обзора");
		
		$where = array();
		
		//добавление условия по выборке обзоров
		if ($validate_current_user)
		{
			$this->CI->load->library('my_users');
			$where_group = $this->CI->my_users->get_where_by_user_group();
			if (!empty($where_group))
			{
				$where = array_merge($where, $where_group);
			}
		}
		
		$article = $this->CI->model_reviews->get_reviews_articles_by_id(intval($articles_id), $where);
		
		if (empty($article))
		{
			Show_Errors::show_permission_error("Статьи с таким ID не существует или недостаточно прав на его просмотр и редактирование");
		}
		
		return $article;
		
	}

	
	/**
	 * валидация интервью
	 * @param int $interview_id
	 * @param bool $validate_current_user
	 * @return unknown
	 */
	function validate_interview_by_id($interview_id, $validate_current_user = true)
	{
	
		$interview_id = $this->set_int($interview_id, true, "Не указан ID интервью");
		
		$where = array();
	
		//добавление условия по выборке обзоров
		if ($validate_current_user)
		{
			$this->CI->load->library('my_users');
			$where_group = $this->CI->my_users->get_where_by_user_group();
			if (!empty($where_group))
			{
				$where = array_merge($where, $where_group);
			}
		}
	
		$interview = $this->CI->model_reviews->get_reviews_interview_by_id($interview_id,$where);
	
		if (empty($interview))
		{
			Show_Errors::show_permission_error("Интервью с таким ID не существует или недостаточно прав на его просмотр и редактирование");
		}
	
		return $interview;
	
	}

	
	/**
	 * валидация таблиц
	 * @param int $interview_id
	 * @param bool $validate_current_user
	 * @return unknown
	 */
	function validate_table_by_id($table_id, $validate_current_user = true)
	{
	
		$table_id = $this->set_int($table_id, true, "Не указан ID таблицы");
		
		$where = array("id" => intval($table_id));
		
		//добавление условия по выборке обзоров
		if ($validate_current_user)
		{
			$this->CI->load->library('my_users');
			$where_group = $this->CI->my_users->get_where_by_user_group();
			if (!empty($where_group))
			{
				$where = array_merge($where, $where_group);
			}
		}
	
		$table = $this->CI->model_common->select_one('reviews_tables', $where);
	
		if (empty($table))
		{
			Show_Errors::show_permission_error("Таблицы с таким ID не существует или недостаточно прав на его просмотр и редактирование");
		}
	
		return $table;
	
	}

	/**
	 * валидация кейса
	 * @param int $case_id
	 * @param bool $validate_current_user
	 * @return array
	 */
	function validate_case_by_id($case_id, $validate_current_user = true)
	{
	
		$case_id = $this->set_int($case_id, true, "Не указан ID кейса");
	
		$where = array("id" => intval($case_id));
	
		//добавление условия по выборке обзоров
		if ($validate_current_user)
		{
			$this->CI->load->library('my_users');
			$where_group = $this->CI->my_users->get_where_by_user_group();
			if (!empty($where_group))
			{
				$where = array_merge($where, $where_group);
			}
		}
	
		$case = $this->CI->model_common->select_one('reviews_cases', $where);
	
		if (empty($case))
		{
			Show_Errors::show_permission_error("Кейса с таким ID не существует или недостаточно прав на его просмотр и редактирование");
		}
	
		return $case;
	
	}
	
	
	
	/**
	 * @author ashmits by 20.12.2012 10:45
	 * Валидация пользователя по ID
	 * @param int $user_id
	 * @return array
	 */
	function validate_user_by_id($user_id = null)
	{
		
		$user_id = $this->set_int($user_id, true, "Не указан ID пользователя");
		
		$user = $this->CI->model_common->select_one("users", array("user_id" => intval($user_id)));
		
		if (empty($user))
		{
			Show_Errors::show_permission_error("Пользователя с таким ID не существует");
		}
		
		return $user;
	}
	
	/**
	 * Валидация раздела сайта
	 * @author ashmits by 26.12.2012 17:33
	 * @param int $section_id
	 * @return unknown
	 */
	function validate_section_by_id($section_id =  null)
	{
		
		$section_id = $this->set_int($section_id, true, "Не указан ID раздела");
		
		$section = $this->CI->model_common->select_one("sections", array("section_id" => $section_id));
		
		if (empty($section))
		{
			Show_Errors::show_permission_error("Раздела с таким ID не существует");
		}
		
		return $section;
		
	}
	
	function validate_header_by_id($header_id)
	{
		$header_id = $this->set_int($header_id, true, "Не указан ID заголовка");
		$header = $this->CI->model_common->select_one("reviews_headers", array("id" => intval($header_id)));
		
		if (empty($header))
		{
			Show_Errors::show_permission_error("Заголовка с таким ID не существует");
		}
		
		return $header;
		
	}

	
	function validate_table_single_by_id($table_id, $validate_current_user = true)
	{
		
		$table_id = self::set_int($table_id, true, "Не указан ID таблицы");
		
		$where = array("table_id" => $table_id);
		if ($validate_current_user)
		{
			$this->CI->load->library('my_users');
			$where_group = $this->CI->my_users->get_where_by_user_group();
			if (!empty($where_group))
			{
				$where = array_merge($where, $where_group);
			}
		}
		
		$table = $this->CI->model_common->select_one("tables", $where);
		
		if (empty($table))
		{
			//show_error("Таблицы с таким ID не существует");
			Show_Errors::show_permission_error("Таблицы с таким ID не существует или недостаточно прав");
		}
		
		return $table;
		
	}
	
	
	function validate_news_by_id($news_id, $validate_current_user = true)
	{
		
		$news_id = $this->set_int($news_id, true, "Не указан ID новости");
		$this->CI->load->library('news_views');
		$this->CI->news_views->set_value_to_conditions('news_id', $news_id);
		
		if ($validate_current_user)
		{
			$this->CI->load->library('my_users');
			$where_group = $this->CI->my_users->get_where_by_user_group();
			if (!empty($where_group) and !empty($where_group['users_user_id']))
			{
				$this->CI->news_views->set_value_to_conditions('users_user_id', $where_group['users_user_id']);
			}
		}
		
		
		$news = $this->CI->model_news->search_news($this->CI->news_views);
		
		if (empty($news[0]))
		{
			Show_Errors::show_permission_error("Новости с таким ID не существует или недостаточно прав");
		}
		
		return $news[0];
	}
	
	
	function validate_article_by_id($article_id, $validate_current_user = true)
	{
	
		$article_id = $this->set_int($article_id, true, "Не указан ID статьи");
		$this->CI->load->library('articles_views');
		$this->CI->articles_views->set_value_to_conditions('article_id', $article_id);
	
		if ($validate_current_user)
		{
			$this->CI->load->library('my_users');
			$where_group = $this->CI->my_users->get_where_by_user_group();
			if (!empty($where_group) and !empty($where_group['users_user_id']))
			{
				$this->CI->articles_views->set_value_to_conditions('users_user_id', $where_group['users_user_id']);
			}
		}
	
	
		$article = $this->CI->model_articles->search_articles($this->CI->articles_views);
	
		if (empty($article[0]))
		{
			
			Show_Errors::show_permission_error("Статья с таким ID не существует или недостаточно прав");
		}
	
		return $article[0];
	}
	
	
}

?>