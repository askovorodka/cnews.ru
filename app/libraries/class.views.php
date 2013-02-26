<?php 

/**
 * Наработка на будущее, абстрактная вьюха
 * @author ashmits by 18.02.2013 11:02
 *
 */
abstract class Views extends Validate
{
	abstract $array, $where, $current_page;
	
	
	public function __construct()
	{
		parent::__construct();
	}
	
	
	abstract function get_sort_orders(){}
	
	abstract function set_value_to_conditions(){}
	
	abstract function get_conditions(){}
	
}

?>