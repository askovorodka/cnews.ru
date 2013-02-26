<?php 

function smarty_function_get_sections_to_select($params, $smarty, $level=1)
{
	if (!empty($params['section']))
	{
		
		$smarty->assign('section', $params['section']);
		$smarty->assign('level', $level);
		
		$template[] = $smarty->fetch('admin/sections/section_option.tpl');
		
		if (!empty($params['section']['children']))
		{
			$level += 1;
			foreach($params['section']['children'] as $key=>$val)
			{
				$template[] = smarty_function_get_sections(array("section" => $params['section']['children'][$key]), $smarty, $level);
			}
		}
		
		return implode("\n", $template);
		
	}
}

?>