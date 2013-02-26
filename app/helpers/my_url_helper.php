<?php 
/**
 * Работа с урлами
 * @author ashmits
 *
 */
final class My_Url_Helper
{
	
	public static function redirect($url)
	{
		header("Location: " . $url);
		die();
	}
	
	public static function get_current_url()
	{
		return DOMAIN."/".$_SERVER['QUERY_STRING'];
	}
	
	public static function get_from_url()
	{
		if (preg_match("/from=(.*)$/i", $_SERVER['REQUEST_URI'], $matches))
		{
			if (!empty($matches[1]))
			{
				return $matches[1];
			}
		}
	}
	
	public static function replace_change_type_url($url, $id)
	{
		return str_replace("#ID", intval($id), $url);
	}
	
	
	public static function validate_url($url)
	{
		$match = "/^(ht|f)tp(s?)\:\/\/[0-9a-zA-Z]([-.\w]*[0-9a-zA-Z])*(:(0-9)*)*(\/?)([a-zA-Z0-9\-вЂЊвЂ‹\.\?\,\'\/\\\+&amp;%\$#_]*)?$/i";
		
		if (preg_match($match, $url))
			return true;
		
		return false;
	}
}