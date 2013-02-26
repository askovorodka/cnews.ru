<?php
class Starter extends Controller {
	
	var $championat_stop_date = "2010-05-17"; //2010-04-26
	
	
	function Starter()
	{
		
		parent::Controller();
		
		
		//$this->db_cnews = $this->load->database('default', TRUE);
		$this->db = $this->load->database('default', TRUE);
		
		
		//Слудующие заголовки - это запрет кеширования.
		Header("Expires: Thu, 19 Feb 1998 13:24:18 GMT");
		Header("Last-Modified: ". gmdate("D, d M Y H:i:s") . " GMT");
		Header("Cache-Control: no-cache, must-revalidate");
		Header("Cache-Control: post-check=0,pre-check=0");
		Header("Cache-Control: max-age=1");
		Header("Pragma: no-cache");
		
	    ini_set('session.gc_maxlifetime', 1209600);
	    ini_set('session.cookie_lifetime', 1209600);		
		
		session_start();
		
		//$this->smarty->assign("include_center", 'center.tpl');
		$this->smarty->assign("main_window_name", 'Ошибка');
		
		// Библиотеки
		
		$this->load->library('validation');
		$this->load->library('image_lib');
		$this->load->library('upload');
		$this->load->library('upload');
		
		// Основные модели
		$this->load->model('model_cnews_news');
		$this->load->model('model_cnews_pub');
		$this->load->model('model_glossary');
		$this->load->model('model_articles');
		$this->load->model('model_user');



		// Подключаем дополнительные библиотеки
		$this->load->helper('smiley');
		$this->load->library('table');	
		
		//Авторизация
		if (!empty($_COOKIE['user_auth_code']) and empty($_SESSION['user']))
		{
			$user = $this->model_user->_select(array('secret_code' => $_COOKIE['user_auth_code']));
			if (!empty($user)) $_SESSION['user'] = $user[0];
			 else setcookie("user_auth_code");
		}
		
		$this->_user_enter_auth();
		
		// Передаем случайное число
		$this->smarty->assign("rand", rand(0, 2));
		
		// Обрабатываем постовые данные, на наличие вредоносного кода
		foreach ($_POST as &$val)
		{
			if (!is_array($val))
			{ 
				$val = htmlspecialchars($val, ENT_QUOTES);
				$val = trim(addslashes($val));
			}
			else
			{
				foreach ($val as &$val2)
				{
					if (!is_array($val2) and substr_count($val2, "<xjxquery>") == 0)
					{ 
						$val2 = htmlspecialchars($val2, ENT_QUOTES);
						$val2 = trim(addslashes($val2));
					}
					// xajax передает данные в качестве постовых, поэтому эти данные защищать
					// htmlspecialchars нельзя. Тогда все ломается. Но мы можем определить что данные
					// переданы xajax и защитить их addslashes
					elseif (!is_array($val2) and substr_count($val2, "<xjxquery>"))
					{	
						$val2 = trim(addslashes($val2));
					}
				}
			}
		}
		
		// Осталось дней до конца
		$csdate = explode("-", $this->championat_stop_date);
		$days_to_stop = ceil((mktime(23, 59, 59, $csdate[1], $csdate[2], $csdate[0]) - time())/60/60/24);
		$this->smarty->assign("days_to_stop", $days_to_stop);
		$this->smarty->assign("days_to_stop_str", $this->_declension($days_to_stop));		
		
		// Инициация XAJAX
		$this->load->library('xajax');
		$this->xajax->registerFunction(array("xmain_rotator", &$this, "xmain_rotator"));
		$this->xajax->registerFunction(array("xuser_avatar_change", &$this, "xuser_avatar_change"));	
		$this->xajax->registerFunction(array("xcomment_mark", &$this, "xcomment_mark"));
		$this->xajax->registerFunction(array("xcomment_delete", &$this, "xcomment_delete"));
				
		$this->xajax->processRequests();
		$this->xajax->waitCursorOff();
     	$xajax_javascript = $this->xajax->getJavascript(
     			"/inc/js/xajax/", 'xajax.js'
     		);
		$this->smarty->assign("xajax_javascript", $xajax_javascript);

		// Интервью
		//$interview = $this->model_articles->_select(array('type_id' => 'interview', 'status' => 1), '*', 'id desc', 1);
		if (!empty($interview))
		{
			$this->smarty->assign('interview', $interview[0]);
		}
	
	
		//переносим константы в шаблонизатор, для более удобного вызова
		if (defined(DOMAIN))
		{
			$this->smarty->assign('DOMAIN', DOMAIN);
		}
		
	}
	
	
	
