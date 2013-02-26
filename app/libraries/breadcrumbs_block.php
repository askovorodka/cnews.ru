<?php 
/**
 * ������� ������
 * @author ashmits by 10.12.2012 by 11:07
 *
 */
final class Breadcrumbs_Block
{
	
	private $data = array();
	private $result = array();
	private $CI = null;
	
	public function __construct()
	{
		$this->CI =& get_instance();
		$this->add("�������", DOMAIN.'/admin/');
	}
	
	/**
	 * ��������� �������� � ������� ������
	 */
	public function add($name, $url)
	{
		$this->data[] = array("name" => $name, "url" => $url);
	}
	
	/**
	 * ���������� ������ ������� ������
	 */
	public function get($key = null)
	{
		if (!empty($key))
		{
			if (isset($this->data[$key]))
			{
				//���������� ������������ �������
				return $this->data[$key];
			}
			else
			{
				return null;
			}
		}
		else
		{
			//���������� ��� ��������
			return $this->data;
		}
	}
	
	/**
	 * �������� ������ � ������
	 */
	public function set()
	{
		$this->CI->smarty->assign('breadcrumbs_block', $this);
	}
	
	
}

?>