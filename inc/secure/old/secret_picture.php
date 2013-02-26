<?php
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//ОТОБРАЖАЕМ КАРТИНКУ С СЕКРЕТНЫМ КОДОМ
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	//Включаем сессию
	@session_start();
	$_SESSION['secret_code'] = rand(111111,999999);

	$secret_code = $_SESSION['secret_code'];
	$img_path = 'secret.jpg';
	$fon_size =20;

	$img		= ImageCreateFromJpeg($img_path);
	$img_size	= getimagesize($img_path);

	$fw = imagefontwidth ($fon_size);
	$fh = imagefontheight ($fon_size);

	$x = ($img_size[0] - strlen($secret_code * $fw ))/3;
	$y = ($img_size[1] - $fh) / 2; // Расположение текста на картинке

	$color = imagecolorallocate($img,
								hexdec(substr("#330066",1,2)),
								hexdec(substr("#330066",3,2)), 
								hexdec(substr("#330066",5,2))
								);

	imagestring ($img, $fon_size, $x, $y, $secret_code, "#2645D9");
	imagejpeg($img);

	
?>