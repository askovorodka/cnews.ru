<?php 

require_once APPPATH . 'controllers/starter.php';

/**
 * ���������� ������� ������
 * @author ashmits by 12.12.2012 10:18
 *
 */

class Reviews_Tables extends Controller
{
	
	public function __construct()
	{
		parent::Controller();
		$this->load->library(array('admin_menu','header_block', 'validate', 'breadcrumbs_block','reviews_collector','exceltotable','generate_table','my_users','my_history','reviews_views'));
		$this->load->helper(array('my_auth','my_url'));
		$this->load->model(array('model_common'));
		
		//�������� ����������� � �������
		if (!$this->my_users->get_active_admin_user())
		{
			My_Url_Helper::redirect(DOMAIN.'/admin/login/?from=' . My_Url_Helper::get_current_url());
		}
		
		$this->my_users->check_permission(array('admin','redactor','reviews_author', 'reviews_writer','reviews_redactor'));
		
		//�������� ����� ���� ������
		$this->admin_menu->set_active_section('reviews');
		//�������� ���� � ������
		$this->admin_menu->set();
		
		//$this->output->enable_profiler(TRUE);
	}
	
	
	function preview($table_id)
	{
		
		$table = $this->validate->validate_table_by_id(intval($table_id));
		$reviews = $this->validate->validate_reviews_by_id($table['reviews_id']);
		
		if (!empty($table))
		{
			//$table['structure'] = Common_Helper::set_structure_table($table['structure'], $table['description']);
			$table['structure'] = Common_Helper::table_configure_by_limit($table['structure'], $table['description'], null);
		}
		
		$this->smarty->assign('reviews', $reviews);
		$this->smarty->assign('table', $table);
		$this->smarty->display('front/reviews/table_preview');
		
	}
	
	/**
	 * ������� �������� ������ �������
	 * @param unknown_type $reviews_id
	 */
	function index($reviews_id=null)
	{
		
		//������� �����
		$reviews = $this->validate->validate_reviews_by_id($reviews_id, false);
		
		//������� ������� ������
		$tables = $this->reviews_views->get_tables_list_by_reviews(intval($reviews_id));
		
		//����� ��� �����������
		$this->header_block->set_title('������ : ������� ������');
		
		$this->breadcrumbs_block->add("������ �������", DOMAIN.'/admin/reviews/');
		$this->breadcrumbs_block->add($reviews['name'], DOMAIN.'/admin/reviews/reviews_single/'.$reviews['id'].'/');
		$this->breadcrumbs_block->add("������ ������ ������", null);
		
		$this->header_block->set();
		$this->breadcrumbs_block->set();
		
		$this->smarty->assign('reviews', $reviews);
		$this->smarty->assign('tables', $tables);
		$this->smarty->display('admin/reviews/reviews_tables');
		
	}
	
	
	/**
	 * ���������� �������
	 * @param int $reviews_id
	 */
	function table_add($reviews_id = null)
	{
		
		$reviews = $this->validate->validate_reviews_by_id($reviews_id, false);
				
		//����� ��� �����������
		$this->header_block->set_title('������ : �������� �������');
		
		$this->breadcrumbs_block->add("������ �������", DOMAIN.'/admin/reviews/');
		$this->breadcrumbs_block->add($reviews['name'], DOMAIN.'/admin/reviews/reviews_single/'.$reviews['id'].'/');
		$this->breadcrumbs_block->add("������ ������ ������", DOMAIN.'/admin/reviews_tables/'.intval($reviews_id).'/');
		$this->breadcrumbs_block->add("���������� �������", null);
		
		//�������� ����� � ������
		$this->header_block->set();
		$this->breadcrumbs_block->set();
		
		$this->smarty->assign('reviews', $reviews);
		$this->smarty->display('admin/reviews/reviews_table_add');
		
	}
	
	/**
	 * ����� ������������ �������
	 * @param int $reviews_id
	 */
	function table_generate($reviews_id)
	{
		
		$reviews = $this->validate->validate_reviews_by_id($reviews_id, false);
				
		//����� ��� �����������
		$this->header_block->set_title('������ : ������� �������');
		
		$this->breadcrumbs_block->add("������ �������", DOMAIN.'/admin/reviews/');
		$this->breadcrumbs_block->add($reviews['name'], DOMAIN.'/admin/reviews/reviews_single/'.$reviews['id'].'/');
		$this->breadcrumbs_block->add("������ ������ ������", DOMAIN.'/admin/reviews_tables/'.intval($reviews_id).'/');
		$this->breadcrumbs_block->add("�������� �������", null);
		
		//�������� ����� � ������
		$this->header_block->set();
		$this->breadcrumbs_block->set();
		
		$this->smarty->assign('reviews', $reviews);
		$this->smarty->display('admin/reviews/reviews_table_generate');
		
	}
	
