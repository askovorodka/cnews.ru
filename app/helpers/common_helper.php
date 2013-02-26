<?php 

/**
 * Класс помошник
 * @author ashmits by 18.12.2012 17:18
 *
 */
class Common_Helper
{
	
	/**
	 * Вставка таблиц по тегам
	 * @param String $text
	 * @return String
	 */
	public static function get_tables_by_tags($text)
	{
		$CI =& get_instance();
		$CI->load->library('tables_views');
		if (preg_match_all('/{table\sid="([0-9]+)"}/i', $text, $tags))
		{
			foreach ($tags[1] as $key => $table_id)
			{
				$tag = $tags[0][$key];
				
				$table = $CI->tables_views->get_table_by_id($table_id, 1);
				
				if (!empty($table))
				{
					if (!empty($table['structure']))
					{
						$table_code = Common_Helper::table_configure_by_limit((string)$table['structure'], (string)$table['description']);
						$CI->smarty->assign('table', $table);
						$CI->smarty->assign('table_code', $table_code);
						$code = $CI->smarty->fetch('front/tables/table_single');
						$text = str_replace($tag, $code, $text);
					}
				}
				
			}
		}
		
		$text = preg_replace('/{table\sid="([0-9]+)"}/i', "", $text);
		
		return (string)$text;
	}
	
	/**
	 * Прическа таблицы к правильному виду
	 * @param String $table_structure
	 * @param String $description
	 * @return string
	 */
	public static function set_structure_table($table_structure, $description = null)
	{
		$config =& get_config();
		$tbl_config = $config['excel_to_table'];
		
		$rows = array();
		
		if (preg_match_all("'<tr.*?>.*?</tr>'si", $table_structure, $matches))
		{
			foreach ($matches[0] as $key=>$val)
			{
				if ($key == 0)
				{
					$val = preg_replace("'<tr.*?>'is", $tbl_config['heading_row_start'], $val);
					$val = preg_replace("'</tr>'is", $tbl_config['heading_row_end'], $val);
					$val = preg_replace("'<t(d|h).*?>'is",$tbl_config['heading_cell_start'], $val);
					$val = preg_replace("'</td>'is", $tbl_config['heading_cell_end'], $val);
				}
				else
				{
					$val = preg_replace("'<tr.*?>'is", $tbl_config['row_start'], $val);
					$val = preg_replace("'</tr>'is", $tbl_config['row_end'], $val);
					
					if ($key%2 == 0)
					{
						$val = preg_replace("'<tr(.*?)>'is", "<tr$1 class='even'>", $val);
					}
					else
					{
						$val = preg_replace("'<tr(.*?)>'is", "<tr$1 class='odd'>", $val);
					}
					
					$val = preg_replace("'<t(d|h).*?>'is",$tbl_config['cell_start'], $val);
					$val = preg_replace("'</td>'is", $tbl_config['cell_end'], $val);
				}
				$rows[] = $val;
			}
		}
		
		$rows = preg_replace("/\n/i", "", $rows);
		$rows = preg_replace("'<tr(.*?)>'is", "\n   <tr$1>", $rows);
		$rows = preg_replace("'</tr>'is", "\n   </tr>", $rows);
		$rows = preg_replace("'<t(d|h)(.*?)>'is", "\n           <t$1$2>", $rows);
		
		if (count($rows))
		{
			$table_structure = "";
			$table_structure = $tbl_config['table_open'] . implode("",$rows) . $tbl_config['table_close'];
			$table_structure = preg_replace("'</table>'is", "\n</table>", $table_structure);
		}

		return (string)$table_structure;
		
	}
	
