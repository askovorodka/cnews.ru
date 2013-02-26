<?php 
/**
 * класс сборщик для статей
 * @author ashmits by 15.02.2013 12:30
 *
 */
class Articles_Collector extends Validate
{
	private $data = array();
	private $CI;

	function __construct()
	{
		parent::__construct();
		$this->CI =& get_instance();
	}
	
	/**
	 * сбор поста для добавления новости
	 * @param unknown_type $article
	 */
	public function article_editadd($article = null)
	{
		
		$this->article_date = $this->set_date($this->article_date, true, "Не указана дата статьи");
		
		if (empty($article['article_insert_date']))
		{
			$this->article_insert_date = $this->set_date();
		}
		
		if (empty($article['users_user_id']))
		{
			$this->users_user_id = $this->set_active_user();
		}
		else
		{
			$this->users_user_id = $article['users_user_id'];
		}
		
		
		$this->article_color = $this->set_text($this->article_color, true, "Не указан цвет статьи");
		$this->article_top = $this->set_int($this->article_top);
		$this->article_is_zoom = $this->set_int($this->article_is_zoom);
		$this->article_is_advert = $this->set_int($this->article_is_advert);
		$this->article_publication = $this->set_int($this->article_publication);

		$this->article_title = $this->set_text( ($this->with_ajax == 1) ? iconv('utf-8', 'windows-1251', $this->article_title) : $this->article_title, true, "Не указан заголовок статьи");

		$this->article_translit = $this->set_translit(($this->with_ajax == 1) ? iconv('utf-8', 'windows-1251', $this->article_title) : $this->article_title);
		$this->article_annonce = $this->set_text(($this->with_ajax == 1) ? iconv('utf-8', 'windows-1251', $this->article_annonce) : $this->article_annonce, true, "Введите анонс статьи");
		$this->article_tv_title = $this->set_text(($this->with_ajax == 1) ? iconv('utf-8', 'windows-1251', $this->article_tv_title) : $this->article_tv_title);
		$this->article_text = $this->set_text(($this->with_ajax == 1) ? iconv('utf-8', 'windows-1251', $this->article_text) : $this->article_text, true, "Введите текст статьи");
		$this->article_comment = $this->set_int($this->article_comment);
		$this->article_image_100x100 = $this->set_image($this->article_image_100x100);
		$this->article_image_280x120 = $this->set_image($this->article_image_280x120);
		$this->article_image_source = $this->set_text(($this->with_ajax == 1) ? iconv('utf-8', 'windows-1251', $this->article_image_source) : $this->article_image_source);
		$this->article_status = $this->set_int(($this->article_status == 1) ? 1 : 0);

		return $this->data;

	}

	/**
	 * отмеченные разделы статьи
	 * @param int $article_id
	 * @return array
	 */
	function articles_sections_editadd($article_id)
	{
		if ($this->sections)
		{
			global $main, $article;
			$article = $article_id;
			
			$main = (int)$this->section_main;
	
			$data = array_map(create_function('$section_id', '
				global $main, $article;
				if ($main == $section_id)
					return array("section_id" => $section_id, "article_id" => $article, "is_main" => 1);
				else
					return array("section_id" => $section_id, "article_id" => $article);
					
					'), array_keys($this->sections));
			
			/*$data = array_map(function($section_id) use ($article_id, $main)
			{
				if ($main == $section_id)
					return array("section_id" => $section_id, "article_id" => $article_id, "is_main" => 1);
				else
					return array("section_id" => $section_id, "article_id" => $article_id);
					
			}, array_keys($this->sections));*/
				
			return $data;
		}
	}
	
	/**
	 * теги статьи
	 * @param int $article_id
	 * @return array
	 */
	function articles_tags_editadd($article_id)
	{
		if ($tags = $this->article_tags)
		{
			if ($this->with_ajax)
			{
				$tags = iconv('utf-8', 'windows-1251', $tags);
			}
			$array = explode(",", $tags);

			/*$data = array_map(function($tags_name) use ($article_id)
			{
				$CI =& get_instance();
				$tags_name = $CI->validate->set_text($tags_name);
				$tags_name = htmlspecialchars($tags_name, ENT_QUOTES);
				//если тега нет в базе, добавляем
				//$tags_name = stripslashes($tags_name);
				if (!($tags_id = $CI->model_news->get_tag_id_by_name($tags_name)))
				{
					$tags_id = $CI->model_common->insert("tags", array("tags_name" => $tags_name));
				}
				return array("tags_id" => $tags_id, "article_id" => $article_id);
			}, $array);*/

			foreach ($array as $k=>$v)
			{
				$tags_name = $this->CI->validate->set_text($v);
				$tags_name = htmlspecialchars($tags_name, ENT_QUOTES);
				if (!($tags_id = $this->CI->model_news->get_tag_id_by_name($tags_name)))
				{
					$tags_id = $this->CI->model_common->insert("tags", array("tags_name" => $tags_name));
				}
				$data[] = array("tags_id" => $tags_id, "article_id" => $article_id);
			}
			
			return $data;
		}
	
		return null;
	}
	
	
	
	//сеттер и геттер
	function __set($name, $value)
	{
		if (isset($this->data[$name]))
			$this->data[$name] = $value;
		else
			$this->data = array_merge($this->data, array($name => $value));
	}
	
	
	function __get($name)
	{
		return $this->CI->input->post($name);
	}
}

?>