<?php 
/**
 * Модель новостей
 * @author ashmits by 01.02.2013 11:40
 *
 */
class Model_News extends Model_Common
{
	
	public function __construct()
	{
		parent::__construct();
	}
	
	public function search_tags_for_news($tag_name)
	{
		if (trim($tag_name) != "")
		{
			$this->db->select('tags_name')->from('tags');
			$this->db->join('news_tags', 'tags.tags_id = news_tags.tags_id', 'inner');
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
	
	/**
	 * Поиск новостей по параметрам
	 * @param News_Views $views
	 * @return array
	 */
	public function search_news(News_Views $views)
	{
		
		if ($where = $views->get_news_list_conditions())
		{
			$this->db->where( $where );
		}
		
		if ($order = $views->get_news_list_order())
		{
			$this->db->order_by( $order );
		}
		
		$this->db->select('*')->from('news')->join('users','news.users_user_id = users.user_id','left');
		$this->get_search_news_joins($views);
		
		$this->db->limit(PER_PAGE_NEWS, $views->page);
		$result = $this->db->get();
		//echo $this->last_query();
		
		if (!empty($result))
		{
			if ($result->num_rows() > 0)
			{
				return $result->result_array();
			}
		}
		
		
		return null;
		
	}
	
	public function search_news_count(News_Views $views)
	{
		
		if ($where = $views->get_news_list_conditions())
		{
			$this->db->where( $where );
		}
		
		$this->get_search_news_joins($views);
		
		$this->db->from('news');
		$result = $this->db->count_all_results();
		
		//результаты
		return $result;
		
	}
	
	private function get_search_news_joins(News_Views $views)
	{
		
		if (!empty($views->array['filter_tags']))
		{
			$this->db->join('news_tags', 'news.news_id = news_tags.news_id', 'left');
			$this->db->join('tags', 'news_tags.tags_id = tags.tags_id', 'left');
		}
		
		if (!empty($views->array['filter_sections']))
		{
			$this->db->join('news_sections','news.news_id = news_sections.news_id', 'left');
		}
		
	}
	
	/**
	 * Удаляем вся связи Новость-Раздел по ID новости
	 * @param int $news_id
	 * @author ashmits by 05.02.2013 12:30
	 */
	public function clear_sections_by_news($news_id)
	{
		$this->db->where("news_id", intval($news_id));
		$this->db->delete("news_sections");
	}

	public function clear_tags_by_news($news_id)
	{
		$this->db->where("news_id", intval($news_id));
		$this->db->delete("news_tags");
	}
	
	public function news_sections_insert($data)
	{
		if (is_array($data))
		{
			$this->db->insert_batch('news_sections', $data);
		}
	}
	
	public function get_news_sections_by_news($news_id)
	{
		$this->db->select('news_id,section_id,is_main')->from('news_sections')->where('news_id', intval($news_id));
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
	
	public function get_news_tags_by_news($news_id)
	{
		
		$this->db->select('tags.tags_name')->from('news_tags')
		->join('tags', 'news_tags.tags_id=tags.tags_id', 'left')
		->where('news_tags.news_id', $news_id);
		
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
	
	public function get_tag_id_by_name($name)
	{
		$this->db->select('*')->from('tags')->where('tags_name', (string)$name)->limit(1);
		$result = $this->db->get();
		if ($result and $result->num_rows() == 1)
		{
			return $result->row()->tags_id;
		}
		
		return null;
	}
	
	public function is_news_tags_exists($news_id, $tags_id)
	{
		$this->db->select('*')->from('news_tags')->where(array('news_id' => $news_id, "tags_id" => $tags_id))->limit(1);
		$result = $this->db->get();
		if ($result and $result->num_rows() == 1)
		{
			return $result->row_array();
		}
		
		return null;
	}
	
	public function delete_news_by_id($news_id)
	{
		$this->db->where("news_id", intval($news_id));
		$this->db->delete("news");
		
		$this->delete_news_sections_by_news_id(intval($news_id));
		$this->delete_news_tags_by_news_id(intval($news_id));
	}
	
	
	public function delete_news_sections_by_news_id($news_id)
	{
		$this->db->where("news_id", intval($news_id));
		$this->db->delete("news_sections");
	}
	
	public function delete_news_tags_by_news_id($news_id)
	{
		$this->db->where("news_id", intval($news_id));
		$this->db->delete("news_tags");
	}
	
}

?>