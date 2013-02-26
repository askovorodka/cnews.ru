<?php 
/**
 * 
 * @author ashmits by 05.12.2012 15:57
 * ����������� ������� � �������
 *
 */
class Reviews extends Controller
{
	
	function __construct()
	{
		
		parent::Controller();
		
		$this->load->library(array('admin_menu','header_block', 'breadcrumbs_block','validate','reviews_collector', 'pagination','my_users','reviews_views','my_history'));
		$this->load->helper(array('my_auth','my_url','my_entities'));
		$this->load->model(array('model_common','model_reviews'));
		
		//�������� ����������� ������������
		if (!$this->my_users->get_active_admin_user())
		{
			My_Url_Helper::redirect(DOMAIN.'/admin/login/?from=' . My_Url_Helper::get_current_url());
		}
		
		//�������� ���������� ������� � �������
		$this->my_users->check_permission(array('redactor','reviews_author', 'admin', 'reviews_writer','reviews_redactor'));
		
		//�������� ����� ���� ������
		$this->admin_menu->set_active_section('reviews');
		//�������� ���� � ������
		$this->admin_menu->set();
		
		//$this->output->enable_profiler(TRUE);
		
	}
	
	/**
	 * ������������ ������
	 * @author ashmits by 15.01.2013 15:56
	 * @param int $reviews_id
	 */
	function preview($reviews_id = null)
	{
		
		$review = $this->validate->validate_reviews_by_id(intval($reviews_id), false);
		
		$reviews_structure = $this->reviews_views->get_reviews_structure(intval($reviews_id));
		
		$this->smarty->assign('review', $review);
		
		$this->smarty->assign('reviews_structure', $reviews_structure);
		
		$this->smarty->display('front/reviews/reviews_preview');
		
	}
	
	function index()
	{
		$this->reviews_list();
	}
	
	/**
	 * ������� ������� � �������
	 */
	function reviews_list($hash = null, $page=0)
	{
		
		//�������� ����� � �����
		$this->header_block->set_title('������: �������');
		//�������� ������ ����� � ������������
		$this->header_block->set();
		
		//������� ������
		$this->breadcrumbs_block->add("������ �������", DOMAIN.'/reviews/');
		$this->breadcrumbs_block->set();

		if (empty($hash))
		{
			$hash = Common_Helper::array_to_hash(array(0));
		}
		
		$this->reviews_views->set_hash((string)$hash);
		$this->reviews_views->set_page((int)$page);
		$reviews = $this->reviews_views->get_admin_reviews_list();
		
		//$views_reviews = $this->reviews_views->get_reviews_list(intval($page));
		
		if (isset($reviews))
		{
			//��������
			$p_config = $this->config->item('paging');
			$p_config['base_url'] = DOMAIN.'/admin/reviews/reviews_list/'.$hash.'/';
			$p_config['total_rows'] = $reviews['total_rows'];
			$p_config['per_page'] = PER_PAGE_REVIEWS;
			$p_config['uri_segment'] = 5;
			
			$this->pagination->initialize($p_config);
			$paging = $this->pagination->create_links();
			
			//�������� ������ � ������
			$this->smarty->assign('reviews', $reviews['reviews']);
			$this->smarty->assign('paging', $paging);
			
		}
		
		$this->smarty->assign('hash', $hash);
		$this->smarty->assign('array', Common_Helper::hash_to_array($hash));
		
		$this->smarty->display('admin/reviews/reviews');
		
	}
	
