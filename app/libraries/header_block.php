<?php 

/**
 * Класс для обработки шапки сайта
 * @author ashmits by 05.12.2012 16:34
 *
 */

class Header_Block
{
	
	private $title = null;
	private $js = array();
	private $css = array();
	private $CI = null;
	
	public function __construct()
	{
		$this->CI = &get_instance();
		//инициализируем дефолтные стили и скрипты
		$this->css = $this->CI->config->item('css_admin');
		$this->js = $this->CI->config->item('js_admin');
	}
	
	public function set_title($title)
	{
		$this->title = (string)$title;
	}
	
	public function get_title()
	{
		return $this->title;
	}
	
	public function set_js($js, $clear=false)
	{
		if ($clear)
		{
			$this->js = $js;
		}
		else
		{
			$this->js = array_merge($this->js, $js);
		}
	}
	
	
	public function get_js()
	{
		return $this->js;
	}
	
	public function set_css($css)
	{
		$this->css = array_merge($this->css, $css);
	}
	
	public function get_css()
	{
		return $this->css;
	}
	
	/**
	 * Передаем объект в шаблонизатор
	 */
	public function set()
	{
		$this->CI->smarty->assign('header_block', $this);
	}
	
}

?>