<?php
/**
 * ��������� ������ ���������� ������
 * @author ashmits by 20.02.2013 14:31
 *
 */
final class Show_Errors
{
	/*
	 * ������ ������� � ������ �����
	 */
	public static function show_permission_error($message)
	{
		$head = "����������� ������� � ������ �����";
		$error =& load_class('Exceptions');
		echo $error->show_error($head, $message, 'error_404', 404);
		exit();		
	}
	
}

?>