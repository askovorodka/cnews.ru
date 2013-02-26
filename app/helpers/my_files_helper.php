<?php 

class My_Files_Helper
{
	
	public static function set_table_dirs($date, $path)
	{
		
		$year = date("Y", strtotime($date));
		$month = date("m", strtotime($date));
		$day = date("d", strtotime($date));
		
		if (!is_dir($path.$year))
		{
			@mkdir($path.$year);
			chmod($path.$year, 0777);
		}
		
		$path .= $year."/";
		
		if (!is_dir($path.$month))
		{
			@mkdir($path.$month);
			@chmod($path.$month, 0777);
		}
		
		$path .= $month."/";
		if (!is_dir($path.$day))
		{
			@mkdir($path.$day);
			@chmod($path.$day, 0777);
		}
		
		$path .= $day."/";
		
		return (string)$path;
		
	}
	
}

?>