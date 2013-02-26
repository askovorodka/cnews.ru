<?php 

/**
 * Проверка фото на существование
 * @param String $image
 * @return boolean
 * @author ashmits by 21.12.2012 17:04
 */
function smarty_modifier_isimage($image)
{
	if (!empty($image))
	{
		$imageinfo = @getimagesize($image);
		if ($imageinfo and !empty($imageinfo['mime']))
		{
			return true;
		}
	}
	
	return false;
	
}

?>