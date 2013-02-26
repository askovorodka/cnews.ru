<?php 
/**
 * Класс сборщик новостей 
 * @author ashmits by 01.02.2013 11:00
 *
 */
class News_Collector extends Validate
{
	
	private $data = array();
	private $CI = null;
	private $views;
	
	function __construct()
	{
		parent::__construct();
		$this->CI =& get_instance();
		$this->views = $this->CI->news_views;
	}
	
	/**
	 * 
	 * @param unknown_type $news
	 */
	public function news_editadd($news = null)
	{
		
		$this->news_date = $this->set_date($this->news_date, true, "Не указана дата новости");
		
		if (empty($news['news_insert_date']))
		{
			$this->news_insert_date = $this->set_date();
		}
		
		if (empty($news['users_user_id']))
		{
			$this->users_user_id = $this->set_active_user();
		}
		else 
		{
			$this->users_user_id = $news['users_user_id'];
		}
		
		$this->news_color = $this->set_text($this->news_color, true, "Не указан цвет новости");
		$this->news_top = $this->set_int($this->news_top);
		$this->news_is_zoom = $this->set_int($this->news_is_zoom);
		$this->news_is_advert = $this->set_int($this->news_is_advert);
		$this->news_publication = $this->set_int($this->news_publication);
		
		$this->news_title = $this->set_text( ($this->with_ajax == 1) ? iconv('utf-8', 'windows-1251', $this->news_title) : $this->news_title, true, "Не указан заголовок новости");
		
		$this->news_translit = $this->set_translit(($this->with_ajax == 1) ? iconv('utf-8', 'windows-1251', $this->news_title) : $this->news_title);
		$this->news_annonce = $this->set_text(($this->with_ajax == 1) ? iconv('utf-8', 'windows-1251', $this->news_annonce) : $this->news_annonce, true, "Введите анонс новости");
		$this->news_tv_title = $this->set_text(($this->with_ajax == 1) ? iconv('utf-8', 'windows-1251', $this->news_tv_title) : $this->news_tv_title);
		$this->news_text = $this->set_text(($this->with_ajax == 1) ? iconv('utf-8', 'windows-1251', $this->news_text) : $this->news_text, true, "Введите текст новости");
		$this->news_comment = $this->set_int($this->news_comment);
		$this->news_image_100x100 = $this->set_image($this->news_image_100x100);
		$this->news_image_280x120 = $this->set_image($this->news_image_280x120);
		$this->news_image_source = $this->set_text(($this->with_ajax == 1) ? iconv('utf-8', 'windows-1251', $this->news_image_source) : $this->news_image_source);
		$this->news_status = $this->set_int(($this->news_status == 1) ? 1 : 0);
		
		return $this->data;
		
	}
	
	/**
	 * Собираем post данные для сохранения новости
	 * @param int $news_id
	 * @return array
	 */
	function news_sections_editadd($news_id)
	{
		if ($this->sections)
		{
			$data = array();
			$main = (int)$this->section_main;

			/*$data = array_map(function($section_id) use ($news_id, $main)
			{
				if ($main == $section_id)
					return array("section_id" => $section_id, "news_id" => $news_id, "is_main" => 1);
				else
					return array("section_id" => $section_id, "news_id" => $news_id);
				 
			}, array_keys($this->sections));*/
			
			foreach (array_keys($this->sections) as $k=>$v)
			{
				if ($v == $main)
					$data[] = array("section_id" => $v, "news_id" => $news_id, "is_main" => 1);
				else
					$data[] = array("section_id" => $v, "news_id" => $news_id);
			}
			
			return $data;
		}
	}
	
	
	function news_tags_editadd($news_id)
	{
		if ($tags = $this->news_tags)
		{
			if ($this->with_ajax)
			{
				$tags = iconv('utf-8', 'windows-1251', $tags);
			}
			$array = explode(",", $tags);
			
			foreach ($array as $k=>$v)
			{
				$tags_name = $this->CI->validate->set_text($v);
				$tags_name = htmlspecialchars($tags_name, ENT_QUOTES);
				
				if (!($tags_id = $this->CI->model_news->get_tag_id_by_name($tags_name)))
				{
					$tags_id = $this->CI->model_news->insert("tags", array("tags_name" => $tags_name));
				}
				
				$data[] = array("tags_id" => $tags_id, "news_id" => $news_id);
			}
			
			/* на ральфе не пашут анонимные функции, поэтому их коментим :(
			$data = array_map(function($tags_name) use ($news_id)
			{
				$CI =& get_instance();
				$tags_name = $CI->validate->set_text($tags_name);
				$tags_name = htmlspecialchars($tags_name, ENT_QUOTES);
				//если тега нет в базе, добавляем
				//$tags_name = stripslashes($tags_name);
				if (!($tags_id = $CI->model_news->get_tag_id_by_name($tags_name)))
				{
					 $tags_id = $CI->model_news->insert("tags", array("tags_name" => $tags_name));
				}
				
				return array("tags_id" => $tags_id, "news_id" => $news_id);
				
			}, $array);
			*/
			
			return $data;
		}
		
		return null;
	}
	
	/**
	 * сеттер, добавляет массив для БД
	 * @param unknown_type $name
	 * @param unknown_type $value
	 */
	function __set($name, $value)
	{
		if (isset($this->data[$name]))
		{
			$this->data[$name] = $value;
		}
		else
		{
			$this->data = array_merge($this->data, array($name => $value));
		}
	}
	
	/**
	 * геттер 
	 * @param unknown_type $name
	 */
	function __get($name)
	{
		return $this->CI->input->post($name);
	}
	
}

?>