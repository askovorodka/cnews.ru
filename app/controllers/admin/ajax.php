<?php 

/**
 * Контроллер ajax ответов сервера
 * @author ashmits by 25.12.2012
 *
 */
//error_reporting(E_ALL);
//ini_set('display_errors','On');

class Ajax extends Controller
{
	
	function __construct()
	{
		parent::Controller();
	}
	
	/**
	 * Прокся на курле,для выбора/загрузки файлов на удаленном сервере
	 * @author ashmits by 22.02.2013 12:11
	 */
	function get_remote_url()
	{
		
		if (preg_match("/\?url=(.*)/", $_SERVER['REQUEST_URI'], $matches))
		{
			$url = $matches[1];
			
			$post = $_POST;
			
			//если выбрана загрузка файла
			if (!empty($_FILES['file']))
			{
				
				$data = array();
				$file_name = $_FILES['file']['name'];
				if ($array = explode(".", $file_name))
				{
					$file_ext = $array[count($array)-1];
					//расширение файла
					$file_ext = strtolower($file_ext);
					//делаем рандомное имя файла
					$file_name = md5($file_name . time());
					//формируем новое название файла с полным path
					$full_path = sprintf("%s/files/proxy_tmp/%s.%s", (string)ROOT, (string)$file_name, (string)$file_ext);
					//грузим на прокси
					if ( @move_uploaded_file($_FILES['file']['tmp_name'], $full_path) )
					{
						$post = array_merge($post, array('file' => '@' . $full_path));
					}
				}
				
			}
			
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, (string)$url);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST , false);
			curl_setopt($curl, CURLOPT_USERPWD, "psycho:wE-[28!");
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			
			curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
			curl_setopt($curl, CURLOPT_VERBOSE, 1);
				
			curl_setopt($curl, CURLOPT_COOKIE, "proxy_domain=". DOMAIN ."/admin/ajax/get_remote_url/?url=;");
			
			$tuData = curl_exec($curl);
			
			print $tuData;
			
