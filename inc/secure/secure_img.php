<?
 session_start();

 /* нужно сбрасывать код $_SESSION["secret_code"] чтобы сгенерилась новая картинка
  * иначе если генерить картинку при каждом к ней обращении
  * то в 7-ом эксплорере он обращается дважды к файлу картинки
  * и она перегенеряет код а пользователь видит старый - и
  * соответственно вводит не тот
  */
   if (isset($_SESSION["secret_code"]) ) // || ($_SESSION["secret_code"]==''))
	unset($_SESSION["secret_code"]);

    if (isset($_GET['w']))
       $width=$_GET['w'];
    else
       $width=150;

    if (isset($_GET['h']))
       $height=$_GET['h'];
    else
       $height=30;

    if (isset($_GET['f']))
       $font_size=$_GET['f'];
    else
       $font_size=22;

    
    $num = mt_rand('100000','999999');
    //echo $num.'<br />';
    $num=str_replace('7','0',$num);
    //echo $num;
    // Запишем номер в сессию
    $_SESSION['secret_code']=md5($num);
    
    // Создадим рисунок размером 50x15
    $img = imagecreate($width,$height);
    // Зададим задний цвет (серый) по RGB
    $back = imagecolorallocate($img, 218, 218 ,218);
    // Зададим черный цвет
    $black = imagecolorallocate($img, 0, 0, 0);
    $gray = imagecolorallocate($img, 100, 100, 100);
    // Рисуем бордюр
    imageline($img, 0,      0,        $width-1,0,         $black);
    imageline($img, 0,      0,        0,       $height-1, $black);
    imageline($img, 0,      $height-1,$width-1,$height-1, $black);
    imageline($img,$width-1,0,        $width-1,$height-1, $black);
    //echo $width/3;
    imageline($img, rand('1', $width/10),     rand('1', $height/3),       rand($width-$width/10,$width-1), rand($height/2,$height-1),$gray);
    imageline($img, rand('1', $width/10),     rand($height/3,$height-$height/3),rand($width-$width/10,$width-1), rand($height/4,$height/2),$gray);
    imageline($img, rand( $width/10,$width/5),rand($height/3,$height-$height/3),rand($width-$width/10,$width-1), rand($height/2,$height-$height/3),$gray);
    imageline($img, rand( $width/10,$width/3),rand('1', $height-$height/3),     rand($width-$width/10,$width-1), rand('1', $height-$height/3),$gray);

    // Рисуем цифры
   // imagestring($img,5,5,3,$num,$black);
    for ($i=0; $i<strlen($num); $i++)
     { $angl=40-rand('0','80');
       $divdr=floor(($width-$font_size)/6);
       imageTTFText($img,$font_size,$angl,$font_size+($i*$divdr),round(3*$height/4),$black,'font.ttf',substr($num,$i,1));
     }
    // Выводим рисунок
    imagepng($img);
?>