	/**
	 * ��������� �������
	 * @param int $reviews_id
	 */
	function generate_post($reviews_id=null)
	{
		//��������� ������
		$reviews = $this->validate->validate_reviews_by_id($reviews_id, false);
		
		//���������� �������
		$table = $this->generate_table->empty_table();
		
		//������� ������ ��� ��������� � ��
		$data = $this->reviews_collector->table_editadd(null,$table);
		
		if (!empty($data))
		{
			//��������� ����� ������� � ��
			$table_id = $this->model_common->insert("reviews_tables", $data);
			
			$this->my_history->add_to_history('reviews_tables','insert', $reviews_id, "�������������� ����� ������� &laquo;" . $data['description'] . "&raquo;");
			//�������������� �� �������� �������������� �������, ��� ����������
			My_Url_Helper::redirect(DOMAIN.'/admin/reviews_tables/table_edit/' . $table_id.'/');
		}
		
	}
	
	
	/**
	 * �������� �������
	 * @param unknown_type $reviews_id
	 */
	function table_add_post($reviews_id=null)
	{
		
		/*
		$this->load->library('ExcelReader');
		//$this->excelreader->readfile(ROOT.'/files/24/table_test.xlsx');
		$this->excelreader->readfile(ROOT.'/files/24/price.xls');
		exit();
		*/
		
		$reviews = $this->validate->validate_reviews_by_id($reviews_id, false);
		
		//���������� ���� �������� ������
		$config_upload = $this->config->item('table_upload');
		//�������, ���� ���, ����� /files/id_������/ ��� �������� ������ *.xls
		if (!is_dir($config_upload['upload_path'].'/'.$reviews_id))
		{
			@mkdir($config_upload['upload_path'].'/'.$reviews_id);
			@chmod($config_upload['upload_path'].'/'.$reviews_id, 0777);
		}
		
		$config_upload['upload_path'] = $config_upload['upload_path'] . '/' . $reviews_id;
		$this->load->library('upload', $config_upload);
		
		//������ ���� �� ������
		if (!$this->upload->do_upload("table"))
		{
			show_error("������ �������� ����� " . $this->upload->display_errors());
		}
		
		$data = $this->upload->data();
		
		if (empty($data))
		{
			show_error("������ �������� �����");
		}
		
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
				$data = $this->reviews_collector->table_editadd(null, (string)$val);
				//���������
				$this->model_common->insert("reviews_tables", $data);
				
				$this->my_history->add_to_history('reviews_tables','insert', $reviews_id, "�������� ����� ������� &laquo;" . $data['description'] . "&raquo;");
				
			}
		}
		
		//��������������
		My_Url_Helper::redirect(DOMAIN.'/admin/reviews_tables/'.$reviews_id.'/');
		
	}

	
	
	
	/**
	 * �������������� �������
	 * @param int $table_id
	 */
	function table_edit($table_id)
	{
		
		$table = $this->validate->validate_table_by_id($table_id);
	
		$reviews = $this->validate->validate_reviews_by_id($table['reviews_id'], false);
				
		//�����
		$this->header_block->set_title('������ : �������������� �������');
		
		$this->breadcrumbs_block->add("������ �������", DOMAIN.'/admin/reviews/');
		$this->breadcrumbs_block->add($reviews['name'], DOMAIN.'/admin/reviews/reviews_single/'.$reviews['id'].'/');
		$this->breadcrumbs_block->add("������ ������ ������", DOMAIN.'/admin/reviews_tables/'.intval($table['reviews_id']).'/');
		$this->breadcrumbs_block->add("�������������� �������", null);
		
		$this->header_block->set();
		$this->breadcrumbs_block->set();
		
		$this->smarty->assign('table', $table);
		$this->smarty->display('admin/reviews/reviews_table_edit');
	
	}
	
	
	/**
	 * �������� ��������� �������
	 * @param int $table_id
	 */
	function table_view($table_id = null)
	{

		$table = $this->validate->validate_table_by_id($table_id, false);
				
		$reviews = $this->validate->validate_reviews_by_id($table['reviews_id'], false);
				
		//�����
		$this->header_block->set_title('������ : �������� �������');
		
		$this->breadcrumbs_block->add("������ �������", DOMAIN.'/admin/reviews/');
		$this->breadcrumbs_block->add($reviews['name'], DOMAIN.'/admin/reviews/reviews_single/'.$reviews['id'].'/');
		$this->breadcrumbs_block->add("������ ������ ������", DOMAIN.'/admin/reviews_tables/'.intval($table['reviews_id']).'/');
		$this->breadcrumbs_block->add("�������� �������", null);
		
		$this->header_block->set();
		$this->breadcrumbs_block->set();
		
		$this->smarty->assign('table', $table);
		$this->smarty->display('admin/reviews/reviews_table_view');
		
	}
	
	
	function table_edit_post($table_id = null)
	{
		$table = $this->validate->validate_table_by_id($table_id);
	
		$data = $this->reviews_collector->table_editadd($table);
	
		if (!empty($data))
		{
			$this->model_common->update("reviews_tables", $data, array("id" => intval($table_id)));
			
			$this->my_history->add_to_history('reviews_tables','update', $table['reviews_id'], "��������� ������� &laquo;" . $data['description'] . "&raquo;");
		}
	
		My_Url_Helper::redirect(DOMAIN.'/admin/reviews_tables/'.$data['reviews_id'].'/');
		
	}
	
	
	/**
	 * �������� �������
	 * @param int $table_id
	 */
	function table_delete($table_id)
	{
		
		$table = $this->validate->validate_table_by_id($table_id);	
		
		$this->model_common->delete("reviews_tables", array("id" => intval($table_id)));
		
		$this->my_history->add_to_history('reviews_tables','delete', $table['reviews_id'], "������� ������� &laquo;" . $table['description'] . "&raquo;");
	
		My_Url_Helper::redirect($this->input->server('HTTP_REFERER'));
	
	}
	
	
}

?>