	function _text_decode($text)
	{
		if (!is_array($text))
		{ 
			$text = htmlspecialchars_decode($text, ENT_QUOTES);
		}
		else
		{
			
			foreach ($text as &$val)
			{
				if (!is_array($val))
				{
					$val = htmlspecialchars_decode($val, ENT_QUOTES);
				}
			}
		}
		
		return $text;
	}
	

	//=====================================================
	// АВТОРИЗАЦИЯ
	//=====================================================
	function _authorization()
	{
		if (empty($_POST['auth_login'])) 
		{
			$this->smarty->assign("UserMessageAuth", "Вы не указали email.");
			return 1;
		}
		
		$result = $this->_check_user($_POST['auth_login'], $_POST['auth_pass']);
		
		if (empty($result))
		{
			$this->smarty->assign("UserMessageAuth", "Неверен email или пароль.");
			return 1;
		}
		elseif ($result == "blocked") 
		{
			$this->smarty->assign("UserMessageAuth", "Ваш аккаунт заблокирован администратором.");
			return 1;
		}
		elseif ($result == "norequst") 
		{
			$this->smarty->assign("UserMessageAuth", "Регистрация не подтверждена.");
			return 1;
		}
		
		// Записываем КУКИ
		if (!empty($_POST['remember_me'])) 
		{
			setcookie('user_auth_code', $result['secret_code'], time() + 432000, '/');
		}
		
		$_SESSION['user'] = $result;

		$this->smarty->assign('user_info', $result);
		
		header("Location: /user/byid/".$_SESSION['user']['id']);
		exit;
	}
	
	function exit_from_site()
	{
		$_SESSION = array();
		setcookie('user_auth_code', '', time() + 432000, '/');
		$_COOKIE = array();
		header('Location: /');
	}
		
	// Проверка, есть ли пользователь хранящийся в сессиях в нашей 
	// БД и как у него с правами. Если все ОК, то обновляем информацию о нем.
	// Иначе очищаем сессии.
	function _user_enter_auth()
	{
		// Авторизация пользователя
		if (!empty($_SESSION['user']))
		{
			// Проверяем сессии на наличие ложной информации и обновляем их
			$user_info = $this->_check_user(
					$_SESSION['user']['email'], $_SESSION['user']['pass'], 0
				);
			if (empty($user_info) or $user_info['blocked']) $_SESSION['user'] = array();
			else
			{			
				$this->model_user->_update(
					$user_info['id'], 
					array('last_visit_ip' => $this->_get_real_ip(), "last_visit" => date("Y-m-d H:i:s"))
				);
				$_SESSION['user'] = $user_info;
				// Передаем информацию о пользователе в Smarty
				$this->smarty->assign('user_info', $_SESSION['user']);
			}
		} 

	}
	
	// Функция проверяет, существует ли пользователь с таким логином и паролем
	// если нет, то возвращается 0, иначе вся информация о пользователе
	function _check_user($email, $pass, $nomd5 = 1)
	{
		if ($nomd5 == 1) $pass = md5(strtolower(trim($pass)));
		$user = $this->model_user->_select(array('email' => $email, "pass" => $pass));
		if (empty($user[0])) return 0;
		if ($user[0]['blocked'] == 1) return "blocked";
		//if ($user[0]['reg_ok'] == 0) return "norequst";
		return $user[0];
	}