	/**
	 * �������� ���������� ������
	 * @param unknown_type $reviews_id
	 */
	function reviews_single($reviews_id)
	{
		
		$reviews = $this->validate->validate_reviews_by_id($reviews_id);
		
		//������� ���� �� �������
		$content_types = $this->config->item('reviews_content_types');
		
		$headers = $this->reviews_views->get_headers_by_reviews($reviews_id);
		
		//��� ������ ������
		$articles = $this->reviews_views->get_reviews_articles($reviews_id);
		//��� �������� ������
		$interviews = $this->reviews_views->get_reviews_interviews($reviews_id);
		//��� ����� ������
		$cases = $this->reviews_views->get_reviews_cases($reviews_id);
		//��� ������� ������
		$tables = $this->reviews_views->get_tables_list_by_reviews($reviews_id);
		
		
		//�����
		$this->header_block->set_title('����� : ' . $reviews['name']);
		//������� ������
		$this->breadcrumbs_block->add("������ �������", DOMAIN.'/admin/reviews/');
		$this->breadcrumbs_block->add($reviews['name'], DOMAIN.'/admin/reviews/reviews_single/' . $reviews_id);
		
		$this->header_block->set();
		$this->breadcrumbs_block->set();
		
		$this->smarty->assign('reviews',$reviews);
		$this->smarty->assign('headers',$headers);
		$this->smarty->assign('articles',$articles);
		$this->smarty->assign('interviews',$interviews);
		$this->smarty->assign('cases',$cases);
		$this->smarty->assign('tables',$tables);
		
		$this->smarty->assign('content_types', $content_types);
		
		$this->smarty->display('admin/reviews/reviews_single');
		
	}
	
	/**
	 * ����������� ����� � ����������
	 * @author ashmits by 17.12.2012 12:33
	 * @param int $reviews_id
	 */
	function reviews_headers_types_checked($reviews_id)
	{
		
		$reviews = $this->validate->validate_reviews_by_id( $reviews_id );
		
		//�������� ����� ���������-������� ���
		//�������� ������
		$this->model_common->update("reviews_articles", array("reviews_headers_id" => 0), array("reviews_id" => $reviews_id));
		
		//��������
		$this->model_common->update("reviews_interviews", array("reviews_headers_id" => 0), array("reviews_id" => $reviews_id));
		
		//�����
		$this->model_common->update("reviews_cases", array("reviews_headers_id" => 0), array("reviews_id" => $reviews_id));
		
		//�������
		$this->model_common->update("reviews_tables", array("reviews_headers_id" => 0), array("reviews_id" => $reviews_id));
		
		//������������ ��� ��������� �� ��������
		foreach ($this->input->post('article') as $artkey=>$artval)
		{
			$header_id = intval($artkey);
			if (!empty($artval) and is_array($artval))
			{
				foreach($artval as $key=>$val)
				{
					$article_id = intval($key);
					$this->model_common->update("reviews_articles", array("reviews_headers_id" => $header_id), array("id" => $article_id));
				}
			}
		}
		
		//������������ ��������
		foreach ($this->input->post('interview') as $intkey=>$intval)
		{
			$header_id = intval($intkey);
			if (!empty($intval) and is_array($intval))
			{
				foreach($intval as $key=>$val)
				{
					$interview_id = intval($key);
					$this->model_common->update("reviews_interviews", array("reviews_headers_id" => $header_id), array("id" => $interview_id));
				}
			}
		}
		
		//�����
		foreach ($this->input->post('case') as $casekey=>$caseval)
		{
			$header_id = intval($casekey);
			if (!empty($caseval) and is_array($caseval))
			{
				foreach($caseval as $key=>$val)
				{
					$case_id = intval($key);
					$this->model_common->update("reviews_cases", array("reviews_headers_id" => $header_id), array("id" => $case_id));
				}
			}
		}
		
		//�������
		foreach ($this->input->post('table') as $tblkey=>$tblval)
		{
			$header_id = intval($tblkey);
			if (!empty($tblval) and is_array($tblval))
			{
				foreach($tblval as $key=>$val)
				{
					$table_id = intval($key);
					$this->model_common->update("reviews_tables", array("reviews_headers_id" => $header_id), array("id" => $table_id));
				}
			}
		}
		
		$this->my_history->add_to_history('reviews_headers','update', $reviews_id, "��������� ����� �������� � ���������� ���� � ������ &laquo;" . $reviews['name'] . "&raquo;");
		
		My_Url_Helper::redirect($this->input->server('HTTP_REFERER'));
		
	}
	
	
	/**
	 * ���������� ������
	 * @param unknown_type $reviews_type
	 */
	function reviews_add()
	{
		
		$this->header_block->set_title('������ : �������� ����� �����');
		$reviews_types = $this->config->item('reviews_types');
		
		//������� ������
		$this->breadcrumbs_block->add("������ �������", DOMAIN.'/admin/reviews/');
		$this->breadcrumbs_block->add("�������� ����� �����", DOMAIN.'/admin/reviews/add/');
		$this->breadcrumbs_block->set();
		
		$this->header_block->set();
		
		$this->smarty->assign('reviews_types', $reviews_types);
		$this->smarty->display('admin/reviews/reviews_add');
	}
	
