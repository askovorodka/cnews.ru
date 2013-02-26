<?php
/**
 * Модель статей
 * @author ashmits
 *
 */
class Model_articles extends Model 
{

	function __construct()
	{
		parent::Model();
	}
	
	
	public function search_articles(Articles_Views $views)
	{
	
		if ($where = $views->get_articles_list_conditions())
		{
			$this->db->where( $where );
		}
	
		if ($order = $views->get_articles_list_order())
		{
			$this->db->order_by( $order );
		}
	
		$this->db->select('*')->from('articles');
		$this->get_search_articles_joins($views);
	
		$this->db->limit($views->page_limit, $views->current_page);
		$result = $this->db->get();
	
		if (!empty($result))
		{
			if ($result->num_rows() > 0)
			{
				return $result->result_array();
			}
		}
	
	
		return null;
	
	}

	
	
	public function search_tags_for_article($tag_name)
	{
		if (trim($tag_name) != "")
		{
			$this->db->select('tags_name')->from('tags');
			$this->db->join('articles_tags', 'tags.tags_id = articles_tags.tags_id', 'inner');
			$this->db->like('tags_name', (string)$tag_name, 'after');
			$result = $this->db->get();
		}
	
	
		if (!empty($result))
		{
			if ($result->num_rows() > 0)
			{
				return $result->result_array();
			}
		}
	
		return null;
	
	}
	
	
	public function search_articles_count(Articles_Views $views)
	{
	
		if ($where = $views->get_articles_list_conditions())
		{
			$this->db->where( $where );
		}
	
		$this->get_search_articles_joins($views);
	
		$this->db->from('articles');
		$result = $this->db->count_all_results();
	
		//результаты
		return $result;
	
	}
	
	
	/**
	 * описание соединений таблиц для поиска статей
	 * @param Articles_Views $views
	 * @author ashmits by 15.02.2013 11:50
	 */
	private function get_search_articles_joins(Articles_Views $views)
	{
	
		if (!empty($views->array['filter_tags']))
		{
				$this->db->join('articles_tags', 'articles.article_id = articles_tags.article_id', 'left');
				$this->db->join('tags', 'articles_tags.tags_id = tags.tags_id', 'left');
		}
				
		if (!empty($views->array['filter_sections'] ))
		{
				$this->db->join('articles_sections','articles.article_id = articles_sections.article_id', 'left');
		}
			
		$this->db->join('users', 'articles.users_user_id = users.user_id', 'left');	
			
	}
	
	
	public function clear_sections_by_article($article_id)
	{
		$this->db->where("article_id", intval($article_id));
		$this->db->delete("articles_sections");
	}
	
	public function clear_tags_by_article($article_id)
	{
		$this->db->where("article_id", intval($article_id));
		$this->db->delete("articles_tags");
	}
	
	/**
	 * Добавление связи раздел-статья
	 * @param unknown_type $data
	 */
	public function articles_sections_insert($data)
	{
		if (is_array($data))
		{
			$this->db->insert_batch('articles_sections', $data);
		}
	}
	
	
	/**
	 * Все разделы подключенные к статье
	 * @param int $article_id
	 * @return NULL
	 */
	public function get_articles_sections_by_article($article_id)
	{
		$this->db->select('article_id,section_id,is_main')->from('articles_sections')->where('article_id', intval($article_id));
		$result = $this->db->get();
	
		if (!empty($result))
		{
			if ($result->num_rows() > 0)
			{
				return $result->result_array();
			}
		}
	
		return null;
	
	}
	
	/**
	 * Все теги подключенные к статье
	 * @param int $article_id
	 * @return NULL
	 */
	public function get_articles_tags_by_article($article_id)
	{
	
		$this->db->select('tags.tags_name')->from('articles_tags')
		->join('tags', 'articles_tags.tags_id=tags.tags_id', 'left')
		->where('articles_tags.article_id', (int)$article_id);

		$result = $this->db->get();

		if (!empty($result))
		{
			if ($result->num_rows() > 0)
			{
				return $result->result_array();
			}
		}

		return null;

	}
	
	/**
	 * Проверка на существование связи тег-статья
	 * @param int $article_id
	 * @param int $tags_id
	 * @return NULL
	 */
	public function is_articles_tags_exists($article_id, $tags_id)
	{
		$this->db->select('*')->from('articles_tags')->where(array('article_id' => $article_id, "tags_id" => $tags_id))->limit(1);
		$result = $this->db->get();
		if ($result and $result->num_rows() == 1)
		{
			return $result->row_array();
		}
	
		return null;
	
	}
	
	/**
	 * Удаление статьи
	 * @param int $article_id
	 */
	public function delete_article_by_id($article_id)
	{
		$this->db->where("article_id", intval($article_id));
		$this->db->delete("articles");
	
		$this->delete_articles_sections_by_article_id(intval($article_id));
		$this->delete_articles_tags_by_article_id(intval($article_id));
	}
	
	/**
	 * Удаление все подключенных разделов  к статье
	 * @param unknown_type $article_id
	 */
	public function delete_articles_sections_by_article_id($article_id)
	{
		$this->db->where("article_id", intval($article_id));
		$this->db->delete("articles_sections");
	}
	
	
	/**
	 * Удаление тегов подключенных к статье
	 * @param unknown_type $article_id
	 */
	public function delete_articles_tags_by_article_id($article_id)
	{
		$this->db->where("article_id", intval($article_id));
		$this->db->delete("articles_tags");
	}
	
}
?>