	//======================================================
	// ФУНКЦИИ ВАЖНЫЕ ДЛЯ РАБОТЫ САЙТА
	//======================================================
	// Функция разбивает слишком длинные слова на части
	function _split_text($s, $len = 10) 
	{ 
		$marker = "\x01";
		$hyp = " ";
		
		$wordmaxlen = $len;
		
		// Сохраняем все тэги чтобы уберечь их от разбивки
		preg_match_all('/(<.*?\>)/si',$s,$tags);
		
		// Заменяем все тэги на маркеры
		$s =  preg_replace('/(<.*?\>)/si', $marker, $s);
		
		// Разбиваем текст на слова
		$words = split(' ',$s);
		
		for ($i=0; $i<count($words); $i++)
		{
			// Каждое слово >= $wordmaxlen разбиваем
		  if (strlen($words[$i])>=$wordmaxlen)
		    $words[$i] = chunk_split($words[$i],$wordmaxlen,$hyp);
		    
		}
		
		// Собираем строку из уже разбитых на части слов
		$s = implode(' ',$words);
		
		// Восстанавливаем тэги, места которых были отмечены маркерами
		for ($i=0; $i<count($tags[1]); $i++)
			$s =  preg_replace("/$marker/si", $tags[1][$i], $s, 1);
		
		return $s;
	}

	function _text_format($word, $fl) 
	{
		$up_letter   = 'АБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ';
		$down_letter = 'абвгдеёжзийклмнопрстуфхцчшщъыьэюя';
		if($fl == '1') return trim(strtr($word, $down_letter, $up_letter));
	 		return trim(strtr($word, $up_letter, $down_letter));
	}	
	
	function _text_validation($text = "")
	{	
		$text[0] = strtoupper($this->_text_format($text[0], 1));
		
		$text = trim($text);
		foreach (array(";", ":", ",", ".", "!", "?") as $val)
		{
			$text = str_replace(" $val ", "$val ", $text);
		}

		foreach (array(",", ".") as $val)
		{
			$text = str_replace(" $val", "$val ", $text);
		}		
		
		// Заменяем ' на `
		$text = str_replace("'", "`", $text);
		
		// Замена двух точек на троеточие
		$text = str_replace("..", "...", $text);
		
		// Обрезаем все символы повторяющиеся более 3х раз до 3х повторений
		preg_match_all("/[?!\.,]{3,}/is", $text, $match);
		
		foreach ($match[0] as $key => $val)
		{
			$text = str_replace($val, substr($val, 0, 3), $text);
		}
		
		// Раставляем пробелы после всех знаков припинания
		// Если это не , : ; то поднимаем последующую букву в верхний регистр 
		$match = "";
		preg_match_all("/([а-яеА-Я])(\.|!|\?|,|:|;|\.\.\.)([а-яеА-Я])/is", $text, $match);
		if (!empty($match))
		{
			foreach ($match[0] as $key => $val)
			{
				$flatter = $match[1][$key];
				$sumb = $match[2][$key];
				$slatter = $match[3][$key];
				
				if ($match[0][$key] == "т.д" or $match[0][$key] == "т.п") continue;
				
				if ($sumb != "," and $sumb != ":" and $sumb != ";")
				{
					$slatter = strtoupper($this->_text_format($slatter, 1));
				}
				
				$text = str_replace($match[0][$key], $flatter.$sumb." ".$slatter, $text);
			}
		}
		
		// Расстановка тире
		// Заменяем короткие тире (–)
		$text = str_replace("–", "—", $text);
		// Заменяем дефис, окружённый пробелами ( - ); 
		$text = str_replace(" - ", " — ", $text);
		$text = str_replace(" -", " — ", $text);
		$text = str_replace("--", "—", $text);
		// Дефис в начале строки (прямая речь); 
		if ($text[0] == "-") $text[0] = "—";
		$text = str_replace("\n- ", "\n—", $text);
		$text = str_replace("\n - ", "\n—", $text);
		$text = str_replace("\n\t- ", "\n\t—", $text);
			
		// Делаем пробельный отступ для смайликов из массива смайликов
		return  $text;
	}	
	
