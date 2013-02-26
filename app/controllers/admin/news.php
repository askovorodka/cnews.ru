<?php 
/**
 * Контроллер новостей админки
 * @author ashmits by 01.02.2013 11:00
 *
 */
class News extends Controller
{
	
	
	function __construct()
	{
		
		parent::Controller();
		
		$this->load->library(array('validate', 'news_views', 'news_collector','admin_menu','header_block','breadcrumbs_block','my_history','reviews_views'));
		$this->load->model(array('model_news','model_common'));
		$this->load->helper(array('my_url','my_entities'));
		//проверка авторизации пользователя
		if (!$this->my_users->get_active_admin_user())
		{
			My_Url_Helper::redirect(DOMAIN.'/admin/login/?from=' . My_Url_Helper::get_current_url());
		}
		
		$this->my_users->check_permission(array('admin', 'redactor','news_redactor', 'news_author', 'news_writer'));
		
		$this->admin_menu->set_active_section('news');
		$this->admin_menu->set();
		
		$this->header_block->set_js(array(JS_ROOT.'news.js'));
		
		//$this->output->enable_profiler(TRUE);
		
	}
	
	
	function index()
	{
		$this->news_list();
	}
	
	/**
	 * Список новостей
	 * @param unknown_type $hash
	 * @param unknown_type $page
	 */
	function news_list($hash = null, $page=0)
	{
		
		$this->header_block->set_title('Список новостей');
		
		$this->header_block->set();
		
		$this->breadcrumbs_block->add('Список новостей', DOMAIN.'/admin/news/');
		$this->breadcrumbs_block->set();
		
		if (empty($hash))
		{
			$hash = Common_Helper::array_to_hash(array(0));
		}
		
		$news = $this->news_views->get_news_list($hash, $page);
		
		if (count($news) > 0)
		{
			
			//пейджинг
			$p_config = $this->config->item('paging');
			$p_config['base_url'] = DOMAIN."/admin/news/news_list/{$hash}/";
			//$p_config['total_rows'] = $this->model_common->select_count("news", $this->news_views->get_news_list_conditions());
			$p_config['total_rows'] = $news['count'];
			$p_config['per_page'] = PER_PAGE_NEWS;
			$p_config['uri_segment'] = 5;
			
			$this->pagination->initialize($p_config);
			$paging = $this->pagination->create_links();
			$this->smarty->assign('paging', $paging);
			
		}
		
		$users = $this->my_users->get_users_list();
		$sections = $this->reviews_views->get_children_sections_by_parent();
		
		//print_r($this->news_views->array);
		
		$this->smarty->assign('sections', $sections);
		$this->smarty->assign('users', $users);
		$this->smarty->assign('news', $news['news']);
		$this->smarty->assign('hash', $hash);
		$this->smarty->assign('array', $this->news_views->array);
		
		$this->smarty->display('admin/news/news_list');
		
	}
	
	/**
	 * Добавление новости
	 * @author ashmits by 01.02.2013 16:21
	 */
	function news_add()
	{
		
		$this->header_block->set_title('Добавление новости');
		//$this->header_block->set_js(array(JS_ROOT.'bootstrap-colorpicker.js'));
		//$this->header_block->set_css(array(CSS_ROOT.'colorpicker.css'));
		$this->header_block->set();
		
		$this->breadcrumbs_block->add('Список новостей', DOMAIN.'/admin/news/');
		$this->breadcrumbs_block->add('Добавление новости', DOMAIN.'/admin/news/news_add/');
		$this->breadcrumbs_block->set();
		
		$sections = $this->reviews_views->get_children_sections_by_parent();
		
		$this->smarty->assign('sections', $sections);
		
		$this->smarty->display('admin/news/news_add');
		
	}
	
