<?php 

//require_once APPPATH . 'controllers/starter.php';

/**
 * 
 * @author ashmits by 05.12.2012 14:15
 * �������� ����� �������
 *
 */

class Frontpage extends Controller
{
	
	function __construct()
	{
		//parent::Starter();
		parent::Controller();
		$this->load->library(array('admin_menu','header_block','my_users', 'breadcrumbs_block'));
		
		$this->load->helper(array('my_auth','my_url'));
		$this->load->model(array('model_common'));
		
		//�������� ����������� � �������
		if (!$this->my_users->get_active_admin_user())
		{
			My_Url_Helper::redirect(DOMAIN.'/admin/login/?from=' . My_Url_Helper::get_current_url());
		}
		
	}
	
	/**
	 * 
	 * @param unknown_type $params
	 * ��������� �������� �������
	 */
	function index()
	{
		//�����
		$this->header_block->set_title('������� �������� �������');
		//�������������� �����
		$this->header_block->set();
		
		//�������������� ����� ����
		$this->admin_menu->set();
		
		$this->breadcrumbs_block->set();
		
		$this->smarty->display('admin/frontpage');
	}
	
}

?>