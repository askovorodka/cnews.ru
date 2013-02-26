<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
class CI_FormPersister {
	function CI_FormPersister()
	{
     require_once(BASEPATH.'libraries/FormPersister/config.php');
	 require_once(BASEPATH.'libraries/FormPersister/HTML/FormPersister.php');
	 ob_start(array('HTML_FormPersister', 'ob_formpersisterhandler'));
	}
}

?>