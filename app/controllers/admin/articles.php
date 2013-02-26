<?php 

/**
 * Статьи
 * @author ashmits by 14.02.2013 16:00
 *
 */
class Articles extends Controller
{
	
	function __construct()
	{
		
		parent::Controller();
		$this->load->library(array('validate', 'articles_collector', 'articles_views','admin_menu','header_block','breadcrumbs_block','my_history','reviews_views','pagination'));
		$this->load->model(array('model_news','model_common','model_articles'));
		$this->load->helper(array('my_url','my_entities'));
		//проверка авторизации пользователя
		if (!$this->my_users->get_active_admin_user())
		{
			My_Url_Helper::redirect(DOMAIN.'/admin/login/?from=' . My_Url_Helper::get_current_url());
		}
		
		$this->my_users->check_permission(array('admin', 'redactor','news_redactor', 'articles_author', 'articles_writer'));
		
		$this->admin_menu->set_active_section('articles');
		$this->admin_menu->set();
		
		$this->header_block->set_js(array(JS_ROOT.'news.js'));
		
		//$this->output->enable_profiler(TRUE);
		
	}
	
	
	function index()
	{
		
		
		$this->articles_list();
	}
	
	
	/**
	 * Список статей обзора
	 * @param String $hash
	 * @param Int $page
	 */
	function articles_list($hash = null, $page = 0)
	{
		
		$this->header_block->set_title('Список статей');
		$this->header_block->set();
		$this->breadcrumbs_block->add('Список статей', DOMAIN.'/admin/articles/');
		$this->breadcrumbs_block->set();
		
		
		if (empty($hash))
		{
			$hash = Common_Helper::array_to_hash(array(0));
		}
		
		$articles = $this->articles_views->get_articles_list((string)$hash, intval($page));
		
		if (count($articles) > 0)
		{
				
			//пейджинг
			$p_config = $this->config->item('paging');
			$p_config['base_url'] = DOMAIN."/admin/articles/articles_list/{$hash}/";
			$p_config['total_rows'] = $articles['count'];
			$p_config['per_page'] = $this->articles_views->page_limit;
			$p_config['uri_segment'] = 5;
				
			$this->pagination->initialize($p_config);
			$paging = $this->pagination->create_links();
			$this->smarty->assign('paging', $paging);
				
		}
		
		//данные для фильтра
		$users = $this->my_users->get_users_list();
		$sections = $this->reviews_views->get_children_sections_by_parent();
		
		
		$this->smarty->assign('sections', $sections);
		$this->smarty->assign('users', $users);
		$this->smarty->assign('articles', $articles['articles']);
		$this->smarty->assign('hash', $hash);
		$this->smarty->assign('array', $this->articles_views->array);
		
		$this->smarty->display('admin/articles/articles_list');
		
		
	}
	
