<?php
/**
 * �������
 * @author ashmits by 18.01.2013
 *
 */
class Tables extends Controller
{
	
	function __construct()
	{
		parent::Controller();
		
		$this->load->library(array('admin_menu','header_block', 'validate', 'breadcrumbs_block','exceltotable','reviews_collector','generate_table','my_users','my_history','reviews_views','tablecollector','tables_views'));
		$this->load->helper(array('my_url','my_files', 'my_entities'));
		$this->load->model(array('model_common'));
		
		//�������� ����������� � �������
		if (!$this->my_users->get_active_admin_user())
		{
			My_Url_Helper::redirect(DOMAIN.'/admin/login/?from=' . My_Url_Helper::get_current_url());
		}
		
		$this->my_users->check_permission(array('admin','redactor','news_redactor','reviews_author', 'reviews_writer','reviews_redactor','news_writer','news_author'));
		
		//�������� ����� ���� ������
		$this->admin_menu->set_active_section('tables');
		//�������� ���� � ������
		$this->admin_menu->set();

		//$this->output->enable_profiler(FALSE);
		
	}
	
	function index()
	{
		$this->tables_list();
	}
	
	function tables_list($hash = null, $page = 0)
	{
		
		if (empty($hash))
		{
			$hash = Common_Helper::array_to_hash(array(0));
		}
		
		$this->header_block->set_title('�������');
		$this->header_block->set();
		
		$this->breadcrumbs_block->add("�������", DOMAIN.'/admin/tables/');
		$this->breadcrumbs_block->set();
		
		$tables = $this->tables_views->get_tables_list($hash, $page);
		
		$array = Common_Helper::hash_to_array($hash);
		
		$this->smarty->assign('tables', $tables);
		$this->smarty->assign('hash', $hash);
		$this->smarty->assign('users', $this->my_users->get_users_list());
		$this->smarty->assign('array', $array);
		
		if (!empty($array['for_wisiwyg']))
		{
			$this->smarty->display('admin/tables/tables_for_js');
		}
		else
		{
			$this->smarty->display('admin/tables/index');
		}
		
	}
	
	
	function add($type = null)
	{
		
		switch($type)
		{
			case "excel":
				$this->add_excel_table_form();
				break;
			default:
				$this->add_table_form();
				break;
		}
		
	}
	
	/**
	 * ����� ���������� *.xls ����� �������
	 */
	function add_excel_table_form()
	{

		$this->header_block->set_title('�������� ������� �� Excel');
		$this->header_block->set();
		
		$this->breadcrumbs_block->add("�������", DOMAIN.'/admin/tables/');
		$this->breadcrumbs_block->add("�������� ������� �� Excel", DOMAIN.'/admin/tables/add/excel/');
		$this->breadcrumbs_block->set();
		
		$this->smarty->display('admin/tables/add_excel_table_form');
		
	}
	
	/**
	 * ���������� ������� �� *.xls
	 */
	function excel_add_post()
	{
		
		$insert_data = $this->tablecollector->editadd();
		$config_upload = $this->config->item('table_upload');
		$config_upload['upload_path'] .= "tables/";
		
		$path = My_Files_Helper::set_table_dirs($insert_data['date'], $config_upload['upload_path']);
		$config_upload['upload_path'] = $path;
		
		$this->load->library('upload', $config_upload);
		
		if (!$this->upload->do_upload("table"))
		{
			show_error("������ �������� ����� " . $this->upload->display_errors());
		}
		
		$data = $this->upload->data();
		
		//������ ���� �� ����� �� �������
		$file = $data['full_path'];
		//������������ *.xls ���� � ���������� ������� �� ������� �����
		$this->exceltotable->read_file($file);
		//���������� ��������� html ������� � ������
		$tables = $this->exceltotable->get_tables();
		
		if (!empty($tables))
		{
			//� ����� ����� *.xls ����� ���� ��������� ������ � ���������
			foreach ($tables as $key=>$val)
			{
				//��������� ������ ��� �������
				//$data = $this->reviews_collector->table_editadd(null, (string)$val);
				$insert_data['structure'] = Common_Helper::set_structure_table($val);
				//���������
				$table_id = $this->model_common->insert("tables", $insert_data);
		
				$this->my_history->add_to_history('tables','insert', $table_id, "�������� ����� ������� &laquo;" . $insert_data['description'] . "&raquo;");
		
			}
		}
		
		//��������������
		My_Url_Helper::redirect(DOMAIN.'/admin/tables/');
		
		
	}
	