	//Преобразование русских букв в транслитные
	function rus_to_translit($s){
		$s=trim(strtolower($s));  
		$s = preg_replace("/[^а-яА-Яa-zA-Z0-9 +]/ies", '', $s);
		$s=str_replace("а", "a",$s); $s=str_replace("б", "b",$s);
		$s=str_replace("в", "v",$s); $s=str_replace("г", "g",$s);
		$s=str_replace("д", "d",$s); $s=str_replace("е", "e",$s);
		$s=str_replace("ё", "yo",$s); $s=str_replace("ж", "zh",$s);
		$s=str_replace("з", "z",$s); $s=str_replace("и", "i",$s);
		$s=str_replace("й", "j",$s); $s=str_replace("к", "k",$s);
		$s=str_replace("л", "l",$s); $s=str_replace("м", "m",$s);
		$s=str_replace("н", "n",$s); $s=str_replace("о", "o",$s); 
		$s=str_replace("п", "p",$s); $s=str_replace("р", "r",$s);
		$s=str_replace("с", "s",$s); $s=str_replace("т", "t",$s);
		$s=str_replace("у", "u",$s); $s=str_replace("ф", "f",$s);
		$s=str_replace("х", "h",$s); $s=str_replace("ц", "ts",$s);
		$s=str_replace("ч", "ch",$s); $s=str_replace("ш", "sh",$s); 
		$s=str_replace("щ", "shch",$s); $s=str_replace("ъ", "",$s); 
		$s=str_replace("ы", "y",$s); $s=str_replace("ь", "",$s); 
		$s=str_replace("э", "e",$s); $s=str_replace("ю", "yu",$s); 
		$s=str_replace("я", "ya",$s); $s=str_replace(" ", "_",$s); 
			
		$s=str_replace("А", "a",$s); $s=str_replace("Б", "b",$s);
		$s=str_replace("В", "v",$s); $s=str_replace("Г", "g",$s);
		$s=str_replace("Д", "d",$s); $s=str_replace("Е", "e",$s);
		$s=str_replace("Ё", "yo",$s); $s=str_replace("Ж", "zh",$s);
		$s=str_replace("З", "z",$s); $s=str_replace("И", "i",$s);
		$s=str_replace("Й", "j",$s); $s=str_replace("К", "k",$s);
		$s=str_replace("Л", "l",$s); $s=str_replace("М", "m",$s);
		$s=str_replace("Н", "n",$s); $s=str_replace("О", "o",$s); 
		$s=str_replace("П", "p",$s); $s=str_replace("Р", "r",$s);
		$s=str_replace("С", "s",$s); $s=str_replace("Т", "t",$s);
		$s=str_replace("У", "u",$s); $s=str_replace("Ф", "f",$s);
		$s=str_replace("Х", "h",$s); $s=str_replace("Ц", "ts",$s);
		$s=str_replace("Ч", "ch",$s); $s=str_replace("Ш", "sh",$s); 
		$s=str_replace("Щ", "shch",$s); $s=str_replace("Ъ", "",$s); 
		$s=str_replace("Ы", "y",$s); $s=str_replace("Ь", "",$s); 
		$s=str_replace("Э", "e",$s); $s=str_replace("Ю", "yu",$s); 
		$s=str_replace("Я", "ya",$s);
		
		return $s;
	}
	
