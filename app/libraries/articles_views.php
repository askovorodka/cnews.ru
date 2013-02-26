<?php 
/**
 * вьюха статей
 * @author ashmits by 15.02.2013 11:05
 *
 */
final class Articles_Views extends Articles_Collector
{
	
	public $page_limit = PER_PAGE_NEWS;
	public $current_page = 0;
	public $array = array();
	public $where = array();
	private $CI;
	
	function __construct()
	{
		parent::__construct();
		$this->CI =& get_instance();
	}
	
	/**
	 * список статей
	 * @param string $hash
	 * @param int $page
	 * @return array
	 */
	function get_articles_list($hash, $page=0)
	{
		
		$this->current_page = (int)$page;
		$this->array = Common_Helper::hash_to_array($hash);
		
		$articles = $this->CI->model_articles->search_articles($this);
		
		if (count($articles) > 0)
		{
			return array("articles" => $articles, "count" => $this->CI->model_articles->search_articles_count($this));
		}
		else
			return null;
		
	}
	
	/**
	 * условия для поиска статей
	 * @return string
	 */
	function get_articles_list_conditions()
	{
		
		$this->where = array();
		
		if (!empty($this->array['article_id']))
		{
			$this->where[] = sprintf("articles.article_id = '%d'", (int)$this->array['article_id']);
		}
				
			
		if (!empty($this->array['users_user_id']))
		{
			$this->where[] = sprintf("articles.users_user_id = '%d'", (int)$this->array['users_user_id']);
		}
				
			
		if (!empty($this->array['filter_sections']))
		{
			$this->where[] = sprintf("articles_sections.section_id = '%d'", (int)$this->array['filter_sections']);
		}

		
		if (!empty($this->array['date_start']))
		{
			$this->where[] = sprintf("articles.article_date >= '" . date("Y-m-d H:i:s", strtotime($this->array['date_start'])) .  "'");
		}
		
		
		if (!empty($this->array['date_end']))
		{
			$this->where[] = sprintf("articles.article_date <= '" . date("Y-m-d H:i:s", strtotime($this->array['date_end'])) . "'");
		}
		
		
		if (!empty($this->array['filter_tags']))
		{
			$this->where[] = sprintf("tags.tags_name = '%s'", htmlspecialchars($this->set_text($this->array['filter_tags']), ENT_QUOTES));
		}
		
		if (!empty($this->array['filter_sections']))
		{
			$this->where[] = sprintf("articles_sections.section_id = '%d'", (int)$this->array['filter_sections']);
		}
		
		if (isset($this->array['article_top']))
		{
			$this->where[] = sprintf("articles.article_top = '%d'", (int)$this->array['article_top']);
		}
		
		if (isset($this->array['article_is_advert']))
		{
			$this->where[] = sprintf("articles.article_is_advert = '%d'", (int)$this->array['article_is_advert']);
		}
		
		if (count($this->where) > 0)
		{
			return implode(" and ", $this->where);
		}
		
	}
	
	/**
	 * сортировка
	 * @return string
	 */
	function get_articles_list_order()
	{
		
		if (!empty($this->array))
			
			if (!empty($this->array['sort_field']))
				
				if (!empty($this->array['sort_type']))
					
					return (string)$this->array['sort_field'] . " " . (string)$this->array['sort_type'];
		
		return "article_date desc";
		
	}
	

	function set_value_to_conditions($name, $value)
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
	
	
	function get_article_sections($article_id)
	{
	
		$array = array();
		$items = $this->CI->model_articles->get_articles_sections_by_article((int)$article_id);
	
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
	
	
}

?>