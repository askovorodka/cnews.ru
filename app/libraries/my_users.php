<?php 
/**
 *  ласс работы с пользовател€ми
 * @author ashmits by 18.12.2012 18:11
 *
 */
class My_Users
{
	private $data = null;
	private $CI = null;
	private $row = null;
	private $admin_cookie_name = "";
	public  $active_admin_user = null;
	
	public function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->helper(array('cookie','show_errors'));
		$this->CI->load->library('encrypt');
		$this->CI->load->model('model_common');
		//определ€ем им€ кук в админке
		$this->admin_cookie_name = $this->CI->config->item('admin_cookie_name');
	}

	function get_users_list()
	{
		$users = $this->CI->model_common->select("users", null, null, "user_name");
		return $users;
	}
	
	/**
	 * ƒобавление услови€ дл€ выборки материалов дл€ конкретного пользовател€ автора
	 * @return string|NULL
	 * @author ashmits by 24.12.2012 12:40
	 */
	function get_where_by_user_group()
	{
		//если авторизован как автор, то видит только свои новости, статьи, обзоры
		if (in_array($this->active_admin_user['group_name'], array('reviews_author','news_author', 'articles_author', 'reviews_writer', 'news_writer')))
		{
			return array("users_user_id" => (int) $this->active_admin_user['user_id']);
		}
		//админ и редактор вид€т все материалы
		elseif (in_array($this->active_admin_user['group_name'], array('admin','redactor')))
		{
			return null;
		}
	}
	
	
	/**
	 * ћетод определ€ет активного пользовател€ админки
	 * @author ashmits by 24.12.2012 10:57
	 * @return array
	 */
	function get_active_admin_user()
	{
		
		$user_key = get_cookie($this->admin_cookie_name);
		//если не авторизован, выбрасываем ошибку
		if (empty($user_key))
		{
			return false;
		}
		
		//если ранее уже загружен пользователь, передаем его
		if (!empty($this->active_admin_user))
		{
			if ($this->active_admin_user['user_key'] == $user_key)
			{
				return $this->active_admin_user;
			}
		}
		
		
		$this->active_admin_user = $this->get_user_by_key($user_key);
		
		if (empty($this->active_admin_user))
		{
			return false;
		}
		
		return $this->active_admin_user;
	}
	
	
	/**
	 * поиск пользовател€ по email
	 * @param string $user_email
	 * @param int $not_search_id ID игнорируемый
	 * @return array|NULL
	 */
	public function get_user_by_email($user_email, $not_search_id = null)
	{
		$user = $this->CI->model_common->select_one("users", array("user_email" => trim($user_email), "user_id !=" => intval($not_search_id)));
		
		if (!empty($user))
		{
			return $user;
		}
		else
		{
			return null;
		}
		
	}
	
	
	/**
	 * ѕоиск пользовател€ по логину
	 * @param int $user_login
	 * @param int $not_search_id ID игнорируемый 
	 * @return array|NULL
	 */
	public function get_user_by_login($user_login, $not_search_id = null)
	{

		$user = $this->CI->model_common->select_one("users", array("user_login" => trim($user_login), "user_id !=" => intval($not_search_id)));

		if (!empty($user))
		{
			return $user;
		}
		else
		{
			return null;
		}

	}
	
	
	/**
	 * проверка наличи€ прав доступа
	 * @param string $permission
	 * @return boolean
	 */
	public function check_permission($permission = null)
	{
		//если в контроллере не вызван предварительный метод авторизации, вызываем принудительно
		if (empty($this->active_admin_user))
		{
			//проверка авторизации пользовател€
			$this->get_active_admin_user();
		}
		
		if (is_array($permission))
		{
			if (in_array($this->get_auth_user_group(), $permission))
			{
				return true;
			}
		}
		elseif ($permission == $this->get_auth_user_group())
		{
			return true;
		}
		
		//show_error("ƒанный раздел вам не доступен");
		Show_Errors::show_permission_error("ƒанный раздел вам не доступен");
		
	}
	
	
	/**
	 * проверка авторизации пользовател€
	 * @author ashmits by 19.12.2012 13:50
	 * @return boolean
	 */
	public function is_user_auth($cookie_name)
	{
		if (empty($_COOKIE[$cookie_name]))
		{
			return false;
		}
		
		if (!$user_key = $_COOKIE[$cookie_name])
		{
			return false;
		}
		
		
		if (!($user = $this->get_user_by_key($user_key)))
		{
			return false;
		}
		
		return true;
		
	}
	

	public function del_cookie($cookie_name)
	{
		
		delete_cookie($cookie_name,".".$_SERVER['SERVER_NAME'],"/");
				
	}
	
	/**
	 * ѕоиск пользовател€ по логину и паролю
	 * @param String $login
	 * @param String $password
	 * @return NULL||array
	 * @author ashmits by 19.12.2012 12:10
	 */
	public function get_user_by_login_password($login=null,$password=null)
	{
		
		if (empty($login) or empty($password))
		{
			show_error("Ќет логина или парол€");
		}
		
		$this->data = $this->CI->model_common->select_one("users", array(
				"user_login" => $login,
				"user_password" => $this->CI->encrypt->sha1($password)));
		
		if (!empty($this->data))
		{
			return $this->data;
		}
		else
		{
			return null;
		}
		
	}
	
	public function is_user_permitted($section_name = null)
	{
		if (empty($section_name))
		{
			show_error("Ќе передано название раздела/модул€");
		}
	}
	
	
	public function __get($name)
	{
		if (!empty($this->data))
		{
			if (array_key_exists($name, $this->data))
			{
				return $this->data[$name];
			}
		}
	}
	
	/**
	 * √енераци€ ключа
	 * @param String $user_login
	 * @return Key String
	 */
	public function generate_userkey($user_login)
	{
		
		$this->CI->load->library('encrypt');
		$encrypt_key = $this->CI->config->item('encryption_key');
		$user_key = $this->CI->encrypt->encode($user_login);
		
		return $user_key;
		
	}
	
	/**
	 * ѕоиск пользовател€ по ключу в Ѕƒ
	 * @param Key String $user_key
	 */
	public function get_user_by_key($user_key)
	{
		
		$this->data = $this->CI->model_common->select_one("users", array("user_key" => $user_key));
		
		if (count($this->data))
			return $this->data;
		
		return null;
	}
	
	/**
	 * ќпредел€ем группу активного пользовател€
	 * @return NULL
	 */
	public function get_auth_user_group()
	{
		
		if (!isset($this->active_admin_user) or !isset($this->active_admin_user['group_name']))
		{
			$this->get_active_admin_user();
		}
		
		
		//if (isset($this->active_admin_user['group_name']))
		{
			return (string) $this->active_admin_user['group_name'];
		}
		
		return null;
		
	}
	
}

?>