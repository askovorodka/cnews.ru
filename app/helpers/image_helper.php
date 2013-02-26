<?php 
/**
 * статический класс работы с картинками
 * @author ashmits by 25.01.13 17:00
 *
 */
final class Image_Helper{
	
	/**
	 * Находит теги <img src="..." /> в тексте и определяем размер картинки, 
	 * если больше заданных, то уменьшает в лоб width, height
	 * @param String $text
	 * @return NULL|mixed
	 */
	public static function set_images_in_text($text="")
	{
		
		if (trim($text) == "")
		{
			return null;
		}
		
		//$CI =& get_instance();
		//$CI->load->library('image_easy');
		
		//достаем все теги img
		if (preg_match_all("'<img(.*?)>'is", $text, $matches))
		{

			foreach ($matches[1] as $key=>$item)
			{
				//достаем атрибут src
				if (preg_match("/src=(\'|\")(.*?)(\'|\")/i", $item, $match))
				{
					if (!empty($match[2]))
					{
						$src = (string)$match[2];
						$image_info = self::get_image_info($src);
						if ($image_info['width'] > MAX_WIDTH_IMAGE or $image_info['height'] > MAX_HEIGHT_IMAGE)
						{
							
							//$CI->image_easy->load($src);
							
							if ($image_info['width'] > MAX_WIDTH_IMAGE)
							{
								$item = preg_replace("/width=(\'|\")([a-z0-9_A-Z]+)(\'|\")/i", "", $item);
								$item .= " width = '" . MAX_WIDTH_IMAGE . "'";
								//$CI->image_easy->resizeToWidth(500);
							}
							elseif ($image_info['height'] > MAX_HEIGHT_IMAGE)
							{
								$item = preg_replace("/height=(\'|\")([a-z0-9_A-Z]+)(\'|\")/i", "", $item);
								$item .= " height = '" . MAX_HEIGHT_IMAGE . "'";
								//$CI->image_easy->resizeToHeight(500);
							}
							
							//$CI->image_easy->save(PREVIEW_IMAGE_FOLDER."resized_" . Image_Helper::get_filename_by_src($src));
							
							$text = str_replace($matches[0][$key], "<p class='popup_img'><a href='$src' class='popup_img'><img" . $item . "></a></p>", $text);
						}
					}
				}
			}
		}
		
		return $text;
		
	}
	
	/**
	 * Информация о картинке
	 * @param String $src
	 * @return multitype:unknown |NULL
	 */
	public static function get_image_info($src="")
	{
		if ($info = @getimagesize($src))
		{
			return array(
					"width" => $info[0],
					"height" => $info[1],
					"type" => $info[2] 
					);
		}
		
		return null;
		
	}
	
	public static function get_image_type($src)
	{
		if ($image = self::get_image_info($src))
		{
			return $image['type'];
		}
	}
	
	public static function get_filename_by_src($src="")
	{
		if (trim($src) != "")
		{
			$src_array = explode("/", $src);
			if (!empty($src_array[count($src_array)-1]))
			{
				return (string)$src_array[count($src_array)-1];
			}
		}
	}
	
}

?>