<?php

/**
 * Авторизация в админке
 * @author ashmits by 24.12.2012 11:30
 *
 */
//error_reporting(E_ALL);
//ini_set('display_errors','On');

class Login extends Controller
{
	function __construct()
	{
		parent::Controller();
		
		$this->load->library(array('admin_menu','header_block', 'breadcrumbs_block','validate','reviews_collector', 'pagination','my_users'));
		$this->load->helper(array('my_auth','my_url'));
		$this->load->model(array('model_common'));
		
	}
	
	/**
	 * Форма автоизации
	 */
	function index()
	{
	
		if ($this->input->post('user_login') and $this->input->post('user_password'))
		{
			$data = $this->reviews_collector->user_login();
			if (!empty($data))
			{
				$user = $this->my_users->get_user_by_login_password($data['user_login'], $data['user_password']);
				if (empty($user))
				{
					$this->smarty->assign('login_error', "Ошибка авторизации. Неверный логин или пароль.");
				}
				else
				{
					$expire=0;
					if ($this->input->post('save') == 1)
					{
						//месяц
						$expire = time() + 60 * 60 * 24 * 31;
					}
					
					setcookie($this->config->item('admin_cookie_name'), $user['user_key'], $expire, "/", ".".$_SERVER['SERVER_NAME']);
					if (My_Url_Helper::get_from_url())
					{
						My_Url_Helper::redirect(My_Url_Helper::get_from_url());
					}
					else
					{
						My_Url_Helper::redirect(DOMAIN.'/admin/login/');
					}
				}
			}
		}
	
	
		//если авторизован, перекидываем на главную пользователей
		if ($this->my_users->get_active_admin_user())
		{
			My_Url_Helper::redirect(DOMAIN.'/admin/');
		}
		
		$this->header_block->set_title('Авторизация');
		$this->header_block->set();
	
		$this->smarty->display('admin/login/index');
	
	}
	
	
	/**
	 * Выход пользователя из админки
	 */
	public function logout()
	{
		$this->my_users->del_cookie($this->config->item('admin_cookie_name'));
		My_Url_Helper::redirect(DOMAIN.'/admin/login/');
	}
	
}

?>