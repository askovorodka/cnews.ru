<?php 
require_once APPPATH . 'controllers/starter.php';
/**
 * Интервью обзоров
 * @author ashmits by 11.12.2012 12:00
 *
 */
class Reviews_Interviews extends Controller
{
	
	public function __construct()
	{
		parent::Controller();
		$this->load->library(array('admin_menu','header_block', 'validate', 'breadcrumbs_block','reviews_collector','my_users','my_history','reviews_views'));
		$this->load->helper(array('my_auth','my_url','image'));
		$this->load->model(array('model_common'));
		
		//проверка авторизации в админке
		if (!$this->my_users->get_active_admin_user())
		{
			My_Url_Helper::redirect(DOMAIN.'/admin/login/?from=' . My_Url_Helper::get_current_url());
		}
		
		$this->my_users->check_permission(array('admin','redactor','reviews_author', 'reviews_writer','reviews_redactor'));

		//активное левое меню Обзоры
		$this->admin_menu->set_active_section('reviews');
		//передаем меню в шаблон
		$this->admin_menu->set();
		
		//$this->output->enable_profiler(TRUE);
	}
	
	/**
	 * предпросмотр интервью
	 * @author ashmits by 16.01.2013 17:54
	 * @param int $interview_id
	 */
	function preview($interview_id)
	{
		
		$interview = $this->validate->validate_interview_by_id(intval($interview_id), false);
		
		$reviews = $this->validate->validate_reviews_by_id(intval($interview['reviews_id']), false);
		
		$interview['text'] = Common_Helper::get_tables_by_tags($interview['text']);
		
		$interview['text'] = Image_Helper::set_images_in_text($interview['text']);
		
		$this->smarty->assign('interview', $interview);
		$this->smarty->assign('reviews', $reviews);
		
		$this->smarty->display('front/reviews/interview_preview');
		
	}
	
	/**
	 * Главная страница интервью обзоров
	 * @param unknown_type $reviews_id
	 */
	function index($reviews_id=null)
	{
		
		//валидация обзора
		$reviews = $this->validate->validate_reviews_by_id($reviews_id, false);		
		
		//находим интервью обзора
		//$interviews = $this->model_common->select("reviews_interviews", array("reviews_id" => intval($reviews_id)), null, "date desc");
		$interviews = $this->reviews_views->get_reviews_interviews($reviews_id);
		
		
		//Блоки для отображения
		$this->header_block->set_title('Обзоры : Интервью обзора');
		
		$this->breadcrumbs_block->add("Список обзоров", DOMAIN.'/admin/reviews/');
		$this->breadcrumbs_block->add($reviews['name'], DOMAIN.'/admin/reviews/reviews_single/'.$reviews_id.'/');
		$this->breadcrumbs_block->add("Список интервью обзора", null);
		
		$this->header_block->set();
		$this->breadcrumbs_block->set();
		
		$this->smarty->assign('reviews', $reviews);
		$this->smarty->assign('interviews', $interviews);
		$this->smarty->display('admin/reviews/reviews_interviews');
		
	}
	
	/**
	 * Добавление интервью
	 * @param unknown_type $reviews_id
	 */
	function interview_add($reviews_id = null)
	{

		//валидация обзора
		$reviews = $this->validate->validate_reviews_by_id($reviews_id, false);
		
		//Блоки для отображения
		$this->header_block->set_title('Обзоры : Добавить интервью');
		
		$this->breadcrumbs_block->add("Список обзоров", DOMAIN.'/admin/reviews/');
		$this->breadcrumbs_block->add($reviews['name'], DOMAIN.'/admin/reviews/reviews_single/' . $reviews_id . '/');
		$this->breadcrumbs_block->add("Список интервью обзора", DOMAIN.'/admin/reviews_interviews/'.intval($reviews_id).'/');
		$this->breadcrumbs_block->add("Добавление интервью", null);
		
		//передаем блоки в шаблон
		$this->header_block->set();
		$this->breadcrumbs_block->set();
		
		$this->smarty->assign('reviews', $reviews);
		$this->smarty->display('admin/reviews/reviews_interview_add');
		
	}
	
	
	/**
	 * Добавление интервью
	 */
	function interview_add_post()
	{
		$data = $this->reviews_collector->interview_editadd();
		if ($data)
		{
			$interview_id = $this->model_common->insert("reviews_interviews", $data);
			$this->my_history->add_to_history('reviews_interviews','insert', $data['reviews_id'], "Добавлено новое интервью &laquo;" . $data['person'] . "&raquo;");
		}
		
		My_Url_Helper::redirect(DOMAIN.'/admin/reviews_interviews/' . $data['reviews_id']);
	}
	
	
	/**
	 * Редактирование интервью
	 * @param int $interview_id
	 */
	function interview_edit($interview_id)
	{
		
		$interview = $this->validate->validate_interview_by_id($interview_id);
		
		$reviews = $this->validate->validate_reviews_by_id($interview['reviews_id'], false);
				
		//Блоки
		$this->header_block->set_title('Обзоры : Редактирование интервью ' . $interview['person']);
		
		$this->breadcrumbs_block->add("Список обзоров", DOMAIN.'/admin/reviews/');
		$this->breadcrumbs_block->add($reviews['name'], DOMAIN.'/admin/reviews/reviews_single/' . $reviews['id'].'/');
		$this->breadcrumbs_block->add("Список интервью обзора", DOMAIN.'/admin/reviews_interviews/'.intval($interview['reviews_id']).'/');
		$this->breadcrumbs_block->add("Редактирование интервью", null);
		
		$this->header_block->set();
		$this->breadcrumbs_block->set();
		
		$this->smarty->assign('interview', $interview);
		$this->smarty->display('admin/reviews/reviews_interview_add');
		
	}
	
	
	/**
	 * Сохранение интервью
	 * @param int $interview_id
	 */
	function interview_edit_post($interview_id = null)
	{
		//валидация интервью
		$interview = $this->validate->validate_interview_by_id($interview_id);
		
		$data = $this->reviews_collector->interview_editadd($interview);
	
		if (!empty($data))
		{
			$this->model_common->update("reviews_interviews", $data, array("id" => intval($interview_id)));
			$this->my_history->add_to_history('reviews_interviews','update', $data['reviews_id'], "Обновлено интервью &laquo;" . $data['person'] . "&raquo;");
		}
	
		My_Url_Helper::redirect(DOMAIN.'/admin/reviews_interviews/'.$data['reviews_id'].'/');
	}
	
	
	/**
	 * Удаление интервью
	 * @param int $interview_id
	 */
	function interview_delete($interview_id = null)
	{
	
		$interview = $this->validate->validate_interview_by_id($interview_id);
		
		$this->model_common->delete("reviews_interviews", array("id" => intval($interview_id)));
		$this->my_history->add_to_history('reviews_interviews','delete', $interview['reviews_id'], "Удалено интервью &laquo;" . $interview['person'] . "&raquo;");
	
		My_Url_Helper::redirect($this->input->server('HTTP_REFERER'));
	
	}
	
	
}

?>