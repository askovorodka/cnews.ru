<?php 

/**
 * ������� �����
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
		
		//�������� ����������� � �������
		if (!$this->my_users->get_active_admin_user())
		{
			My_Url_Helper::redirect(DOMAIN.'/admin/login/?from=' . My_Url_Helper::get_current_url());
		}
		
		$this->my_users->check_permission('admin');
		
		//�������� ����� ���� ������
		$this->admin_menu->set_active_section('sections');
		//�������� ���� � ������
		$this->admin_menu->set();
		
	}
	
	
	function index()
	{
		
		$this->header_block->set_title('������� �����');
		$this->header_block->set();
		
		$this->breadcrumbs_block->add('������� �����', DOMAIN.'/admin/sections/');
		$this->breadcrumbs_block->set();
		
		$sections = $this->reviews_views->get_children_sections_by_parent();
		
		$this->smarty->assign('sections', $sections);
		
		$this->smarty->display('admin/sections/index');
		
	}
	
	/**
	 * ���������� ������ �������
	 * @param int $parent_id
	 */
	function section_add($parent_id = 0)
	{

		if (!empty($parent_id))
		{
			$parent = $this->validate->validate_section_by_id($parent_id);
		}
		
		$sections = $this->reviews_views->get_children_sections_by_parent();
		
		$this->header_block->set_title('������� ����� : �������� ����� ������');
		$this->header_block->set();
		
		$this->breadcrumbs_block->add('������� �����', DOMAIN.'/admin/sections/');
		$this->breadcrumbs_block->add('�������� ����� ������', DOMAIN.'/admin/sections/section_add/' . $parent_id);
		$this->breadcrumbs_block->set();
		
		$this->smarty->assign('parent_id', $parent_id);
		
		$this->smarty->assign('sections', $sections);
		
		$this->smarty->display('admin/sections/section_add');
		
	}
	
	/**
	 * �������������� ������ �����
	 * @author ashmits by 27.12.2012 17:31
	 * @param int $section_id
	 */
	function section_edit($section_id = null)
	{
		
		$section = $this->validate->validate_section_by_id( $section_id );
		
		$this->header_block->set_title('������� ����� : ������������� ������ ����� ' . $section['section_name']);
		$this->header_block->set();
		
		$this->breadcrumbs_block->add('������� �����', DOMAIN.'/admin/sections/');
		$this->breadcrumbs_block->add('������������� ������ ����� ' . $section['section_name'], DOMAIN.'/admin/sections/section_edit/' . $section_id);
		$this->breadcrumbs_block->set();
		
		$sections = $this->reviews_views->get_children_sections_by_parent();
		
		$this->smarty->assign('sections', $sections);
		$this->smarty->assign('section_single', $section);
		
		$this->smarty->display('admin/sections/section_add');
		
	}
	
	/**
	 * ���������� ������ �������
	 * 
	 */
	function section_add_post()
	{
		
		$data = $this->reviews_collector->section_editadd();
		
		if (!empty($data))
		{
			$section_id = $this->model_common->insert("sections", $data);
			$this->my_history->add_to_history("sections","insert", $section_id, "�������� ����� ������ &laquo;" . $data['section_name']."&raquo;");
		}
		
		My_Url_Helper::redirect(DOMAIN.'/admin/sections/');
		
	}
	
	/**
	 * ���������� ������������������ �������
	 * @param int $section_id
	 */
	function section_edit_post($section_id = null)
	{
		
		$section = $this->validate->validate_section_by_id($section_id);
		
		$data = $this->reviews_collector->section_editadd( $section );
		
		if (!empty($data))
		{
			$this->model_common->update("sections", $data, array("section_id" => $section_id));
			$this->my_history->add_to_history("sections","update", $section_id, "�������� ������ &laquo;" . $data['section_name']."&raquo;");
		}
		
		My_Url_Helper::redirect(DOMAIN.'/admin/sections/');
		
	}
	
	
	/**
	 * �������� ������� ����� � �����������
	 * @author ashmits by 27.12.2012 15:49
	 * @param unknown_type $section_id
	 */
	function section_delete($section_id = null)
	{
		
		$section = $this->validate->validate_section_by_id( $section_id );
		
		//������� ID �����������
		$this->reviews_views->get_children_section_ids( $section_id );
		
		//������� ID �������� ��������
		$ids = $this->reviews_views->ids;
		
		//��������� � ������ ID ��������� �������
		$ids[] = $section_id;
		
		//��������� ������ �������
		$where = implode(",", $ids);
		
		//�������
		$this->model_common->delete("sections", "section_id in ({$where})");
		
		$this->my_history->add_to_history("sections","delete", $section_id, "������ ������ &laquo;" . $section['section_name']."&raquo; � ����������");
		
		//����������
		My_Url_Helper::redirect($this->input->server('HTTP_REFERER'));
		
	}
	
}

?>