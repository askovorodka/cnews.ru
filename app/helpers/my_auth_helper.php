<?php 

/**
 * 
 * @author ashmits by 05.12.2012 14:01
 * Хелпер авторизации
 *
 */

final class My_Auth_Helper
{

	/**
	 * Проверка авторизации в админке
	 * @return boolean
	 */
	public static function is_admin_auth()
	{
		if (!empty($_SERVER['PHP_AUTH_USER']) && !empty($_SERVER['PHP_AUTH_PW']))
		{
			if ($_SERVER['PHP_AUTH_USER'] == ADMIN_LOGIN && $_SERVER['PHP_AUTH_PW'] == ADMIN_PASSWORD)
			{
				return true;
			}
		}
		return self::get_admim_auth();
	}

	/**
	 * Окно авторизации
	 */
	private static function get_admim_auth()
	{
		header("Content-Type: text/html; charset=windows-1251");
		header('WWW-Authenticate: Basic realm="Enter login and password"');
		header('HTTP/1.0 401 Unauthorized');
		die('Ошибка авторизации');
	}
	
	/**
	 * Получение ID активного пользователя
	 * @return number
	 */
	public static function get_active_user_id()
	{
		$CI =& get_instance();
		$CI->load->library('my_users');
		if (empty($CI->my_users->active_admin_user['user_id']))
		{
			show_error("Нет активного пользователя");
		}
		return (int) $CI->my_users->active_admin_user['user_id'];
		
	}

}

?>