<?php 

class Frontpage extends Controller
{
	function __construct()
	{
		parent::Controller();
		$this->load->helper('my_url');
	}
	
	function index()
	{
		My_Url_Helper::redirect(DOMAIN.'/admin/');
	}
}

?>