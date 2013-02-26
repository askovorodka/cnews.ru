<?php 

/**
 * �����-�������
 * @author ashmits by 10.12.2012 12:15
 * 
 *
 */

class Reviews_Collector extends Validate
{
	
	private $data = array();
	protected $CI = null;
	
	public function __construct()
	{
		$this->CI =& get_instance();
	}
	
	/**
	 * ������ ������ ��� ����������/�������������� ������
	 * @return array
	 */
	public function articles_editadd($articles = null)
	{
		
		if (empty($articles['users_user_id']))
		{
			$this->users_user_id = self::set_active_user();
		}
		else
		{
			$this->users_user_id = $articles['users_user_id'];
		}
		
		$this->reviews_id = self::set_int($this->reviews_id, true, "����������� ID ������");
		$this->name = self::set_text($this->name, true, "������� �������� ������");
		$this->article_translit = self::set_translit($this->name);
		$this->small_text = self::set_text($this->small_text, true,"������� ������� �������� ������");
		$this->text = self::set_text($this->text, true, "������� ������ �������� ������");
		$this->image = self::set_image($this->image, true, "������� ������ �� �����������");
		$this->date = self::set_date($this->date);
		
		if ($this->CI->my_users->get_auth_user_group() != 'reviews_writer')
		{
			$this->article_status = self::set_int($this->article_status,true,"�� ������ ������ ������ ������");
		}
		
		return $this->data;
	}
	
	/**
	 * ������ ������ ��� ����������/�������������� ������
	 * @return array
	 */
	public function reviews_editadd($reviews = null)
	{
		//���� ������������ �� ���������, ��������� ��������� ������������ � ������ ������
		if (empty($reviews['users_user_id']))
		{
			$this->users_user_id = self::set_active_user();
		}
		else
		{
			$this->users_user_id = $reviews['users_user_id'];
		}
		
		$this->name = self::set_text($this->name, true, "������� �������� ������");
		$this->date = self::set_date($this->date);
		$this->type = self::set_int($this->type, true, "�� ������ ��� ������");
		
		$this->image = self::set_image($this->image);
		$this->banner_image = self::set_image($this->banner_image);
		$this->banner_url = self::set_text($this->banner_url);
		$this->text = self::set_text($this->text);
		$this->footer = self::set_text($this->footer);
		
		$this->translit = self::set_translit($this->name);
		$this->pre_release = self::set_text($this->pre_release);
		
		$this->banner_right_image = self::set_image($this->banner_right_image);
		$this->banner_right_url = self::set_text($this->banner_right_url);
		
		if ($this->CI->my_users->get_auth_user_group() != 'reviews_writer')
		{
			$this->review_status = self::set_int($this->review_status,true,"�� ������ ������ ���������� ������");
		}
		
		return $this->data;
	}
	
	/**
	 * ������ ������ ��� ����������/���������� ��������
	 */
	public function interview_editadd($interview = null)
	{
		if (empty($interview['users_user_id']))
		{
			$this->users_user_id = self::set_active_user();
		}
		else
		{
			$this->users_user_id = $interview['users_user_id'];
		}
		
		$this->reviews_id = self::set_int($this->reviews_id, true, "����������� ID ������");
		$this->date = self::set_date($this->date);
		$this->person = self::set_text($this->person, true, "������� �����������");
		$this->interview_translit = self::set_translit($this->person);
		$this->description = self::set_text($this->CI->input->post('description'), true, "������� ���������");
		$this->small_text = self::set_text($this->CI->input->post('small_text'), true, "������� ������� ��������");
		$this->text = self::set_text($this->CI->input->post('text'), true, "������� ������ ��������");
		$this->image = self::set_image($this->CI->input->post('image'), true, "������� ������ �� �����������");
		$this->logo = self::set_image($this->CI->input->post('logo'), true, "������� ������ �� ����������� �������� ������");
		$this->logo_url = self::set_text($this->CI->input->post('logo_url'), true, "������� ����� ������ � �������� ������");
		
		if ($this->CI->my_users->get_auth_user_group() != 'reviews_writer')
		{
			$this->interview_status = self::set_int($this->interview_status,true,"�� ������ ������ ��������");
		}
		
		return $this->data;
		
	}
	
	/**
	 * ������ ������ ��� ����������/���������� �����
	 */
	public function case_editadd($case = null)
	{
		if (empty($case['users_user_id']))
		{
			$this->users_user_id = self::set_active_user();
		}
		else
		{
			$this->users_user_id = $case['users_user_id'];
		}
		
		$this->reviews_id = self::set_int($this->reviews_id, true, "����������� ID ������");
		$this->date = self::set_date($this->date);
		$this->name = self::set_text($this->name, true, "������� �������� �����");
		$this->case_translit = self::set_translit($this->name);
		$this->small_text = self::set_text($this->small_text, true, "������� ������� ��������");
		$this->text = self::set_text($this->text, true, "������� ������ ��������");
		$this->image = self::set_image($this->image, true, "������� ������ �� �����������");
		$this->banner_image = self::set_image($this->banner_image, true, "������� ������ �� ����������� �������");
		$this->banner_url = self::set_text($this->banner_url, true, "������� ����� ������ �������");
		$this->banner_right_image = self::set_image($this->banner_right_image);
		$this->banner_right_url = self::set_text($this->banner_right_url);
		
		if ($this->CI->my_users->get_auth_user_group() != 'reviews_writer')
		{
			$this->case_status = self::set_int($this->case_status,true,"�� ������ ������ ����� ������");
		}
		
		
		return $this->data;
		
	}
	

