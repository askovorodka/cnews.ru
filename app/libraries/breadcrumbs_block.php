<?php 
/**
 * Хлебные крошки
 * @author ashmits by 10.12.2012 by 11:07
 *
 */
final class Breadcrumbs_Block
{
	
	private $data = array();
	private $result = array();
	private $CI = null;
	
	public function __construct()
	{
		$this->CI =& get_instance();
		$this->add("Главная", DOMAIN.'/admin/');
	}
	
	/**
	 * Добавляем сегменты в хлебные крошки
	 */
	public function add($name, $url)
	{
		$this->data[] = array("name" => $name, "url" => $url);
	}
	
	/**
	 * Возвращаем массив хлебных крошек
	 */
	public function get($key = null)
	{
		if (!empty($key))
		{
			if (isset($this->data[$key]))
			{
				//возвращаем определенный сегмент
				return $this->data[$key];
			}
			else
			{
				return null;
			}
		}
		else
		{
			//возвращаем все сегменты
			return $this->data;
		}
	}
	
	/**
	 * Передаем объект в шаблон
	 */
	public function set()
	{
		$this->CI->smarty->assign('breadcrumbs_block', $this);
	}
	
	
}

?>