			//если был загружен файл для прокси, удаляем его
			if (!empty($full_path))
			{
				@unlink($full_path);
			}
				
		}
		
		
	}
	
	/**
	 * Поиск тегов по названию для выпадающего списка
	 */
	function search_tags($content_type = 'news')
	{
		
		$this->load->model(array('model_news','model_common','model_articles'));
		$this->load->library('validate');
		
		$query = $this->input->post('query');
		$query = iconv('utf-8', 'windows-1251', $query);
		$query = $this->validate->set_text($query);
		
		/**
		 * Анализируем тип контента и подключаем нужную модель
		 */
		switch($content_type)
		{
			case 'articles':
				$tags = $this->model_articles->search_tags_for_article((string)$query);
				break;
			
			default:
				$tags = $this->model_news->search_tags_for_news((string)$query);
				break;
		}
		
		if (empty($tags))
		{
			return "";
		}
		
		//перекодируем весь массив в UTF-8 для ajax ответа
		$tags = Common_Helper::assoc_array_decode('windows-1251','utf-8', $tags, 2);
		header("Content-type: text/json;charset=utf-8");
		echo json_encode($tags);
		
	}
	
	
	function reviews_generate()
	{
		
		if ($reviews_id = $this->input->post('reviews_id'))
		{
			$this->load->library(array('validate', 'reviews_views', 'renders'));
			$this->load->model(array('model_common', 'model_reviews'));
			if ($reviews = $this->validate->validate_reviews_by_id(intval($reviews_id)))
			{
				$reviews_structure = $this->reviews_views->get_reviews_structure(intval($reviews_id));
				
				$this->smarty->assign('review', $reviews);
				$this->smarty->assign('reviews_structure', $reviews_structure);
				$this->smarty->assign('only_content', true);
				$template = $this->smarty->fetch('front/reviews/reviews_preview');
				
				$review_render_result = (string)$template;
				
				$this->model_common->update("reviews", array("review_render_result" => $review_render_result), array("id" => intval($reviews_id)));
				
				//рендерим статьи обзора
				$this->renders->set_reviews_articles_renders($reviews_id);
				//рендерим интервью обзора
				$this->renders->set_reviews_interviews_renders($reviews_id);
				//рендерим кейсы обзора
				$this->renders->set_reviews_cases_renders($reviews_id);
				
				print "1";
			}
			else 
			{
				print "0";
			}
		}
		else
		{
			print "0";
		}
		
	}
	
	function get_table_source_code()
	{
		if ($table_id = $this->input->post('table_id'))
		{
			$this->load->library(array('tables_views'));
			$table = $this->tables_views->get_table_by_id(intval($table_id));
			if (!empty($table))
			{
				print json_encode(Common_Helper::assoc_array_decode('windows-1251','utf-8', $table));
			}
		}
	}
	
	/**
	 * Активный пользователь, для JS скриптов
	 */
	function get_active_user()
	{
		$this->load->library('my_users');
		$user = $this->my_users->get_active_admin_user();
		header("Content-type: text/json;charset=utf-8");
		if ($user)
		{
			print json_encode(Common_Helper::assoc_array_decode('windows-1251', 'utf-8', $user));
		}
	}
	
	/**
	 * Возвращает config для js
	 */
	function get_config()
	{
		
		$this->load->library('my_users');
		$user = $this->my_users->get_active_admin_user();
		header("Content-type: text/json;charset=utf-8");
		if (!empty($user))
		{
			$config =& get_config();
			$config = Common_Helper::assoc_array_decode('windows-1251', 'utf-8', $config);
			print json_encode($config);
		}
		
	}
	
	/**
	 * Возвращает хэш с полями и типом сортировки
	 * @author ashmits
	 */
	function get_hash_to_table()
	{
		header("Content-type: text/json; charset=windows-1251");
		if ($hash = $this->input->post('hash'))
		{
			
			$array = Common_Helper::hash_to_array($hash);
			if ($field = $this->input->post('sort_field'))
			{
				$array['sort_type'] = (empty($array['sort_type']) or $array['sort_type'] == 'desc' or empty($array['sort_field']) or $array['sort_field'] != $field) ? 'asc' : 'desc';
				$array['sort_field'] = $field;
				
			}
			
			$hash = Common_Helper::array_to_hash($array);
			print json_encode($hash);
		}
	}

	/**
	 * Возвращаем хэш с полем сортировки и типом сортировки
	 * по умолчанию ASC
	 * @author ashmits by 23.01.2013 12:14
	 * @return String
	 */
	function get_sort_hash()
	{
		header("Content-type: text/json; charset=windows-1251");
		if ($hash = $this->input->post('hash'))
		{
			$array = Common_Helper::hash_to_array((string)$hash);
			if ($field = $this->input->post('sort_field'))
			{
				$array['sort_type'] = (empty($array['sort_type']) or $array['sort_type'] == 'desc' or empty($array['sort_field']) or $array['sort_field'] != $field) ? 'asc' : 'desc';
				$array['sort_field'] = $field;
			}
			$hash = Common_Helper::array_to_hash($array);
			print json_encode($hash);
		}
	}

	/**
	 * Получаем хэш из поста
	 * @author ashmits by 07.02.2013 13:16
	 */
	function get_hash_from_post()
	{
		header("Content-type: text/json; charset=windows-1251");
		if (!empty($_POST))
		{
			$hash = Common_Helper::array_to_hash(Common_Helper::assoc_array_decode('utf-8','windows-1251',$_POST));
			print json_encode((string)$hash);
		}
	}
	
	
	
	/**
	 * Поиск пользователя по логину
	 * @author ashmits by 26.12.2012 12:43
	 * @param String $user_login
	 */
	function get_user_by_login()
	{
		
		header("Content-type: text/json; charset=utf-8");
		
		if ($user_login = $this->input->post('user_login'))
		{
			$this->load->library('my_users');
			$user = $this->my_users->get_user_by_login( $user_login );
			
			if (!empty($user))
			{
				print json_encode($user);
				return;
			}
			
		}
		
		print "false";
		return;
		
	}
	
	/**
	 * Поиск пользователя по email
	 * @author ashmits	by 26.12.2012 15:21
	 * 
	 */
	function get_user_by_email()
	{
		
		header("Content-type: text/json; charset=utf-8");
		
		if ($user_email = $this->input->post('user_email'))
		{
			$this->load->library('my_users');
			$user = $this->my_users->get_user_by_email( $user_email );
				
			if (!empty($user))
			{
				print json_encode($user);
				return;
			}
				
		}
		
		print "false";
		return;
		
	}
	
}



?>