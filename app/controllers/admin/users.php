<?php

/**
 * ���������� �������������� � �������
 * ������� ������� admin
 * @author ashmits by 18.12.2012 17:05
 *
 */

final class Users extends Controller
{
	
	public function __construct()
	{
		
		parent::Controller();
		$this->load->library(array('admin_menu','header_block', 'breadcrumbs_block','validate','reviews_collector', 'pagination','my_users', 'my_history'));
		$this->load->helper(array('my_auth','my_url'));
		$this->load->model(array('model_common', 'model_user'));
		
		if (!$this->my_users->get_active_admin_user())
		{
			My_Url_Helper::redirect(DOMAIN.'/admin/login/?from=' . My_Url_Helper::get_current_url());
		}
		
		//�������� ����
		$this->my_users->check_permission(array('admin','news_redactor','reviews_redactor', 'redactor'));
		
		//�������� ����� ���� ������������
		//$this->admin_menu->set_active_section('users');
		$this->admin_menu->set();
		
	}
	
	/**
	 * ������� ���������
	 * @author ashmits by 25.12.2012 11:02
	 */
	function history_changes($hash = "", $page = 0)
	{
		
		$this->my_users->check_permission(array('admin','news_redactor','reviews_redactor','redactor'));
		$this->header_block->set_title("������������ : ������� ���������");
		$this->header_block->set();
		
		$this->breadcrumbs_block->add("������ �������������", DOMAIN.'/admin/users/');
		$this->breadcrumbs_block->add("������� ���������", DOMAIN.'/admin/users/history_changes/');
		$this->breadcrumbs_block->set();
		
		$this->admin_menu->set_active_section('history_changes');
		$this->admin_menu->set();
		
		if (empty($hash))
		{
			$hash = Common_Helper::array_to_hash(array(0));
		}
		
		//$changes = $this->my_history->get_history_changes_list(Common_Helper::hash_to_array($hash), intval($page) );
		$changes = $this->my_history->get_history_changes_list($hash, intval($page) );
		if (count($changes))
		{
			$this->smarty->assign('history_changes', $changes['changes']);
			
			//��������
			$p_config = $this->config->item('paging');
			$p_config['base_url'] = DOMAIN."/admin/users/history_changes/{$hash}/";
			$p_config['total_rows'] = $changes['total_changes'];
			$p_config['per_page'] = PER_PAGE_HISTORY_CHANGES;
			$p_config['uri_segment'] = 5;
			
			$this->pagination->initialize($p_config);
			$paging = $this->pagination->create_links();
			
			$this->smarty->assign('paging', $paging);
		}
		
		$this->smarty->assign('users', $this->my_users->get_users_list());
		$this->smarty->assign('hash', $hash);
		$this->smarty->assign('change_types', $this->my_history->change_types);
		$this->smarty->assign('change_objects', $this->my_history->change_objects);
		
		$this->smarty->assign('filter_params', Common_Helper::hash_to_array($hash));
		
		$this->smarty->display('admin/users/history_changes');
		
	}
	
	/**
	 * ������� �������� �� ������� �������������
	 * @param int $page
	 */
	public function index()
	{
		$this->my_users->check_permission('admin');
		//������ ���� �������������
		$users = $this->model_common->select("users", null, null, "user_register_date desc");
		
		//������� ������ ������������� �� �������
		$groups = $this->config->item('admin_groups');
		
		//������������ ������������� � ���������� ������
		foreach($users as $key=>$val)
		{
			$users[$key]['group'] =  $val['group_name'];
			$users[$key]['group_name'] =  $groups[$val['group_name']];
		}
		
		$this->smarty->assign('users', $users);
		
		$this->header_block->set_title('������������ : ������ �������������');
		$this->header_block->set();
		
		$this->breadcrumbs_block->add("������ �������������", DOMAIN.'/admin/users/');
		$this->breadcrumbs_block->set();
		
		$this->admin_menu->set_active_section('users');
		$this->admin_menu->set();
		
		$this->smarty->display('admin/users/users_index');
		
	}
	
	/**
	 * �������� ������������
	 * @param int $user_id
	 */
	function user_delete($user_id = null)
	{
		$this->my_users->check_permission('admin');
		$user = $this->validate->validate_user_by_id($user_id);
		$this->model_common->delete("users", array("user_id" => intval($user_id)));
		
		$this->my_history->add_to_history('users','delete', $user_id, "�������� ������������ &laquo;" . $user['user_login'] . "&raquo;");
		
		My_Url_Helper::redirect($this->input->server('HTTP_REFERER'));
		
	}
	
	/**
	 * ����� ���������� ������ ������������
	 */
	public function user_add()
	{

		$this->my_users->check_permission('admin');
		$this->header_block->set_title("������������ : �������� ������ ������������");
		$this->header_block->set();
		
		$this->breadcrumbs_block->add("������ �������������", DOMAIN.'/admin/users/');
		$this->breadcrumbs_block->add("���������� ������ ������������", DOMAIN.'/admin/users/user_add/');
		$this->breadcrumbs_block->set();
		
		$admin_groups = $this->config->item('admin_groups');
		
		$this->smarty->assign('admin_groups', $admin_groups);
		
		$this->smarty->display('admin/users/user_edit');
	}
	
	/**
	 * ���������� ������������
	 */
	function user_add_post()
	{
		$this->my_users->check_permission('admin');
		
		$data = $this->reviews_collector->user_edit();
		
		$user_id = $this->model_common->insert("users", $data);
		
		$this->my_history->add_to_history('users','insert', $user_id, "���������� ������������ &laquo;" . $data['user_login'] . "&raquo;");
		
		My_Url_Helper::redirect(DOMAIN.'/admin/users/');
		
	}
	
	/**
	 * �������������� ������������
	 * @author ashmits by 20.12.2012 10:47
	 * @param int $user_id
	 */
	public function user_edit($user_id = null)
	{
		$this->my_users->check_permission('admin');
		$user = $this->validate->validate_user_by_id($user_id);
				
		$this->header_block->set_title("������������ : �������������� ������������");
		$this->header_block->set();
		
		$this->breadcrumbs_block->add("������ �������������", DOMAIN.'/admin/users/');
		$this->breadcrumbs_block->add("�������������� ������������", DOMAIN.'/admin/users/user_edit/' . $user_id);
		$this->breadcrumbs_block->set();
		
		$this->smarty->assign('user', $user);
		
		$admin_groups = $this->config->item('admin_groups');
		
		$this->smarty->assign('admin_groups', $admin_groups);
		
		$this->smarty->display('admin/users/user_edit');
		
	}
	
	/**
	 * �������������� ������������
	 * @param int $user_id
	 */
	public function user_edit_post($user_id = null)
	{
		$this->my_users->check_permission('admin');
		//��������� ������������
		$user = $this->validate->validate_user_by_id($user_id);
		
		//������ ������ �� post
		$data = $this->reviews_collector->user_edit($user_id);
		
		if (!empty($data))
		{
			$this->model_common->update("users", $data, array("user_id" => $user_id));
			$this->my_history->add_to_history('users','update', $user_id, "���������� ������������ &laquo;" . $data['user_login'] . "&raquo;");
		}
		
		My_Url_Helper::redirect(DOMAIN.'/admin/users/');
		
	}
	
}

?>