	/**
	 * Урезание таблицы, для превью
	 * @param String $table_structure
	 * @param String $description
	 * @param int $count_rows
	 * @return NULL|string
	 */
	public static function table_configure_by_limit($table_structure = null, $description = null, $count_rows = 11)
	{
		if (empty($table_structure))
			return null;
		
		$config =& get_config();
		$tbl_config = $config['excel_to_table'];
		
		if (preg_match_all("'<tr.*?>.*?</tr>'si", $table_structure, $matches))
		{
			if (!empty($matches[0]))
			{
				$rows = $matches[0];
				foreach ($rows as $key=>$val)
				{
					if ($key == 0)
					{
						$val = preg_replace("'<tr.*?>'is", $tbl_config['heading_row_start'], $val);
						$val = preg_replace("'</tr>'is", $tbl_config['heading_row_end'], $val);
						$val = preg_replace("'<t(d|h).*?>'is",$tbl_config['heading_cell_start'], $val);
						$val = preg_replace("'</td>'is", $tbl_config['heading_cell_end'], $val);
					}
					else
					{
						$val = preg_replace("'<tr.*?>'is", $tbl_config['row_start'], $val);
						$val = preg_replace("'</tr>'is", $tbl_config['row_end'], $val);
							
						if ($key%2 == 0)
						{
							$val = preg_replace("'<tr(.*?)>'is", "<tr$1 class='even'>", $val);
						}
						else
						{
							$val = preg_replace("'<tr(.*?)>'is", "<tr$1 class='odd'>", $val);
						}
							
						$val = preg_replace("'<t(d|h).*?>'is",$tbl_config['cell_start'], $val);
						$val = preg_replace("'</td>'is", $tbl_config['cell_end'], $val);
						
					}
					$rows[$key] = $val;
				}
				
				if (!empty($count_rows))
				{
					$rows = array_slice($rows, 0, $count_rows);
				}
			}
			
			if (preg_match_all("'<th.*?>.*?</th>'", $table_structure, $matches2))
			{
				if (!empty($matches2[0]))
				{
					$cols = count($matches2[0]);
				}
			}
			
			$config =& get_config();
			$table = $config['excel_to_table']['table_open'];
			
			if (!empty($cols))
			{
				$row = $tbl_config['title_row_start'].$tbl_config['title_td_start'].$description.$tbl_config['title_td_end'].$tbl_config['title_row_end'];
				$row = preg_replace("'<t(d|h)(.*?)>'", "<t$1$2 colspan=\"$cols\">", $row);
				$table .= $row;
			}
			$table .= implode("\n",$rows);
			$table .= "</table>";
			
			return $table;
		}
		
		return null;
		
	}
	
	/**
	 * Перекодировка ассоц. многомерного массива
	 * @author ashmits by 29.12.2012 11:12
	 * @param String $in_charset
	 * @param String $out_charset
	 * @param array $array
	 * @param 0,1,2 $special_chars - признак кодирования/декодирования строки
	 * 0-не трогаем
	 * 1-кодируем
	 * 2-декодируем
	 * @return Array
	 */
	public static function assoc_array_decode($in_charset = null, $out_charset = null, $array = null, $special_chars = 0)
	{
		foreach($array as $key=>$val)
		{
			
			if (is_array($val))
			{
				$array[$key] = Common_Helper::assoc_array_decode($in_charset, $out_charset, $val, $special_chars);
			}
			else
			{

				$val = iconv($in_charset, $out_charset, $val);
				if ($special_chars == 1)
				{
					$val = htmlspecialchars($val, ENT_QUOTES);
				}
				elseif ($special_chars == 2)
				{
					$val = htmlspecialchars_decode($val, ENT_QUOTES);
				}
				$array[$key] = $val;
			}	
		}
		
		return $array;
		/*
		if (empty($in_charset) or empty($out_charset) or empty($array))
		{
			return null;
		}
		return array_map(function($val) use($in_charset, $out_charset, $special_chars){ 
			//если массив многомерен, делаем рекурсию
			if (is_array($val))
			{
				$val = Common_Helper::assoc_array_decode($in_charset, $out_charset, $val, $special_chars);
			}
			else 
			{
				
				$val = iconv($in_charset, $out_charset, $val);
				if ($special_chars == 1)
				{
					$val = htmlspecialchars($val, ENT_QUOTES);
				}
				elseif ($special_chars == 2)
				{
					$val = htmlspecialchars_decode($val, ENT_QUOTES);
				}
				
			}
			return $val;
			 
		}, $array);
		*/
		//return;
	}
	
	
	/**
	 * Переодим массив в хэш и заносим в базу если нет такого сериалайза
	 * @author ashmits by 25.12.2012 17:30
	 * @param Array $array
	 * @return string|NULL
	 */
	public static function array_to_hash($array = null)
	{
		if (count($array))
		{

			$hash = sha1(serialize($array));

			$CI =& get_instance();
			$CI->load->model('model_common');
			$is_hash_exist = $CI->model_common->select_one("array_hash", array("hash" => $hash));
			if (empty($is_hash_exist))
			{
				$CI->model_common->insert("array_hash", array("hash" => $hash, "serialize" => serialize($array)));
			}
			
			return (string)$hash;
			
		}
		
		return null;
		
	}
	
	
	/**
	 * Ищем хэш в базе, достаем и возвращаем сериалайз массива
	 * @author ashmits by 25.12.2012 17:32
	 * @param Sha1 String $hash
	 * @return Array|NULL
	 */
	public static function hash_to_array($hash = null)
	{
		
		$CI =& get_instance();
		$CI->load->model('model_common');
		$hash_item = $CI->model_common->select_one("array_hash", array("hash" => $hash));
		
		if (!empty($hash_item['serialize']))
		{
			return unserialize($hash_item['serialize']);
		}
		
		return null;
		
	}
	
	
	public static function is_sha1($str) {
		return (bool) preg_match('/^[0-9a-f]{40}$/i', $str);
	}	
	