	/**
	 * Добавление статьи
	 */
	function article_add()
	{
		
		$this->header_block->set_title('Добавление статьи');
		$this->header_block->set();
		
		$this->breadcrumbs_block->add('Список статей', DOMAIN.'/admin/articles/');
		$this->breadcrumbs_block->add('Добавление статьи', DOMAIN.'/admin/articles/article_add/');
		$this->breadcrumbs_block->set();
		
		$sections = $this->reviews_views->get_children_sections_by_parent();
		
		$this->smarty->assign('sections', $sections);
		
		$this->smarty->display('admin/articles/article_add');
		
	}
	
	
	/**
	 * Сохранение добавляемой статьи
	 */
	function article_add_post()
	{

		$data = $this->articles_collector->article_editadd();
		
		if (isset($data))
		{
			$article_id = $this->model_common->insert("articles", $data);
				
			//если не автосохранение, то
			if (!$this->input->post('with_ajax'))
				$this->my_history->add_to_history('articles','insert', $article_id, "Добавлена новая статья &laquo;" . $data['article_title'] . "&raquo;");
				
				
			//формируем новые связи из post
			$sections = $this->articles_collector->articles_sections_editadd((int)$article_id);
			if (count($sections) > 0)
			{
				foreach($sections as $section)
				{
					//добавляем связи в БД
					$this->model_common->insert("articles_sections", $section);
				}
			}
				
			//добавляем теги
			$tags = $this->articles_collector->articles_tags_editadd($article_id);
			if (count($tags))
			{
				foreach ($tags as $tag)
				{
					//если такой связи нету тег-новость, добавляем
					if (!$this->model_articles->is_articles_tags_exists($tag['article_id'], $tag['tags_id']))
					{
						//добавляем связь тег-новость
						$this->model_common->insert("articles_tags", $tag);
					}
				}
			}

		}
		
		//если идет аякс сохранение, то выдаем ID новости
		if ($this->input->post('with_ajax'))
		{
			print json_encode($article_id); exit();
		}
		else
		{
			My_Url_Helper::redirect(DOMAIN.'/admin/articles/');
		}
		
		
	}
	
	
	/**
	 * форма редактирования статьи
	 * @param int $article_id
	 */
	function article_edit($article_id)
	{
	
		$article = $this->validate->validate_article_by_id($article_id);
	
		$this->header_block->set_title('Редактирование статьи: ' . $article['article_title']);
	
		$this->header_block->set();
	
		$this->breadcrumbs_block->add('Список статей', DOMAIN.'/admin/articles/');
		$this->breadcrumbs_block->add('Редактирование статей', DOMAIN.'/admin/articles/article_edit/' . (int)$article_id);
		$this->breadcrumbs_block->set();
	
		$sections = $this->reviews_views->get_children_sections_by_parent();
		$article_sections = $this->articles_views->get_article_sections($article_id);
		
		$article_tags = $this->model_articles->get_articles_tags_by_article($article_id);
	
		$entities = My_Entities_Helper::get_entities_by_entity_in($article_id, 'articles');
		$this->smarty->assign('entities', $entities);
	
		$this->smarty->assign('sections', $sections);
		$this->smarty->assign('article_sections', $article_sections);
		$this->smarty->assign('article_tags', $article_tags);
		$this->smarty->assign('article', $article);
	
		$this->smarty->display('admin/articles/article_add');
	
	}
	
	
	/**
	 * сохранение отредактированной новости
	 * @param int $news_id
	 */
	function article_edit_post($article_id)
	{
	
		//валедируем новость
		$article = $this->validate->validate_article_by_id($article_id);
		//достаем данные из POST
		$data = $this->articles_collector->article_editadd($article);
	
		//заносим данные в БД
		if (!empty($data))
		{
			$this->model_common->update("articles", $data, array("article_id" => intval($article_id)));
				
			if (!$this->input->post('with_ajax'))
			{
				$this->my_history->add_to_history('articles','update', $article_id, "Отредактирована статья &laquo;" . $data['article_title'] . "&raquo;");
			}
				
			//сохраняем подключенные модули {table}
			My_Entities_Helper::set_entities($data['article_text'], $article_id, 'articles');
	
		}
	
		//чистим предыдущие связи
		$this->model_articles->clear_sections_by_article((int)$article_id);
		//формируем новые связи из post
		$sections = $this->articles_collector->articles_sections_editadd((int)$article_id);
		if (count($sections) > 0)
		{
			foreach($sections as $section)
			{
				//добавляем связи в БД
				$this->model_common->insert("articles_sections", $section);
			}
		}
	
		//чистим теги новости
		$this->model_articles->clear_tags_by_article((int)$article_id);
		$tags = $this->articles_collector->articles_tags_editadd($article_id);
		if (count($tags))
		{
			foreach ($tags as $tag)
			{
				//если такой связи нету тег-новость, добавляем
				if (!$this->model_articles->is_articles_tags_exists($tag['article_id'], $tag['tags_id']))
				{
					//добавляем связь тег-новость
					$this->model_common->insert("articles_tags", $tag);
				}
			}
		}
	
		if ($this->input->post('with_ajax'))
		{
			print json_encode($article_id); exit();
		}
		else
		{
			My_Url_Helper::redirect(DOMAIN.'/admin/articles/');
		}
	
	}
	
	
	function article_delete($article_id)
	{
		$article = $this->validate->validate_article_by_id($article_id);
		$this->model_articles->delete_article_by_id($article_id);
		My_Url_Helper::redirect(DOMAIN.'/admin/articles/');
	
	}
	
	
	
}

?>