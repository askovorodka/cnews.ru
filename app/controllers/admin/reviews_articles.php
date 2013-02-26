<?php 
//error_reporting(E_ALL);
//ini_set('display_errors','On');

//require_once APPPATH . 'controllers/starter.php';
/**
 * Контролеер статей обзоров
 * @author ashmits by 07.12.2012 17:04
 *
 */
class Reviews_Articles extends Controller
{
	function __construct()
	{
		
		parent::Controller();
		$this->load->library(array('admin_menu','header_block', 'validate', 'breadcrumbs_block','reviews_collector','my_users', 'my_history', 'reviews_views'));
		$this->load->helper(array('my_auth','my_url', 'my_entities', 'image'));
		$this->load->model(array('model_common'));
		
		//проверка авторизации в админке
		if (!$this->my_users->get_active_admin_user())
		{
			My_Url_Helper::redirect(DOMAIN.'/admin/login/?from=' . My_Url_Helper::get_current_url());
		}
		
		$this->my_users->check_permission(array('admin','redactor','reviews_author','reviews_writer','reviews_redactor'));
		
		//активное левое меню Обзоры
		$this->admin_menu->set_active_section('reviews');
		//передаем меню в шаблон
		$this->admin_menu->set();
		
		//$this->output->enable_profiler(TRUE);
	}
	
	/**
	 * Страница Статей обзора
	 * @author ashmits by 07.12.2012 17:20
	 * @param int $reviews_id
	 */
	function index($reviews_id = null)
	{
		//валидация обзора
		$reviews = $this->validate->validate_reviews_by_id($reviews_id,false);
		//статьи обзора
		$articles = $this->reviews_views->get_reviews_articles($reviews_id);
		
		
		//блоки
		$this->header_block->set_title('Обзоры : Статьи обзора');
		//шапка
		$this->header_block->set();
		
		//хлебные крошки
		$this->breadcrumbs_block->add("Список обзоров", DOMAIN.'/admin/reviews/');
		$this->breadcrumbs_block->add($reviews['name'], DOMAIN.'/admin/reviews/reviews_single/' . $reviews_id .'/');
		$this->breadcrumbs_block->add("Список статей обзора", null);
		$this->breadcrumbs_block->set();
		
		
		if (!empty($reviews))
		{
			$this->smarty->assign('reviews', $reviews);
		}
		
		if (!empty($articles))
		{
			$this->smarty->assign('articles', $articles);
		}
		
		$this->smarty->display('admin/reviews/reviews_articles');
	}
	
	
	function preview($article_id = null)
	{
		
		$article = $this->validate->validate_articles_by_id((int)$article_id, false);
		
		$reviews = $this->validate->validate_reviews_by_id((int)$article['reviews_id'], false);
		
		$article['text'] = Common_Helper::get_tables_by_tags((string)$article['text']);
		
		$article['text'] = Image_Helper::set_images_in_text((string)$article['text']);
		
		$this->smarty->assign('article', $article);
		$this->smarty->assign('reviews', $reviews);
		
		$this->smarty->display('front/reviews/articles_preview');
		
	}
	
	
	/**
	 * Добавление статьи к обзору
	 * @param int $reviews_id
	 */
	function articles_add($reviews_id = null)
	{
		//валидация обзора
		$reviews = $this->validate->validate_reviews_by_id($reviews_id, false);
		
		$this->header_block->set_title('Обзоры : Добавить статью');
		$this->header_block->set();
		
		//хлебные крошки
		$this->breadcrumbs_block->add("Список обзоров", DOMAIN.'/admin/reviews/');
		$this->breadcrumbs_block->add($reviews['name'], DOMAIN.'/admin/reviews/reviews_single/' . $reviews_id . '/');
		$this->breadcrumbs_block->add("Список статей обзора", DOMAIN.'/admin/reviews_articles/'.intval($reviews_id).'/');
		$this->breadcrumbs_block->add("Добавление статьи", null);
		$this->breadcrumbs_block->set();
		
		$this->smarty->assign('reviews', $reviews);
		$this->smarty->display('admin/reviews/reviews_articles_add');
		
	}
	
	
	/**
	 * Форма редактирования статьи
	 * @param int $articles_id
	 */
	function articles_edit($articles_id=null)
	{
		
		$articles = $this->validate->validate_articles_by_id($articles_id);
		$reviews = $this->validate->validate_reviews_by_id($articles['reviews_id'], false);
		
		$this->header_block->set_title('Обзоры : Редактирование статьи ' . $articles['name']);
		$this->header_block->set();
		
		//хлебные крошки
		$this->breadcrumbs_block->add("Список обзоров", DOMAIN.'/admin/reviews/');
		$this->breadcrumbs_block->add($reviews['name'], DOMAIN.'/admin/reviews/reviews_single/' . $articles['reviews_id'] . '/');
		$this->breadcrumbs_block->add("Список статей обзора", DOMAIN.'/admin/reviews_articles/'.intval($articles['reviews_id']).'/');
		$this->breadcrumbs_block->add("Редактирование статьи", null);
		$this->breadcrumbs_block->set();
		
		$entities = My_Entities_Helper::get_entities_by_entity_in($articles_id, 'reviews_articles');
		$this->smarty->assign('entities', $entities);
		$this->smarty->assign('articles', $articles);
		$this->smarty->display('admin/reviews/reviews_articles_add');
		
	}
	
	
	/**
	 * Добавление статьи
	 * @param int $reviews_id
	 */
	function articles_add_post()
	{
		
		$data = array();
		$data = $this->reviews_collector->articles_editadd();
		
		if (!empty($data))
		{
			$articles_id = $this->model_common->insert("reviews_articles", $data);
			//сохраняем подключенные модули {table}
			My_Entities_Helper::set_entities($data['text'], $articles_id, 'reviews_articles');
			$this->my_history->add_to_history('reviews_articles','insert', $data['reviews_id'], "Добавлена статья обзоров &laquo;" . $data['name'] . "&raquo;");
		}
		
		My_Url_Helper::redirect(DOMAIN.'/admin/reviews_articles/' . $data['reviews_id'] . '/');
		
	}
	
	
	/**
	 * Сохранение измененной статьи
	 * @param int $articles_id
	 */
	function articles_edit_post($articles_id=null)
	{
		$article = $this->validate->validate_articles_by_id($articles_id);
		
		$data = $this->reviews_collector->articles_editadd($article);
		
		if (!empty($data))
		{
			$this->model_common->update("reviews_articles", $data, array("id" => intval($articles_id)));
			//сохраняем подключенные модули {table}
			My_Entities_Helper::set_entities($data['text'], $articles_id, 'reviews_articles');
			$this->my_history->add_to_history('reviews_articles','update', $article['reviews_id'], "Обновлена статья обзоров &laquo;" . $article['name'] . "&raquo;");
		}
		
		My_Url_Helper::redirect(DOMAIN.'/admin/reviews_articles/'.$data['reviews_id'].'/');
	}
	
	
	/**
	 * Удаление статьи
	 * @param int $articles_id
	 */
	function articles_delete($articles_id)
	{
		
		$article = $this->validate->validate_articles_by_id($articles_id);
		
		$this->model_common->delete("reviews_articles", array("id" => intval($articles_id)));
		
		$this->my_history->add_to_history('reviews_articles','delete', $article['reviews_id'], "Удалена статья обзоров &laquo;" . $article['name'] . "&raquo;");
		
		My_Url_Helper::redirect($this->input->server('HTTP_REFERER'));
		
	}
	
}

?>