	public static function _split_text($s, $len = 10)
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
	
	
	public static function _text_format($word, $fl) 
	{
		$up_letter   = 'АБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ';
		$down_letter = 'абвгдеёжзийклмнопрстуфхцчшщъыьэюя';
		if($fl == '1') return trim(strtr($word, $down_letter, $up_letter));
	 		return trim(strtr($word, $up_letter, $down_letter));
	}	
	 
	
	public static function _text_validation($text = "")
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
	public static function rus_to_translit($s){
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
	
	
	public static function _send_email($from, $to, $subject, $html_text, $text = '')
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
	public static function _cut_long_annotation($annotation, $len)
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
	
	
	public static function _declension($n,$string=array('день','дня','дней'))
	{
		$n = abs($n) % 100;
		$n1 = $n % 10;
		if ($n > 10 && $n < 20) return $string[2];
		if ($n1 > 1 && $n1 < 5) return $string[1];
		if ($n1 == 1) return $string[0];
		return $string[2];
	}
	
	public static function _days_to_str($days)
	{
		return $days." ". self::_declension($days);
	}
	
	// Если строка $date_str содержит в себе номер месяца обрамленный в символ -,
	// то функция заменяет его на русскоязычное название месяца.
	// К примеру, нужно вывести на экран дату 1 мая 2009 года.
	// Получаем дату из базы в виде 1 -5- 2009 и обрабатываем этой функцией
	public static function _convert_month_in_str($date_str)
	{
		$date_array = array("-01-" => "января", "-02-" => "февраля", "-03-" => "марта", "-04-" => "апреля", "-05-" => "мая", "-06- "=> "июня", "-07-" => "июля", "-08-" => "августа", "-09-" => "сентября", "-10-" => "октября", "-11-" => "ноября", "-12-" => "декабря");
	
		return strtr($date_str, $date_array);
	}
	
	// Функция возвращает ip пользователя
	public static function _get_real_ip()
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
	
	/**
	 * Добавелние элементов в ассоциативный массив
	 * @param unknown_type $arr
	 * @return number
	 */
	public static function array_push_associative(&$arr) {
		$ret=0;
		$args = func_get_args();
		foreach ($args as $arg) {
			if (is_array($arg)) {
				foreach ($arg as $key => $value) {
					$arr[$key] = $value;
					$ret++;
				}
			}else{
				$arr[$arg] = "";
			}
		}
		return $ret;
	}
	
	
}

?>