<?php  if (!defined('BASEPATH')) exit('��� ������� � �������');

/**
 * 
 * @author ashmits
 * @date 05.12.2012 11:50
 * ����� ��������� ���� �������
 *
 */

final class Admin_menu{
	
	private static $CI = null;
	private $menu = null;

	
	public function __construct()
	{
		$this->CI = &get_instance();
		//������� ���� �� �������
		$this->menu = $this->CI->config->item('admin_menu');
		$this->CI->load->library('my_users');
		
	}
	
	
	private function get_menu()
	{
		return $this->menu;
	}
	
	
	/**
	 * �������� �������� ����
	 * @param String $active_section
	 */
	public function set_active_section($active_section)
	{
		
		if (!empty($active_section))
		{
			foreach ($this->menu as $key=>$val)
			{
				if ($val['section'] == $active_section)
				{
					$this->menu[$key] = array_merge($this->menu[$key], array("active" => true));
				}
				
				//������������ �������
				if (!empty($this->menu[$key]['children']))
				{
					foreach ($this->menu[$key]['children'] as $subkey=>$subval)
					{
						if ($subval['section'] == $active_section)
						{
							$this->menu[$key]['children'][$subkey] = array_merge($this->menu[$key]['children'][$subkey], array("active" => true));
						}
					}
				}
				
			}
		}
		
	}
	
	/**
	 * �������������� ���� ��� ������, �������� ����������� ��������
	 * @param Array $menu
	 * @param String $group_name
	 * @param int $i - ��������
	 */
	private function change_menu_for_group(&$menu, $group_name, $i=0)
	{

		if (empty($menu[$i]))
		{
			return;
		}
		
		elseif (!empty($menu[$i]['permission']))
		{
			if (is_array($menu[$i]['permission']))
			{
				if (!in_array($group_name, $menu[$i]['permission']))
				{
					unset($menu[$i]);
				}
			}
			elseif ($menu[$i]['permission'] != $group_name)
			{
				unset($menu[$i]);
			}
		}
		
		if (!empty($menu[$i]['children']))
		{
			$this->change_menu_for_group($menu[$i]['children'], $group_name);
		}
		
		return $this->change_menu_for_group($menu, $group_name,++$i);
		
	}
	
	
	/**
	 * 
	 */
	private function renderer_menu_by_group()
	{
		//$this->CI->load->library('my_users');
		$current_user_group = $this->CI->my_users->get_auth_user_group();
		//����������� ����� ����
		$this->change_menu_for_group($this->menu, $current_user_group);
	}
	
	/**
	 * �������� ���� � ������
	 */
	public function set()
	{
		
		$this->renderer_menu_by_group();
		
		$this->CI->smarty->assign('left_menu', $this->get_menu());
		$this->CI->smarty->assign('active_user', $this->CI->my_users->get_active_admin_user());
	}
	
}

?>