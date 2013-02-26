<?php
class Starter extends Controller {
	
	var $championat_stop_date = "2010-05-17"; //2010-04-26
	
	
	function Starter()
	{
		
		parent::Controller();
		
		
		//$this->db_cnews = $this->load->database('default', TRUE);
		$this->db = $this->load->database('default', TRUE);
		
		
		//��������� ��������� - ��� ������ �����������.
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
		$this->smarty->assign("main_window_name", '������');
		
		// ����������
		
		$this->load->library('validation');
		$this->load->library('image_lib');
		$this->load->library('upload');
		$this->load->library('upload');
		
		// �������� ������
		$this->load->model('model_cnews_news');
		$this->load->model('model_cnews_pub');
		$this->load->model('model_glossary');
		$this->load->model('model_articles');
		$this->load->model('model_user');



		// ���������� �������������� ����������
		$this->load->helper('smiley');
		$this->load->library('table');	
		
		//�����������
		if (!empty($_COOKIE['user_auth_code']) and empty($_SESSION['user']))
		{
			$user = $this->model_user->_select(array('secret_code' => $_COOKIE['user_auth_code']));
			if (!empty($user)) $_SESSION['user'] = $user[0];
			 else setcookie("user_auth_code");
		}
		
		$this->_user_enter_auth();
		
		// �������� ��������� �����
		$this->smarty->assign("rand", rand(0, 2));
		
		// ������������ �������� ������, �� ������� ������������ ����
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
					// xajax �������� ������ � �������� ��������, ������� ��� ������ ��������
					// htmlspecialchars ������. ����� ��� ��������. �� �� ����� ���������� ��� ������
					// �������� xajax � �������� �� addslashes
					elseif (!is_array($val2) and substr_count($val2, "<xjxquery>"))
					{	
						$val2 = trim(addslashes($val2));
					}
				}
			}
		}
		
		// �������� ���� �� �����
		$csdate = explode("-", $this->championat_stop_date);
		$days_to_stop = ceil((mktime(23, 59, 59, $csdate[1], $csdate[2], $csdate[0]) - time())/60/60/24);
		$this->smarty->assign("days_to_stop", $days_to_stop);
		$this->smarty->assign("days_to_stop_str", $this->_declension($days_to_stop));		
		
		// ��������� XAJAX
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

		// ��������
		//$interview = $this->model_articles->_select(array('type_id' => 'interview', 'status' => 1), '*', 'id desc', 1);
		if (!empty($interview))
		{
			$this->smarty->assign('interview', $interview[0]);
		}
	
	
		//��������� ��������� � ������������, ��� ����� �������� ������
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
	// �����������
	//=====================================================
	function _authorization()
	{
		if (empty($_POST['auth_login'])) 
		{
			$this->smarty->assign("UserMessageAuth", "�� �� ������� email.");
			return 1;
		}
		
		$result = $this->_check_user($_POST['auth_login'], $_POST['auth_pass']);
		
		if (empty($result))
		{
			$this->smarty->assign("UserMessageAuth", "������� email ��� ������.");
			return 1;
		}
		elseif ($result == "blocked") 
		{
			$this->smarty->assign("UserMessageAuth", "��� ������� ������������ ���������������.");
			return 1;
		}
		elseif ($result == "norequst") 
		{
			$this->smarty->assign("UserMessageAuth", "����������� �� ������������.");
			return 1;
		}
		
		// ���������� ����
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
		
	// ��������, ���� �� ������������ ���������� � ������� � ����� 
	// �� � ��� � ���� � �������. ���� ��� ��, �� ��������� ���������� � ���.
	// ����� ������� ������.
	function _user_enter_auth()
	{
		// ����������� ������������
		if (!empty($_SESSION['user']))
		{
			// ��������� ������ �� ������� ������ ���������� � ��������� ��
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
				// �������� ���������� � ������������ � Smarty
				$this->smarty->assign('user_info', $_SESSION['user']);
			}
		} 

	}
	
	// ������� ���������, ���������� �� ������������ � ����� ������� � �������
	// ���� ���, �� ������������ 0, ����� ��� ���������� � ������������
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
	// ������� ������ ��� ������ �����
	//======================================================
	// ������� ��������� ������� ������� ����� �� �����
	function _split_text($s, $len = 10) 
	{ 
		$marker = "\x01";
		$hyp = " ";
		
		$wordmaxlen = $len;
		
		// ��������� ��� ���� ����� ������� �� �� ��������
		preg_match_all('/(<.*?\>)/si',$s,$tags);
		
		// �������� ��� ���� �� �������
		$s =  preg_replace('/(<.*?\>)/si', $marker, $s);
		
		// ��������� ����� �� �����
		$words = split(' ',$s);
		
		for ($i=0; $i<count($words); $i++)
		{
			// ������ ����� >= $wordmaxlen ���������
		  if (strlen($words[$i])>=$wordmaxlen)
		    $words[$i] = chunk_split($words[$i],$wordmaxlen,$hyp);
		    
		}
		
		// �������� ������ �� ��� �������� �� ����� ����
		$s = implode(' ',$words);
		
		// ��������������� ����, ����� ������� ���� �������� ���������
		for ($i=0; $i<count($tags[1]); $i++)
			$s =  preg_replace("/$marker/si", $tags[1][$i], $s, 1);
		
		return $s;
	}

	function _text_format($word, $fl) 
	{
		$up_letter   = '�����Ũ��������������������������';
		$down_letter = '��������������������������������';
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
		
		// �������� ' �� `
		$text = str_replace("'", "`", $text);
		
		// ������ ���� ����� �� ���������
		$text = str_replace("..", "...", $text);
		
		// �������� ��� ������� ������������� ����� 3� ��� �� 3� ����������
		preg_match_all("/[?!\.,]{3,}/is", $text, $match);
		
		foreach ($match[0] as $key => $val)
		{
			$text = str_replace($val, substr($val, 0, 3), $text);
		}
		
		// ���������� ������� ����� ���� ������ ����������
		// ���� ��� �� , : ; �� ��������� ����������� ����� � ������� ������� 
		$match = "";
		preg_match_all("/([�-���-�])(\.|!|\?|,|:|;|\.\.\.)([�-���-�])/is", $text, $match);
		if (!empty($match))
		{
			foreach ($match[0] as $key => $val)
			{
				$flatter = $match[1][$key];
				$sumb = $match[2][$key];
				$slatter = $match[3][$key];
				
				if ($match[0][$key] == "�.�" or $match[0][$key] == "�.�") continue;
				
				if ($sumb != "," and $sumb != ":" and $sumb != ";")
				{
					$slatter = strtoupper($this->_text_format($slatter, 1));
				}
				
				$text = str_replace($match[0][$key], $flatter.$sumb." ".$slatter, $text);
			}
		}
		
		// ����������� ����
		// �������� �������� ���� (�)
		$text = str_replace("�", "�", $text);
		// �������� �����, ��������� ��������� ( - ); 
		$text = str_replace(" - ", " � ", $text);
		$text = str_replace(" -", " � ", $text);
		$text = str_replace("--", "�", $text);
		// ����� � ������ ������ (������ ����); 
		if ($text[0] == "-") $text[0] = "�";
		$text = str_replace("\n- ", "\n�", $text);
		$text = str_replace("\n - ", "\n�", $text);
		$text = str_replace("\n\t- ", "\n\t�", $text);
			
		// ������ ���������� ������ ��� ��������� �� ������� ���������
		return  $text;
	}	
	
	//�������������� ������� ���� � �����������
	function rus_to_translit($s){
		$s=trim(strtolower($s));  
		$s = preg_replace("/[^�-��-�a-zA-Z0-9 +]/ies", '', $s);
		$s=str_replace("�", "a",$s); $s=str_replace("�", "b",$s);
		$s=str_replace("�", "v",$s); $s=str_replace("�", "g",$s);
		$s=str_replace("�", "d",$s); $s=str_replace("�", "e",$s);
		$s=str_replace("�", "yo",$s); $s=str_replace("�", "zh",$s);
		$s=str_replace("�", "z",$s); $s=str_replace("�", "i",$s);
		$s=str_replace("�", "j",$s); $s=str_replace("�", "k",$s);
		$s=str_replace("�", "l",$s); $s=str_replace("�", "m",$s);
		$s=str_replace("�", "n",$s); $s=str_replace("�", "o",$s); 
		$s=str_replace("�", "p",$s); $s=str_replace("�", "r",$s);
		$s=str_replace("�", "s",$s); $s=str_replace("�", "t",$s);
		$s=str_replace("�", "u",$s); $s=str_replace("�", "f",$s);
		$s=str_replace("�", "h",$s); $s=str_replace("�", "ts",$s);
		$s=str_replace("�", "ch",$s); $s=str_replace("�", "sh",$s); 
		$s=str_replace("�", "shch",$s); $s=str_replace("�", "",$s); 
		$s=str_replace("�", "y",$s); $s=str_replace("�", "",$s); 
		$s=str_replace("�", "e",$s); $s=str_replace("�", "yu",$s); 
		$s=str_replace("�", "ya",$s); $s=str_replace(" ", "_",$s); 
			
		$s=str_replace("�", "a",$s); $s=str_replace("�", "b",$s);
		$s=str_replace("�", "v",$s); $s=str_replace("�", "g",$s);
		$s=str_replace("�", "d",$s); $s=str_replace("�", "e",$s);
		$s=str_replace("�", "yo",$s); $s=str_replace("�", "zh",$s);
		$s=str_replace("�", "z",$s); $s=str_replace("�", "i",$s);
		$s=str_replace("�", "j",$s); $s=str_replace("�", "k",$s);
		$s=str_replace("�", "l",$s); $s=str_replace("�", "m",$s);
		$s=str_replace("�", "n",$s); $s=str_replace("�", "o",$s); 
		$s=str_replace("�", "p",$s); $s=str_replace("�", "r",$s);
		$s=str_replace("�", "s",$s); $s=str_replace("�", "t",$s);
		$s=str_replace("�", "u",$s); $s=str_replace("�", "f",$s);
		$s=str_replace("�", "h",$s); $s=str_replace("�", "ts",$s);
		$s=str_replace("�", "ch",$s); $s=str_replace("�", "sh",$s); 
		$s=str_replace("�", "shch",$s); $s=str_replace("�", "",$s); 
		$s=str_replace("�", "y",$s); $s=str_replace("�", "",$s); 
		$s=str_replace("�", "e",$s); $s=str_replace("�", "yu",$s); 
		$s=str_replace("�", "ya",$s);
		
		return $s;
	}
	
	// ������� ��������� ������ ���������� ������ �����������
	// �� ��������: << -1- -2- >>
	// $n_this_page - ����� ������� ��������
	// $n_pages - ����� �������
	// $controller_name - ��� ����������� (��� �������� �����������)
	function _drop_to_page($n_this_page, $n_pages, $controller_name, $page_name = '')
	{
		if ($n_pages <= 1) return 0;
	
		$d = 5; // �������� ������
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
	
		// ������� �� ������ ��������
		if ($n_this_page > 1)
		{
			$path = "/".$controller_name."1".$page_name;
			$array_link[] = "<a href='$path' class='rew'>������</a> ";
		}
		
		// ����� ������ ������� �� $start � ���������� $stop
		for ($i = $start; $i <= $stop; $i++)
		{
				$path = "/".$controller_name.$i.$page_name;
				if ($i != $n_this_page)
				{
					$array_link[] = "<a href='$path'> $i </a>";
				}	
			 	else $array_link[] = "$i ";
		}
		
		// ������� �� ��������� ��������
		if ($n_this_page < $n_pages)
		{
			$path = "/".$controller_name.$n_pages.$page_name;
			$array_link[] = " <a href='$path' class='ff'>�����</a>";
		}			
	
		return $array_link;
	}
	
	/*
	* ������� �������� ����������� ���������,
	* ������������ ��� ��������� ��� ��� � html
	* ���������:
	* $from - �������� ����� �����������
	* $to - �������� ����� ����������
	* $subject - ���� ������
	* $html_text - ����� html ���������
	* $text - ��������� ���������
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
	* ������� �������� ������� ������� ��������� � ������� ��� �������
	* ������� �������� ��������� "..."
	* ���������:
	* $annotation - ���������, ������� ��������� ��������
	* $len - ����� ���������, ������� ��������� ��������
	* ������������ �������� - ����������� ���������.
	*/
	function _cut_long_annotation($annotation, $len)
	{
		//�������� ������� ������� ��������
		if (strlen($annotation)>$len and !empty($annotation))
		{		
			// ������ ����� ���������� �������, � �������� ����� ����������
			// len_description. ����� �� �������� ����� �� ��������.
			$annotation=stripslashes($annotation);
			$pos = strpos($annotation, ' ', $len);
			// ������ ��� � ����������� ����� ������� ���� ������� ������� � $len
			// ��� ���� ������. �� �������� ����� $len ���. ����� $pos ����� ��������� �������.
			// �������� ��������:
			if (empty($pos) and $len > 10) $pos = strpos($annotation, ' ', $len - 10);
			$annotation = substr_replace($annotation, '', $pos)."...";		
			//�� ������, ���� � ����� ������ ������� ��� ����� ('���,...' ��� '���....')
			$annotation = str_replace(',...', '...', $annotation);
			$annotation = str_replace('......', '...', $annotation);
		}
		return $annotation;
	}
	
	// ������� � �������� ��������� ��������� ����� ������ �
	// ���������� ��������� ������������ � ���������� �����������
	// � ���� ��������� ������
	function _last_visit_to_str($sec)
	{	
		$minutes = round($sec/60);
		if ($minutes <= 60)
		{
			return $minutes." ".$this->_declension($minutes, array("������", "������", "�����"));
		}

		$hourse = round($minutes/60);
		if ($hourse <= 24)
		{
			return $hourse." ".$this->_declension($hourse, array("���", "����", "�����"));
		}		

		$days = round($hourse/24);
		return $days." ".$this->_declension($days);
	}
	
	function _declension($n,$string=array('����','���','����'))
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
	
	// ���� ������ $date_str �������� � ���� ����� ������ ����������� � ������ -, 
	// �� ������� �������� ��� �� ������������� �������� ������.
	// � �������, ����� ������� �� ����� ���� 1 ��� 2009 ����. 
	// �������� ���� �� ���� � ���� 1 -5- 2009 � ������������ ���� ��������
	function _convert_month_in_str($date_str)
	{
		$date_array = array("-01-" => "������", "-02-" => "�������", "-03-" => "�����", "-04-" => "������", "-05-" => "���", "-06- "=> "����", "-07-" => "����", "-08-" => "�������", "-09-" => "��������", "-10-" => "�������", "-11-" => "������", "-12-" => "�������");

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
			$message = $message."<br /><a href='/'>������� �� ������� ��������</a>";
			$this->smarty->assign("Error", $message);
			print $message;
			//$this->smarty->assign("include_center", "show_error.tpl");
			//$this->smarty->display("index.tpl");						
			exit;				
	}
	
	// ������������ ���� ���� 2009-09-16 00:05:42 �  16 ������� 2009 � 00:05
	function _date_convert($date)
	{
	    $full_date = explode(" ", $date);
	    $date = explode("-", $full_date[0]);
	    if ($date[0] == "0000") return 0; // ������ ����
	    
	    $months = array(
	        "01" => "������",
    	    "02" => "�������",
    	    "03" => "�����",
    	    "04" => "������",
    	    "05" => "���",
    	    "06" => "����",
    	    "07" => "����",
    	    "08" => "�������",
    	    "09" => "��������",
    	    "10" => "�������",
    	    "11" => "������",
    	    "12" => "�������",
	    );
	    
	    $result = $date[2]." ".$months[$date[1]]." ".$date[0];
	    
	    if (!empty($full_date[1]))
	    {
	    	$time = explode(":", $full_date[1]);
	   		$result .= " � ".$time[0].":".$time[1];
	    }
	    
	    return $result;
	}
	
	// ����������� ���� ���� 2010-09-16 00:05:42 � 16/09/10
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
	
	// ������� ���������� ������� ����/����� �������� �� ��������� ����
	function _time_stay($date)
	{
	    //2009-12-31 21:08:00
	    $y = substr($date, 0, 4);
	    $m = substr($date, 5, 2);
	    $d =substr($date, 8, 2);
	    $h =substr($date, 11, 2);
	    $min =substr($date, 14, 2);
	    $res_h = floor((mktime($h, $min, 0, $m, $d, $y) - time())/60/60); // ����� ����� �� �������
	   // if ($res_h <=0) return false;
	    $res_d = abs(($res_h - $res_h%24)/24); // ������ ���� �� �������
	    $res_h = abs($res_h%24); // ������ ����� �� �������
	    
	    return array(
		    	"d" => round($res_d), 
		    	"d_str" => $this->_declension($res_d),
		    	"h" => round($res_h), 
		    	"h_str" => $this->_declension($res_h, array('���', '����', '�����'))
	    	);
	}

	// ������� ���������� ip ������������
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
			$objResponse->addAssign("span_vote_{$post_id}", "innerHTML", " <font style='color: #888;'>(��� ��� �����������)</font> ");
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
		
		$objResponse->addAssign("span_vote_{$post_id}", "innerHTML", " <font style='color: #888;'>(��� ����� ��������)</font> ");

		return $objResponse;	
	}

}