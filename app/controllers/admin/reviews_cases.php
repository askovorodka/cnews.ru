<?php 

//require_once APPPATH . 'controllers/starter.php';

/**
 * Кейсы обзоров
 * @author ashmits by 11.12.2012 15:51
 *
 */
class Reviews_Cases extends Controller
{
	
	public function __construct()
	{
		parent::Controller();
		$this->load->library(array('admin_menu','header_block', 'validate', 'breadcrumbs_block','reviews_collector','my_users','my_history', 'reviews_views'));
		$this->load->helper(array('my_auth','my_url', 'my_entities', 'image'));
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
	
	function preview($case_id)
	{
		
		$case = $this->validate->validate_case_by_id(intval($case_id), false);
		$reviews = $this->validate->validate_reviews_by_id($case['reviews_id'], false);
		
		$case['text'] = Common_Helper::get_tables_by_tags((string)$case['text']);
		$case['text'] = Image_Helper::set_images_in_text((string)$case['text']);
		
		$this->smarty->assign('case', $case);
		$this->smarty->assign('reviews', $reviews);
		$this->smarty->display('front/reviews/case_preview');
	}
	
	/**
	 * Главная страница кейсов обзоров
	 * @param int $reviews_id
	 */
	function index($reviews_id=null)
	{
		
		//валидация обзора
		$reviews = $this->validate->validate_reviews_by_id($reviews_id, false);
		
		//находим кейсы обзора
		$cases = $this->reviews_views->get_reviews_cases($reviews_id);
		
		//Блоки для отображения
		$this->header_block->set_title('Обзоры : Кейсы обзора');
		
		$this->breadcrumbs_block->add("Список обзоров", DOMAIN.'/admin/reviews/');
		$this->breadcrumbs_block->add($reviews['name'], DOMAIN.'/admin/reviews/reviews_single/'.$reviews_id.'/');
		$this->breadcrumbs_block->add("Список кейсов обзора", null);
		
		$this->header_block->set();
		$this->breadcrumbs_block->set();
		
		$this->smarty->assign('reviews', $reviews);
		$this->smarty->assign('cases', $cases);
		$this->smarty->display('admin/reviews/reviews_cases');
		
	}
	
	
	
	/**
	 * Добавление кейса
	 * @param int $reviews_id
	 */
	function case_add($reviews_id = null)
	{
	
		//валидация обзора
		$reviews = $this->validate->validate_reviews_by_id($reviews_id, false);
		
		//Блоки для отображения
		$this->header_block->set_title('Обзоры : Добавить кейс');
	
		$this->breadcrumbs_block->add("Список обзоров", DOMAIN.'/admin/reviews/');
		$this->breadcrumbs_block->add($reviews['name'], DOMAIN.'/admin/reviews/reviews_single/'.$reviews_id.'/');
		$this->breadcrumbs_block->add("Список кейсов обзора", DOMAIN.'/admin/reviews_cases/'.intval($reviews_id).'/');
		$this->breadcrumbs_block->add("Добавление кейса", null);
	
		//передаем блоки в шаблон
		$this->header_block->set();
		$this->breadcrumbs_block->set();
	
		$this->smarty->assign('reviews', $reviews);
		$this->smarty->display('admin/reviews/reviews_case_add');
	
	}


	/**
	 * Добавление кейса
	 */
	function case_add_post()
	{
		$data = $this->reviews_collector->case_editadd();
		if ($data)
		{
			$case_id = $this->model_common->insert("reviews_cases", $data);
			$this->my_history->add_to_history('reviews_cases','insert', $data['reviews_id'], "Добавлен новый кейс &laquo;" . $data['name'] . "&raquo;");
			
			My_Entities_Helper::set_entities($data['text'], $case_id, 'reviews_cases');
				
		}
	
		My_Url_Helper::redirect(DOMAIN.'/admin/reviews_cases/' . $data['reviews_id']);
	}


	
	/**
	 * Форма редактирования кейса
	 * 11.12.2012 17:17
	 * @param int $articles_id
	 */
	function case_edit($case_id)
	{
		
		$case = $this->validate->validate_case_by_id($case_id);
		
		$reviews = $this->validate->validate_reviews_by_id($case['reviews_id'], false);
	
		$this->header_block->set_title('Обзоры : Редактирование кейса ' . $case['name']);
		$this->header_block->set();
	
		//хлебные крошки
		$this->breadcrumbs_block->add("Список обзоров", DOMAIN.'/admin/reviews/');
		$this->breadcrumbs_block->add($reviews['name'], DOMAIN.'/admin/reviews/reviews_single/' . $reviews['id'].'/');
		$this->breadcrumbs_block->add("Список кейсов обзора", DOMAIN.'/admin/reviews_cases/'.intval($case['reviews_id']).'/');
		$this->breadcrumbs_block->add("Редактирование кейса", null);
		$this->breadcrumbs_block->set();
	
		$entities = My_Entities_Helper::get_entities_by_entity_in($case_id, 'reviews_cases');
		$this->smarty->assign('entities', $entities);
		
		$this->smarty->assign('case', $case);
		$this->smarty->display('admin/reviews/reviews_case_add');
	
	
	}
	

	/**
	 * Сохранение кейса
	 * @param int $case_id
	 */
	function case_edit_post($case_id = null)
	{
		
		$case = $this->validate->validate_case_by_id($case_id);
	
		$data = $this->reviews_collector->case_editadd($case);
	
		if (!empty($data))
		{
			$this->model_common->update("reviews_cases", $data, array("id" => intval($case_id)));
			$this->my_history->add_to_history('reviews_cases','update', $data['reviews_id'], "Обновлен кейс &laquo;" . $data['name'] . "&raquo;");
			
			My_Entities_Helper::set_entities($data['text'], $case_id, 'reviews_cases');
		}
	
		My_Url_Helper::redirect(DOMAIN.'/admin/reviews_cases/'.$data['reviews_id'].'/');
	}
	
	
	/**
	 * Удаление кейса
	 * @param int $case_id
	 */
	function case_delete($case_id)
	{
		//валидация кейса
		$case = $this->validate->validate_case_by_id($case_id);
	
		$this->model_common->delete("reviews_cases", array("id" => intval($case_id)));
		
		$this->my_history->add_to_history('reviews_cases','delete', $case['reviews_id'], "Удален кейс &laquo;" . $case['name'] . "&raquo;");
	
		My_Url_Helper::redirect($this->input->server('HTTP_REFERER'));
	
	}
	
	
}

?>