	/**
	 * ���������� ������������ ������
	 */
	function reviews_add_post()
	{
		$data = $this->reviews_collector->reviews_editadd();
		if (!empty($data))
		{
			$id=$this->model_common->insert("reviews", $data);
			$this->my_history->add_to_history('reviews','insert', $id, "�������� ����� ����� &laquo;" . $data['name'] . "&raquo;");
		}
		//������������� �� �������������� ������
		My_Url_Helper::redirect(DOMAIN.'/admin/reviews/');
		
	}
	
	/**
	 * ���������� ����������� �������
	 * @param unknown_type $reviews_id
	 */
	function reviews_edit_post($reviews_id = null)
	{
		
		$reviews = $this->validate->validate_reviews_by_id($reviews_id);
		$data = $this->reviews_collector->reviews_editadd( $reviews );
		
		if (!empty($data))
		{
			
			$this->model_common->update("reviews", $data, array("id"=>intval($reviews_id)));
			$this->my_history->add_to_history('reviews','update', $reviews_id, "�������� ����� &laquo;" . $reviews['name'] . "&raquo;");
		}
		
		My_Url_Helper::redirect(DOMAIN.'/admin/reviews/');
		
	}
	
	
	
	/**
	 * ����� �������������� ������
	 * @author ashmits by 06.12.2012 18:17
	 * @param int $reviews_id
	 * 
	 */
	function reviews_edit($reviews_id=null)
	{
		
		$reviews = $this->validate->validate_reviews_by_id($reviews_id);
		
		$this->smarty->assign('reviews', $reviews);
		
		$reviews_types = $this->config->item('reviews_types');
		
		$this->header_block->set_title('������ : �������������� ������ ' . $reviews['name']);
		
		$this->header_block->set();
		
		//������� ������
		$this->breadcrumbs_block->add("������ �������", DOMAIN.'/admin/reviews/');
		$this->breadcrumbs_block->add($reviews['name'], DOMAIN.'/admin/reviews/reviews_single/' . $reviews_id . '/');
		$this->breadcrumbs_block->add("������������� ����� " . $reviews['name'], DOMAIN.'/admin/reviews/reviews_edit/' . intval($reviews_id));
		$this->breadcrumbs_block->set();
		
		//�������� ����� � ������������
		if (!empty($reviews))
		{
			$this->smarty->assign('reviews', $reviews);
		}
		
		$this->smarty->assign('reviews_types', $reviews_types);
		
		$this->smarty->display('admin/reviews/reviews_add');
	}
	