	/**
	 * ����� ���������� ������� - �����������
	 */
	function add_table_form()
	{
		
		$this->header_block->set_title('������� �������');
		$this->header_block->set();
		
		$this->breadcrumbs_block->add("�������", DOMAIN.'/admin/tables/');
		$this->breadcrumbs_block->add("������� �������", DOMAIN.'/admin/tables/add/');
		$this->breadcrumbs_block->set();
		
		$this->smarty->display('admin/tables/add_table_form');
		
	}
	
	/**
	 * ���������� ����� �������
	 */
	function table_add_post()
	{
		
		$table_structure = $this->generate_table->empty_table();
		$insert_data = $this->tablecollector->editadd();
		$insert_data['structure'] = Common_Helper::set_structure_table($table_structure);
		$table_id = $this->model_common->insert("tables", $insert_data);
		$this->my_history->add_to_history('tables','insert', $table_id, "������� ����� �������");
		My_Url_Helper::redirect(DOMAIN.'/admin/tables/edit/' . $table_id.'/');
		
	}
	
	/**
	 * ����� �������������� �������
	 * @param unknown_type $table_id
	 */
	function edit($table_id)
	{
		
		$table = $this->validate->validate_table_single_by_id($table_id);
		
		$this->header_block->set_title('������������� �������');
		$this->header_block->set();
		
		$this->breadcrumbs_block->add("�������", DOMAIN.'/admin/tables/');
		$this->breadcrumbs_block->add("������������� �������", DOMAIN.'/admin/tables/edit/'.$table_id);
		$this->breadcrumbs_block->set();
		
		$entities_in = My_Entities_Helper::get_entities_in_by_entity($table_id, 'tables');
		
		$this->smarty->assign('entities_in', $entities_in);
		$this->smarty->assign('table', $table);
		$this->smarty->display('admin/tables/edit');
		
	}
	
	/**
	 * ���������� ����������������� �������
	 * @author ashmits by 18.01.2013
	 * @param int $table_id
	 */
	function edit_post($table_id)
	{
		
		$table = $this->validate->validate_table_single_by_id($table_id);
		$update_data = $this->tablecollector->editadd($table);
		$this->model_common->update("tables", $update_data, array("table_id" => $table_id));
		$this->my_history->add_to_history('tables','update', $table_id, "���������������� ������� &laquo;" . $update_data['description'] . "&raquo;");
		
		My_Url_Helper::redirect(DOMAIN.'/admin/tables/');
		
	}
	
	/**
	 * �������� �������
	 * @author ashmits by 18.01.2013 17:10
	 * @param int $table_id
	 */
	function delete($table_id)
	{
		$table = $this->validate->validate_table_single_by_id($table_id);
		$this->model_common->delete("tables", array("table_id" => $table_id));
		$this->my_history->add_to_history('tables','delete', $table_id, "������� ������� &laquo;" . $table['description'] . "&raquo;");
		
		My_Url_Helper::redirect($this->input->server('HTTP_REFERER'));
	}
	
	function view($table_id)
	{
		$table = $this->validate->validate_table_single_by_id($table_id, false);
		
		//�����
		$this->header_block->set_title('������� : �������� �������');
		
		$this->breadcrumbs_block->add("�������", DOMAIN.'/admin/tables/');
		$this->breadcrumbs_block->add("�������� �������", null);
		
		$this->header_block->set();
		$this->breadcrumbs_block->set();
		$table['structure'] = Common_Helper::table_configure_by_limit($table['structure'], $table['description'], 1000);
		$this->smarty->assign('table', $table);
		//$this->smarty->display('admin/reviews/reviews_table_view');
		$this->smarty->display('front/tables/preview');
		
	}
	
	
	function preview($table_id)
	{
		
		$table = $this->validate->validate_table_single_by_id($table_id, false);
		$table['structure'] = Common_Helper::table_configure_by_limit($table['structure'], $table['description']);
		$this->smarty->assign('table', $table);
		$this->smarty->display('front/tables/preview');
	}
	
	
}

?>