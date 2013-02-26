<?php 

/**
 * Разделы сайта
 * @author ashmits by 26.12.2012 12:15
 *
 */

class Sections extends Controller
{
	
	function __construct()
	{
		
		parent::Controller();
		
		$this->load->library(array('admin_menu','header_block', 'validate', 'breadcrumbs_block','reviews_collector','my_users','my_history','reviews_views'));
		$this->load->helper(array('my_url'));
		$this->load->model(array('model_common'));
		
		//проверка авторизации в админке
		if (!$this->my_users->get_active_admin_user())
		{
			My_Url_Helper::redirect(DOMAIN.'/admin/login/?from=' . My_Url_Helper::get_current_url());
		}
		
		$this->my_users->check_permission('admin');
		
		//активное левое меню Обзоры
		$this->admin_menu->set_active_section('sections');
		//передаем меню в шаблон
		$this->admin_menu->set();
		
	}
	
	
	function index()
	{
		
		$this->header_block->set_title('Разделы сайта');
		$this->header_block->set();
		
		$this->breadcrumbs_block->add('Разделы сайта', DOMAIN.'/admin/sections/');
		$this->breadcrumbs_block->set();
		
		$sections = $this->reviews_views->get_children_sections_by_parent();
		
		$this->smarty->assign('sections', $sections);
		
		$this->smarty->display('admin/sections/index');
		
	}
	
	/**
	 * Добавление нового раздела
	 * @param int $parent_id
	 */
	function section_add($parent_id = 0)
	{

		if (!empty($parent_id))
		{
			$parent = $this->validate->validate_section_by_id($parent_id);
		}
		
		$sections = $this->reviews_views->get_children_sections_by_parent();
		
		$this->header_block->set_title('Разделы сайта : Добавить новый раздел');
		$this->header_block->set();
		
		$this->breadcrumbs_block->add('Разделы сайта', DOMAIN.'/admin/sections/');
		$this->breadcrumbs_block->add('Добавить новый раздел', DOMAIN.'/admin/sections/section_add/' . $parent_id);
		$this->breadcrumbs_block->set();
		
		$this->smarty->assign('parent_id', $parent_id);
		
		$this->smarty->assign('sections', $sections);
		
		$this->smarty->display('admin/sections/section_add');
		
	}
	
	/**
	 * Редактирование разела сайта
	 * @author ashmits by 27.12.2012 17:31
	 * @param int $section_id
	 */
	function section_edit($section_id = null)
	{
		
		$section = $this->validate->validate_section_by_id( $section_id );
		
		$this->header_block->set_title('Разделы сайта : Редактировать раздел сайта ' . $section['section_name']);
		$this->header_block->set();
		
		$this->breadcrumbs_block->add('Разделы сайта', DOMAIN.'/admin/sections/');
		$this->breadcrumbs_block->add('Редактировать раздел сайта ' . $section['section_name'], DOMAIN.'/admin/sections/section_edit/' . $section_id);
		$this->breadcrumbs_block->set();
		
		$sections = $this->reviews_views->get_children_sections_by_parent();
		
		$this->smarty->assign('sections', $sections);
		$this->smarty->assign('section_single', $section);
		
		$this->smarty->display('admin/sections/section_add');
		
	}
	
	/**
	 * Сохранение нового раздела
	 * 
	 */
	function section_add_post()
	{
		
		$data = $this->reviews_collector->section_editadd();
		
		if (!empty($data))
		{
			$section_id = $this->model_common->insert("sections", $data);
			$this->my_history->add_to_history("sections","insert", $section_id, "Добавлен новый раздел &laquo;" . $data['section_name']."&raquo;");
		}
		
		My_Url_Helper::redirect(DOMAIN.'/admin/sections/');
		
	}
	
	/**
	 * Сохранение отредактированного раздела
	 * @param int $section_id
	 */
	function section_edit_post($section_id = null)
	{
		
		$section = $this->validate->validate_section_by_id($section_id);
		
		$data = $this->reviews_collector->section_editadd( $section );
		
		if (!empty($data))
		{
			$this->model_common->update("sections", $data, array("section_id" => $section_id));
			$this->my_history->add_to_history("sections","update", $section_id, "Обновлен раздел &laquo;" . $data['section_name']."&raquo;");
		}
		
		My_Url_Helper::redirect(DOMAIN.'/admin/sections/');
		
	}
	
	
	/**
	 * Удаление раздела сайта и подразделов
	 * @author ashmits by 27.12.2012 15:49
	 * @param unknown_type $section_id
	 */
	function section_delete($section_id = null)
	{
		
		$section = $this->validate->validate_section_by_id( $section_id );
		
		//находим ID подразделов
		$this->reviews_views->get_children_section_ids( $section_id );
		
		//достаем ID дочерних разделов
		$ids = $this->reviews_views->ids;
		
		//добавляем в массив ID основного раздела
		$ids[] = $section_id;
		
		//формируем строку запроса
		$where = implode(",", $ids);
		
		//удаляем
		$this->model_common->delete("sections", "section_id in ({$where})");
		
		$this->my_history->add_to_history("sections","delete", $section_id, "Удален раздел &laquo;" . $section['section_name']."&raquo; и подразделы");
		
		//редиректим
		My_Url_Helper::redirect($this->input->server('HTTP_REFERER'));
		
	}
	
}

?>