	// Функция формирует массив содержащий группу гиперссылок
	// на страницы: << -1- -2- >>
	// $n_this_page - номер текущей страницы
	// $n_pages - всего страниц
	// $controller_name - имя контроллера (для создания гиперссылки)
	function _drop_to_page($n_this_page, $n_pages, $controller_name, $page_name = '')
	{
		if ($n_pages <= 1) return 0;
	
		$d = 5; // Интервал ссылок
		$start = 1;
		$stop = 2*$d;
		
		if (($n_this_page - $d) > 1)
		{
			$start = $n_this_page - $d;
			$stop = $n_this_page + $d;
		}
	
		if ($stop > $n_pages)
		{
			$stop = $n_pages;
			$start = $n_pages - 2*$d;
		}
	
		if ($start < 1) $start = 1;
	
		// Переход на первую страницу
		if ($n_this_page > 1)
		{
			$path = "/".$controller_name."1".$page_name;
			$array_link[] = "<a href='$path' class='rew'>Начало</a> ";
		}
		
		// Вывод ссылок начиная со $start и заканчивая $stop
		for ($i = $start; $i <= $stop; $i++)
		{
				$path = "/".$controller_name.$i.$page_name;
				if ($i != $n_this_page)
				{
					$array_link[] = "<a href='$path'> $i </a>";
				}	
			 	else $array_link[] = "$i ";
		}
		
		// Переход на последнюю страницу
		if ($n_this_page < $n_pages)
		{
			$path = "/".$controller_name.$n_pages.$page_name;
			$array_link[] = " <a href='$path' class='ff'>Конец</a>";
		}			
	
		return $array_link;
	}
	
	/*
	* Функция отправки электронных сообщений,
	* поддерживает как текстовый вид так и html
	* Параметры:
	* $from - почтовый адрес отправителя
	* $to - почтовый адрес получателя
	* $subject - тема письма
	* $html_text - текст html сообщения
	* $text - текстовае сообщение
	*/
	function _send_email($from, $to, $subject, $html_text, $text = '')
	{
		$this->load->library('email');
	
		$config['mailtype'] = 'html';
		$config['priority'] = '2';
		$config['charset'] = "Windows-1251";
		$this->email->initialize($config);

		$this->email->from($from, $from);
		$this->email->to($to);
		$this->email->subject($subject);
		$this->email->message($html_text);
		
		$this->email->send();
	}

	/*
	* Функция образает слишком длинные аннотации к статьям или товарам
	* заменяя концовку аннотации "..."
	* Параметры:
	* $annotation - аннотация, которую требуется образать
	* $len - длина аннотации, которую требуется получить
	* Возвращаемое значение - укороченная аннотация.
	*/
	function _cut_long_annotation($annotation, $len)
	{
		//Обрезаем слишком длинные описания
		if (strlen($annotation)>$len and !empty($annotation))
		{		
			// Найдем намер следующего пробела, в описании после превышения
			// len_description. Чтобы не обрезать слово на середине.
			$annotation=stripslashes($annotation);
			$pos = strpos($annotation, ' ', $len);
			// Бывает что в предложении ровно столько букв сколько указано в $len
			// или чуть больше. Но пробелов после $len нет. Тогда $pos может равняться пустоте.
			// Исправим ситуацию:
			if (empty($pos) and $len > 10) $pos = strpos($annotation, ' ', $len - 10);
			$annotation = substr_replace($annotation, '', $pos)."...";		
			//На случай, если в конец попала запятая или точка ('дом,...' или 'дом....')
			$annotation = str_replace(',...', '...', $annotation);
			$annotation = str_replace('......', '...', $annotation);
		}
		return $annotation;
	}
	
	// Функция в качестве аргумента принимает число секунд с
	// последнего посещения пользователя и возвращает комментарий
	// в виде текстовой строки
	function _last_visit_to_str($sec)
	{	
		$minutes = round($sec/60);
		if ($minutes <= 60)
		{
			return $minutes." ".$this->_declension($minutes, array("минута", "минуты", "минут"));
		}

		$hourse = round($minutes/60);
		if ($hourse <= 24)
		{
			return $hourse." ".$this->_declension($hourse, array("час", "часа", "часов"));
		}		

		$days = round($hourse/24);
		return $days." ".$this->_declension($days);
	}
	