	/**
	 * �������� ������
	 * @param unknown_type $reviews_id
	 */
	function reviews_delete($reviews_id=null)
	{
		
		$reviews = $this->validate->validate_reviews_by_id($reviews_id);
		
		
		if (!empty($reviews_id))
		{
			//������� �������
			$this->model_common->delete("reviews_tables", array("reviews_id" => intval($reviews_id)));
			
			//������� ��� �������� ������
			$interviews = $this->reviews_views->get_reviews_interviews($reviews_id);
			foreach($interviews as $interview)
				//������� ��� ����� ��������-�������� (������� � ��)
				My_Entities_Helper::delete_by_entity_in($interview['id'], "reviews_interviews");
			//������� ��������
			$this->model_common->delete("reviews_interviews", array("reviews_id" => intval($reviews_id)));
			
			//������� ��� ����� ������
			$cases = $this->reviews_views->get_reviews_cases($reviews_id);
			foreach($cases as $case)
				//������� ��� ����� ����-�������� (������������ ������� � ��)
				My_Entities_Helper::delete_by_entity_in($case['id'], "reviews_cases");
			//������� �����
			$this->model_common->delete("reviews_cases", array("reviews_id" => intval($reviews_id)));
			
			//������� ��� ������ ������
			$articles = $this->reviews_views->get_reviews_articles($reviews_id);
			foreach($articles as $article)
				//������� ��� ����� ������-�������� (������� � ��)
				My_Entities_Helper::delete_by_entity_in($article['id'], "reviews_articles");
			//������� ������
			$this->model_common->delete("reviews_articles", array("reviews_id" => intval($reviews_id)));
			
			//������� ��������� ������
			$this->model_common->delete("reviews_headers", array("reviews_id" => intval($reviews_id)));
			//������� �����
			$this->model_common->delete("reviews", array("id" => intval($reviews_id)));
			$this->my_history->add_to_history('reviews','delete', $reviews_id, "������ ����� " . $reviews['name']);
		}
		
		//�������������� �� ���������� ��������
		My_Url_Helper::redirect($this->input->server('HTTP_REFERER'));
		
	}
	
	/**
	 * ��������� ��������� ����� ����������
	 * @param int $reviews_id ID ������
	 * @param int $header_id ID ���������
	 */
	function reviews_headers_types($reviews_id, $header_id)
	{

		$reviews = $this->validate->validate_reviews_by_id($reviews_id);
		$header = $this->validate->validate_header_by_id($header_id);
		
		if (!empty($_POST))
		{
			$this->model_common->update("reviews_headers", array("structure" => ""), array("id" => $header_id));
			if (!empty($_POST['types']))
			{
				$structure = implode(",",array_keys($_POST['types']));
				$this->model_common->update("reviews_headers", array("structure" => $structure), array("id" => $header_id));
				$this->my_history->add_to_history('reviews_headers','update', $reviews_id, "��������� ���� �������� ������ {$reviews['name']} � ��������� ���� &laquo;" . $header['name'] . "&raquo;");
			}
			
			My_Url_Helper::redirect($this->input->server('HTTP_REFERER'));
		}
		
		//������� ���������
		$header = $this->validate->validate_header_by_id($header_id);
		
		//�����
		$this->header_block->set_title('����� ' . $reviews['name'].' �������������� ��������� ���� ' . $header['name']);
		
		//���������� ������� ������
		$this->breadcrumbs_block->add("������ �������", DOMAIN.'/admin/reviews/');
		$this->breadcrumbs_block->add($reviews['name'], DOMAIN.'/admin/reviews/reviews_single/' . $reviews_id . '/');
		$this->breadcrumbs_block->add("������ ���������� ����", DOMAIN.'/admin/reviews/reviews_headers/' . intval($reviews_id) . '/');
		$this->breadcrumbs_block->add("���� �������� ��������� ���� " . $header['name'], DOMAIN.'/admin/reviews/reviews_headers_types/' . intval($reviews_id) . '/' . intval($header_id) . '/');
		
		
		$this->header_block->set();
		$this->breadcrumbs_block->set();
		
		$content_types = $this->config->item('reviews_content_types');
		
		//��������� ������ �����, ����� ������� ���������� ����
		if (!empty($header['structure']))
		{
			$floor = explode(",", $header['structure']);
			$new_array = array();
			foreach ($floor as $key=>$val)
			{
				$new_array[$floor[$key]] = $content_types[$floor[$key]];
			}
			
			foreach ($content_types as $key=>$val)
			{
				if (!array_key_exists($key, $new_array))
				{
					Common_Helper::array_push_associative($new_array, array($key=>$val));
				}
			}
			$content_types = $new_array;
			$this->smarty->assign('reviews_content_types', $content_types);
			$this->smarty->assign('floor', $floor);
		}
		
		$this->smarty->assign('reviews', $reviews);
		$this->smarty->assign('header', $header);
		$this->smarty->assign('reviews_content_types', $content_types);
		
		$this->smarty->display('admin/reviews/reviews_headers_types');
		
	}
	
	
	/**
	 * ��������� ������
	 */
	function reviews_headers($reviews_id=null)
	{
		
		$reviews = $this->validate->validate_reviews_by_id($reviews_id);
		
		//��������� ���������
		if ($name = $this->input->post('header_name'))
		{
			$max_sort = $this->model_reviews->get_max_sort_headers_by_reviews($reviews_id);
			$max_sort += 1;
			$id = $this->model_common->insert("reviews_headers", array("name" => $name, "reviews_id" => intval($reviews_id), "users_user_id" => $this->my_users->active_admin_user['user_id'], "sort" => $max_sort));
			//��������� � ������� ��������
			$this->my_history->add_to_history('reviews_headers','insert', $reviews_id, "�������� ��������� ���� &laquo;" . $name . "&raquo;");
			My_Url_Helper::redirect($this->input->server('HTTP_REFERER'));
		}
		
		//������� ��������� ������
		$where = array("reviews_id" => intval($reviews_id));
		$headers = $this->model_common->select("reviews_headers", $where, null, "sort asc");
		
		$this->header_block->set_title('����� ' . $reviews['name'].' ��������� ����');
		
		//���������� ������� ������
		$this->breadcrumbs_block->add("������ �������", DOMAIN.'/admin/reviews/');
		$this->breadcrumbs_block->add($reviews['name'], DOMAIN.'/admin/reviews/reviews_single/' . $reviews_id . '/');
		$this->breadcrumbs_block->add("������ ���������� ����", DOMAIN.'/admin/reviews/reviews_headers/' . intval($reviews_id) . '/');
		
		$this->header_block->set();
		$this->breadcrumbs_block->set();
		
		$this->smarty->assign('reviews', $reviews);
		$this->smarty->assign('headers', $headers);
		$this->smarty->display('admin/reviews/reviews_headers');
	}
	
