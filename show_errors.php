<?
 error_reporting('E_ALL');
 ini_set('display_errors',1);

 if (!function_exists('userErrorHandler'))
  { function userErrorHandler($errno, $errmsg, $filename, $linenum, $vars)
     {
        $errortype = array (
                    E_ERROR          => "Error",
                    E_WARNING        => "Warning",
                    E_PARSE          => "Parsing Error",
                    E_NOTICE          => "Notice",
                    E_CORE_ERROR      => "Core Error",
                    E_CORE_WARNING    => "Core Warning",
                    E_COMPILE_ERROR  => "Compile Error",
                    E_COMPILE_WARNING => "Compile Warning",
                    E_USER_ERROR      => "User Error",
                    E_USER_WARNING    => "User Warning",
                    E_USER_NOTICE    => "User Notice",
                    E_STRICT          => "Runtime Notice"
                    );
        // set of errors for which a var trace will be saved
        $user_errors = array(E_USER_ERROR, E_USER_WARNING, E_USER_NOTICE);
        echo '<p><b>Ошибка: '.$errno.' '.$errortype[$errno].' - '.$errmsg.'</b> (файл:'.$filename.' строка:'.$linenum.')</p>';
     }

    $old_error_handler = set_error_handler("userErrorHandler");
  }
?>