	/**
	 * ������ ������ ��� ����������/���������� �������
	 * @param string $structure
	 */
	public function table_editadd($table = null, $structure = null)
	{
		
		if (empty($structure))
		{
			$structure = $this->structure;
			$structure = Common_Helper::set_structure_table($structure);
		}
		
		if (empty($table['users_user_id']))
		{
			$this->users_user_id = self::set_active_user();
		}
		else
		{
			$this->users_user_id = $table['users_user_id'];
		}
		
		$this->reviews_id = self::set_int($this->reviews_id, true, "����������� ID ������");
		if (!empty($table['date']))
		{
			$this->date = $table['date'];
		}
		else
		{
			$this->date = self::set_date($this->date);
		}
		
		$this->description = self::set_text($this->description);
		$this->structure = self::set_text($structure, true, "���� html �������");
		$this->source = self::set_text(nl2br($this->source));
		$this->rating = self::set_text(nl2br($this->rating));
		
		if ($this->CI->my_users->get_auth_user_group() != 'reviews_writer')
		{
			$this->table_status = self::set_int($this->table_status,true,"�� ������ ������ �������");
		}
		
		return $this->data;
	}
	
	/**
	 * ������ ������ ��� �������� ������ html �������
	 * @return multitype:
	 */
	public function generate_empty_table()
	{
		
		$this->rows = self::set_int($this->rows_count, true, "�� ������� ���������� ����� � �������");
		$this->cols = self::set_int($this->cols_count, true, "�� ������� ���������� ����� � �������");
		
		return $this->data;
		
	}
	
	
	public function user_login()
	{
		$this->user_login = self::set_text($this->user_login, true, "������� �����");
		$this->user_password = self::set_text($this->user_password, true, "������� ������");
		
		return $this->data;
	}
	
	/**
	 * ������ ������ ��� ����������/�������������� ������������
	 * @param int $current_user ID �������������� ������������
	 * @return array
	 */
	public function user_edit($current_user_id = null)
	{
		
		$this->group_name = self::set_admin_group($this->group_name, true, "�� ������ ID ������ �������������");
		$this->user_register_date = self::set_date($this->user_register_date);
		$this->user_login = self::set_text($this->user_login, true, "������� ����� ������������");
		$this->user_email = self::set_email($this->user_email, true, "������� email ������������");
		$this->user_name =  self::set_text($this->user_name, true, "������� �������� ������������");
		$this->user_password = self::set_password($this->user_password, true, "������� ������ ������������");
		$this->user_description = self::set_text($this->user_description);
		
		//�������� �� ������������� email � ����
		$email_exist = $this->CI->my_users->get_user_by_email($this->user_email, $current_user_id);
		if (!empty($email_exist))
		{
			show_error("������������ � ����� email ��� ���������������");
		}

		//�������� �� ������������� ������ � ����
		$login_exist = $this->CI->my_users->get_user_by_login($this->user_login, $current_user_id);
		if (!empty($login_exist))
		{
			show_error("������������ � ����� ������� ��� ���������������");
		}
		
		if (!$current_user_id)
		{
			$this->user_key = $this->CI->my_users->generate_userkey($this->user_login);
		}
		
		return $this->data;
		
	}
	
	/**
	 * ������ ������ ��� �������
	 * @param int $parent_id
	 * @param array $section
	 * @return multitype:
	 */
	public function section_editadd($section = null)
	{
		
		//$this->section_parent_id = self::set_int($parent_id, true, "�� ������ ������������ ������");
		$this->section_parent_id = self::set_int($this->parent, true, "�� ������ ������������ ������");
		$this->section_name = self::set_text($this->section_name, true, "������� �������� �������");
		
		if (!empty($section['section_create_date']))
		{
			$this->section_create_date = self::set_date($section['section_create_date']);
		}
		else {
			$this->section_create_date = self::set_date(null);
		}
		
		$this->section_description = self::set_text($this->section_description);
		
		return $this->data;
		
	}
	
	/**
	 * ������� ������ � ������
	 * @param unknown_type $name
	 * @param unknown_type $value
	 */
	public function __set($name, $value = null)
	{
		$this->data[$name] = $value;
	}
	
	/**
	 * ����� ������ �� �����, ���� �� ��������� ������ ����� � �����������...
	 * @param unknown_type $key
	 * @author ashmits by 18.12.2012 15:30
	 */
	public function __get($key)
	{
		return $this->CI->input->post($key);
	}
	
	
}

?>