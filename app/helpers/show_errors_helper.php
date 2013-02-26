<?php
/**
 * Кастомный хелпер обработчик ошибок
 * @author ashmits by 20.02.2013 14:31
 *
 */
final class Show_Errors
{
	/*
	 * Ошибка доступа в раздел сайта
	 */
	public static function show_permission_error($message)
	{
		$head = "Ограничение доступа в раздел сайта";
		$error =& load_class('Exceptions');
		echo $error->show_error($head, $message, 'error_404', 404);
		exit();		
	}
	
}

?>