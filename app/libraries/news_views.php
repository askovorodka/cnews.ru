<?php 
/**
 * Вьюха новостей
 * @author ashmits by 01.02.2013 11:10
 *
 */
class News_Views extends Validate
{
	
	private $where = array();
	public $page = 0;
	public $array = array();
	private $hash = null;
	private $CI;
	
	function __construct()
	{
		parent::__construct();
		$this->CI =& get_instance();
		$this->CI->load->library(array('pagination'));
	}
	
	/**
	 * Список новостей
	 * @param string $hash
	 * @param int $page
	 */
	public function get_news_list($hash=null, $page)
	{
		
		$this->array = Common_Helper::hash_to_array($hash);
		$this->page = intval($page);
		
		$news_list = $this->CI->model_news->search_news($this);
		
		if (count($news_list) > 0)
		{
			return array("news" => $news_list, "count" => $this->CI->model_news->search_news_count($this));
		}
		else
			return null;
		
	}
	
	/**
	 * Ищем новость по ID
	 * @author ashmits by 04.02.2013 11:50
	 * @param int $news_id
	 */
	public function get_news_by_id($news_id)
	{
		$this->array['news_id'] = intval($news_id);
		$news = $this->CI->model_news->get_news_list($this);
	}
	
	/**
	 * формирование строки сортировки
	 * @return string
	 * @author ashmits by 04.02.2013 15:12
	 */
	public function get_news_list_order()
	{
		
		if (!empty($this->array) and !empty($this->array['sort_field']) and !empty($this->array['sort_type']))
			return $this->array['sort_field'] . " " . $this->array['sort_type'];
		
		return "news_date desc";
		
	}
	
	/**
	 * Условия для выборки
	 * @return string where
	 * @author ashmits by 04.02.2013 15:11
	 */
	public function get_news_list_conditions()
	{
		$this->where = array();

		if (!empty($this->array['news_id']))
		{
			$this->where[] = sprintf("news_id = '%d'", (int)$this->array['news_id']);
		}

		if (!empty($this->array['users_user_id']))
		{
			$this->where[] = sprintf("users_user_id = '%d'", (int)$this->array['users_user_id']);
		}
		
		if (!empty($this->array['news_title']))
		{
			$this->where[] = sprintf("news_title = '%s'", (string)$this->array['news_title']);
		}

		if (!empty($this->array['news_status']))
		{
			$this->where[] = sprintf("news_status = '%d'", (int)$this->array['news_status']);
		}

		if (!empty($this->array['news_top']))
		{
			$this->where[] = sprintf("news_top = '%d'", (int)$this->array['news_top']);
		}
		
		if (!empty($this->array['not_news_id']))
		{
			$this->where[] = sprintf("news_id != '%d'", (int)$this->array['not_news_id']);
		}

		if (!empty($this->array['date_start']))
		{
			$this->where[] = "news_date >= '" . date("Y-m-d H:i:s",strtotime($this->array['date_start'])) . "'";
		}

		if (!empty($this->array['date_end']))
		{
			$this->where[] = "news_date <= '" . date("Y-m-d H:i:s",strtotime($this->array['date_end'])) . "'";
		}
		
		if (!empty($this->array['filter_sections']))
		{
			$this->where[] = sprintf("news_sections.section_id = '%d'", (int)$this->array['filter_sections']);
		}

		if (!empty($this->array['filter_tags']))
		{
			$this->where[] = sprintf("tags.tags_name = '%s'", htmlspecialchars($this->set_text($this->array['filter_tags']), ENT_QUOTES));
		}
		
		if (!empty($this->array['news_top']))
		{
			$this->where[] = sprintf("news.news_top = '%d'", (int)$this->array['news_top']);
		}
		
		if (!empty($this->array['news_is_advert']))
		{
			$this->where[] = sprintf("news.news_is_advert = '%d'", (int)$this->array['news_is_advert']);
		}
		
		if (count($this->where) > 0)
		{
			return implode(" and ", $this->where);
		}
		
		return "";
		
	}
	
	function get_news_sections($news_id)
	{
		
		$array = array();
		$items = $this->CI->model_news->get_news_sections_by_news((int)$news_id);
		
		if (empty($items))
			return null;
		
		foreach ($items as $item)
		{
			$array['sections'][] = $item['section_id'];
			if ($item['is_main'] == 1)
				$array['main'][] = $item['section_id'];
		}
		
		return $array;
		
	}
	
	/**
	 * динамическое добавление условий выборки
	 * @param string $name - ключ массива
	 * @param any $value - значение
	 * @author ashmits by 04.02.2010 15:15
	 */
	public function set_value_to_conditions($name, $value)
	{
		
		if (!array_key_exists($name, $this->array))
		{
			$this->array = array_merge($this->array, array($name=>$value));
		}
		else
		{
			$this->array[$name] = $value;
		}
		
	}
	
}

?>