	/**
	 * Добавление новости в БД
	 * 
	 */
	function news_add_post()
	{
		
		$data = $this->news_collector->news_editadd();
		if (isset($data))
		{
			$news_id = $this->model_common->insert("news", $data);
			
			//если не автосохранение, то
			if (!$this->input->post('with_ajax'))
				$this->my_history->add_to_history('news','insert', $news_id, "Добавлена новая новость &laquo;" . $data['news_title'] . "&raquo;");
			
			
			//формируем новые связи из post
			$sections = $this->news_collector->news_sections_editadd((int)$news_id);
			if (count($sections) > 0)
			{
				foreach($sections as $section)
				{
					//добавляем связи в БД
					$this->model_common->insert("news_sections", $section);
				}
			}
			
			//добавляем теги
			$tags = $this->news_collector->news_tags_editadd($news_id);
			if (count($tags))
			{
				foreach ($tags as $tag)
				{
					//если такой связи нету тег-новость, добавляем
					if (!$this->model_news->is_news_tags_exists($tag['news_id'], $tag['tags_id']))
					{
						//добавляем связь тег-новость
						$this->model_common->insert("news_tags", $tag);
					}
				}
			}
			
			
		}
		
		//если идет аякс сохранение, то выдаем ID новости
		if ($this->input->post('with_ajax'))
		{
			print json_encode($news_id); exit();
		}
		else
		{
			My_Url_Helper::redirect(DOMAIN.'/admin/news/');
		}
		
	}
	
	
	function news_edit($news_id)
	{
		
		$news = $this->validate->validate_news_by_id($news_id);
		
		$this->header_block->set_title('Редактирование новости: ' . $news['news_title']);
		
		//$this->header_block->set_js(array(JS_ROOT.'ExtJS/ext-all.js'));
		//$this->header_block->set_css(array(JS_ROOT.'ExtJS/resources/css/ext-all.css'));
		
		$this->header_block->set();
		
		$this->breadcrumbs_block->add('Список новостей', DOMAIN.'/admin/news/');
		$this->breadcrumbs_block->add('Редактирование новости', DOMAIN.'/admin/news/news_edit/'.$news_id);
		$this->breadcrumbs_block->set();
		
		$sections = $this->reviews_views->get_children_sections_by_parent();
		$news_sections = $this->news_views->get_news_sections($news_id);
		$news_tags = $this->model_news->get_news_tags_by_news($news_id);
		
		//$news_tags[0]['tags_name'] = stripslashes($news_tags[0]['tags_name']);
		
		$entities = My_Entities_Helper::get_entities_by_entity_in($news_id, 'news');
		$this->smarty->assign('entities', $entities);
		
		$this->smarty->assign('sections', $sections);
		$this->smarty->assign('news_sections', $news_sections);
		$this->smarty->assign('news_tags', $news_tags);
		$this->smarty->assign('news', $news);
		
		$this->smarty->display('admin/news/news_add');
		
	}
	
	/**
	 * Редактирование новости, заносим данные в БД
	 * @param int $news_id
	 * @author ashmits by 04.02.2013 17:05
	 */
	function news_edit_post($news_id)
	{
		
		//валедируем новость
		$news = $this->validate->validate_news_by_id($news_id);
		//достаем данные из POST
		$data = $this->news_collector->news_editadd($news);
		
		//заносим данные в БД
		if (!empty($data))
		{
			$this->model_common->update("news", $data, array("news_id" => intval($news_id)));
			
			if (!$this->input->post('with_ajax'))
			{
				$this->my_history->add_to_history('news','update', $news_id, "Отредактирована новость &laquo;" . $data['news_title'] . "&raquo;");
			}
			
			//сохраняем подключенные модули {table}
			My_Entities_Helper::set_entities($data['news_text'], $news_id, 'news');
				
		}
		
		//чистим предыдущие связи
		$this->model_news->clear_sections_by_news((int)$news_id);
		//формируем новые связи из post
		$sections = $this->news_collector->news_sections_editadd((int)$news_id);
		if (count($sections) > 0)
		{
			foreach($sections as $section)
			{
				//добавляем связи в БД
				$this->model_common->insert("news_sections", $section);
			}
		}
		
		//чистим теги новости
		$this->model_news->clear_tags_by_news((int)$news_id);
		$tags = $this->news_collector->news_tags_editadd($news_id);
		if (count($tags))
		{
			foreach ($tags as $tag)
			{
				//если такой связи нету тег-новость, добавляем
				if (!$this->model_news->is_news_tags_exists($tag['news_id'], $tag['tags_id']))
				{
					//добавляем связь тег-новость
					$this->model_common->insert("news_tags", $tag);
				}
				//$this->model_common->update("tags", array("tags_count" => "tags_count+1"), array("tags_id" => $tag['tags_id']));
			}
		}
		
		if ($this->input->post('with_ajax'))
		{
			print json_encode($news_id); exit();
		}
		else
		{
			My_Url_Helper::redirect(DOMAIN.'/admin/news/');
		}
		
	}
	
	/**
	 * Удаление новости по ID
	 * @param int $news_id
	 * @author ashmits by 06.02.2013 15:04
	 */
	function news_delete($news_id)
	{
		
		$news = $this->validate->validate_news_by_id($news_id);
		
		$this->model_news->delete_news_by_id($news_id);
		
		My_Url_Helper::redirect(DOMAIN.'/admin/news/');
		
	}
	
}

?>