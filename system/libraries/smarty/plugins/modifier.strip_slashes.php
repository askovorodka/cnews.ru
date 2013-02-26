<?php

function smarty_modifier_strip_slashes($string)
{
    //return str_replace("\/", "", $string);
    return stripslashes($string);
    
}

?>