	/**
	 * ���������� ����������
	 */
	function reviews_headers_save($reviews_id = null)
	{
		
		$reviews = $this->validate->validate_reviews_by_id($reviews_id);
		//�������� ��� ���������� � ���������� ������
		$this->model_reviews->set_reviews_headers_sort($reviews_id);
		
		if ($reviews_headers = $this->input->post('header'))
		{
			$sort_count=1;
			foreach($reviews_headers as $key=>$val)
			{
				$this->model_common->update("reviews_headers", array("name" => $val, "sort" => $sort_count), array("id" => intval($key)));
				$sort_count++;
			}
			
			$this->my_history->add_to_history('reviews_headers','update', $reviews_id, "�������������� ��������� ���� ������ &laquo;" . $reviews['name'] . "&raquo;");
		}
		
		My_Url_Helper::redirect($this->input->server('HTTP_REFERER'));
		
	}
	
	/**
	 * �������� ���������� ���������
	 * @param unknown_type $headers_id
	 */
	function reviews_headers_delete($headers_id=null)
	{
		
		$header = $this->validate->validate_header_by_id($headers_id);
		
		if (!empty($headers_id))
		{

			$where = array("id" => intval($headers_id));
			if ($this->my_users->get_where_by_user_group())
			{
				$where = array_merge($where, $this->my_users->get_where_by_user_group());
			}
			//������� ��������� �����
			$this->model_common->delete("reviews_headers", $where);
			$this->model_common->update("reviews_articles", array("reviews_headers_id" => null), array("reviews_headers_id" => (int)$headers_id));
			$this->model_common->update("reviews_interviews", array("reviews_headers_id" => null), array("reviews_headers_id" => (int)$headers_id));
			$this->model_common->update("reviews_cases", array("reviews_headers_id" => null), array("reviews_headers_id" => (int)$headers_id));
			$this->model_common->update("reviews_tables", array("reviews_headers_id" => null), array("reviews_headers_id" => (int)$headers_id));
			
			$this->my_history->add_to_history('reviews_headers','delete', $headers_id, "������ ��������� ���� &laquo;" . $header['name'] . "&raquo;");
		}
		My_Url_Helper::redirect($this->input->server('HTTP_REFERER'));
	}
	
}


?>