	function _declension($n,$string=array('день','дня','дней'))
	{
	    $n = abs($n) % 100;
	    $n1 = $n % 10;
	    if ($n > 10 && $n < 20) return $string[2];
	    if ($n1 > 1 && $n1 < 5) return $string[1];
	    if ($n1 == 1) return $string[0];
	    return $string[2];
	}
	
	
	function _days_to_str($days)
	{	
		return $days." ".$this->_declension($days);
	}	
	
	// Если строка $date_str содержит в себе номер месяца обрамленный в символ -, 
	// то функция заменяет его на русскоязычное название месяца.
	// К примеру, нужно вывести на экран дату 1 мая 2009 года. 
	// Получаем дату из базы в виде 1 -5- 2009 и обрабатываем этой функцией
	function _convert_month_in_str($date_str)
	{
		$date_array = array("-01-" => "января", "-02-" => "февраля", "-03-" => "марта", "-04-" => "апреля", "-05-" => "мая", "-06- "=> "июня", "-07-" => "июля", "-08-" => "августа", "-09-" => "сентября", "-10-" => "октября", "-11-" => "ноября", "-12-" => "декабря");

		return strtr($date_str, $date_array);
	}
	
	
	function city()
	{
		                
		$file_handle = fopen("inc/city.txt", "r");
		while (!feof($file_handle)) {
		   $line = fgets($file_handle);
		   @list($city, $region) = explode("\t", $line);
		   $region = trim($region);
		   $city = trim($city);
		   $res = $this->model_city->_select(array("name" => $region));
		   if (!empty($res))
		   {
		   		$this->model_city->_insert(array("name" => $city, "parent_id" => $res[0]['id']));
		   }
		   
		}
		fclose($file_handle);
	}
	
	function _image_change($upload_path, $file_name, $width, $height, $crop = 0, $folder = 1)
	{
		$folder_name = "";
		if (is_numeric($width) and is_numeric($height) and $folder) 
		{
			$folder_name = $width."x".$height."/";
		}
		$config['image_library'] = 'GD2';
		$config['source_image'] = $upload_path."/".$file_name;
		$config['new_image'] = $upload_path."/".$folder_name.$file_name;
		@mkdir($upload_path."/".$folder_name, 0777);		
		$config['width'] = $width;
		$config['height'] = $height;
		if ($crop) $config['quality'] = 100;
		if ($crop) $config['prop_type'] = "";
			else $config['prop_type'] = "width";
		$this->image_lib->initialize($config); 
		
		$this->image_lib->resize();
		
		$this->error_str .= $this->upload->display_errors();
		
		$this->image_lib->clear();	

		if (!empty($crop))	
		{
			$config['image_library'] = 'GD2';
			$config['source_image'] = $upload_path."/".$folder_name.$file_name;
			$config['width'] = $width;
			$config['height'] = $height;
			$this->image_lib->initialize($config); 		
			//print_r ($this->image_lib); exit;	
			$this->image_lib->crop();
			
			$this->error_str .= $this->upload->display_errors();
			
			$this->image_lib->clear();		
		}			
	}			

	function _show_error($message = "")
	{
			$message = $message."<br /><a href='/'>Перейти на главную страницу</a>";
			$this->smarty->assign("Error", $message);
			print $message;
			//$this->smarty->assign("include_center", "show_error.tpl");
			//$this->smarty->display("index.tpl");						
			exit;				
	}
	
	// Конвертирует дату вида 2009-09-16 00:05:42 в  16 октября 2009 в 00:05
	function _date_convert($date)
	{
	    $full_date = explode(" ", $date);
	    $date = explode("-", $full_date[0]);
	    if ($date[0] == "0000") return 0; // Пустая дата
	    
	    $months = array(
	        "01" => "января",
    	    "02" => "февраля",
    	    "03" => "марта",
    	    "04" => "апреля",
    	    "05" => "мая",
    	    "06" => "июня",
    	    "07" => "июля",
    	    "08" => "августа",
    	    "09" => "сентября",
    	    "10" => "октября",
    	    "11" => "ноября",
    	    "12" => "декабря",
	    );
	    
	    $result = $date[2]." ".$months[$date[1]]." ".$date[0];
	    
	    if (!empty($full_date[1]))
	    {
	    	$time = explode(":", $full_date[1]);
	   		$result .= " в ".$time[0].":".$time[1];
	    }
	    
	    return $result;
	}
	
	// Преобразует дату вида 2010-09-16 00:05:42 в 16/09/10
	function _date_simple_convert($date = "")
	{
		if (substr_count($date, " "))
		{
			$date = explode(" ", $date);
			$date = $date[0];
		}
		$date = explode("-", $date);
		return $date[2]."/".$date[1]."/".substr($date[0], 2);
	}
	
	// Функция возвращает сколько дней/часов осталось до указанной даты
	function _time_stay($date)
	{
	    //2009-12-31 21:08:00
	    $y = substr($date, 0, 4);
	    $m = substr($date, 5, 2);
	    $d =substr($date, 8, 2);
	    $h =substr($date, 11, 2);
	    $min =substr($date, 14, 2);
	    $res_h = floor((mktime($h, $min, 0, $m, $d, $y) - time())/60/60); // Всего часов до момента
	   // if ($res_h <=0) return false;
	    $res_d = abs(($res_h - $res_h%24)/24); // Полных дней до момента
	    $res_h = abs($res_h%24); // Полных часов до момента
	    
	    return array(
		    	"d" => round($res_d), 
		    	"d_str" => $this->_declension($res_d),
		    	"h" => round($res_h), 
		    	"h_str" => $this->_declension($res_h, array('час', 'часа', 'часов'))
	    	);
	}

	// Функция возвращает ip пользователя
	function _get_real_ip()
	{
		 if (!empty($_SERVER['HTTP_CLIENT_IP'])) 
		 {
		   $ip=$_SERVER['HTTP_CLIENT_IP'];
		 }
		 elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
		 {
		  $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
		 }
		 else
		 {
		   $ip=$_SERVER['REMOTE_ADDR'];
		 }
		 return $ip;
	}
	
	//=======================================================================
	// XAJAX
	//=======================================================================
	function _xcomment_mark($post_id = "", $mark = "")
	{
		$objResponse = new xajaxResponse();        
		$objResponse->setCharEncoding('windows-1251'); 
		
		if (empty($_SESSION['user'])) return $objResponse;
		if (!is_numeric($post_id)) return $objResponse;
		
		$post = $this->model_post->_select(array("id" => $post_id));
		
		if (empty($post[0])) return $objResponse;
		
		if ($post[0]['user_id'] == $_SESSION['user']['id'])
		{
			$objResponse->addAssign("span_vote_{$post_id}", "innerHTML", " <font style='color: #888;'>(Это ваш комменатрий)</font> ");
			return $objResponse;			
		}
		
		$user_vote = $this->model_vote->_select(array(
				"user_id" => $_SESSION['user']['id'], "post_id" => $post_id
			));
					
		if (!empty($user_vote[0])) return $objResponse;
		
		$this->model_vote->_insert(array(
				"user_id" => $_SESSION['user']['id'], "post_id" => $post_id, "approve" => $mark
			));	
		
		$count = $this->model_vote->_select(
			array("post_id" => $post_id, "approve" => $mark), "count(*) as count"
		);			
		if ($mark == "-1")
		{
			$this->model_post->_update($post_id, array("negative_votes_cnt" => $count[0]['count']));
		}
		else
		{
			$this->model_post->_update($post_id, array("positive_votes_cnt" => $count[0]['count']));			
		}
		
		$objResponse->addAssign("span_vote_{$post_id}", "innerHTML", " <font style='color: #888;'>(Ваш голос засчитан)</font> ");

		return $objResponse;	
	}

}