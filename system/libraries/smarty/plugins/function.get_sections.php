<?php 
/**
 * Рекурсивный вывод дерева разделов
 * @author ashmits by 27.12.2012 12:39
 * @param array $params - массив разделов
 * @param object $smarty - шаблонизатор
 * @param int $level - уровень вложенности раздела
 * @return string template
 */
function smarty_function_get_sections($params, &$smarty, $level = 1, $type = 'item', $current_section = null, $parent_id = null)
{
	if (!empty($params['section']))
	{
		
		if (!empty($params['type']))
		{
			$type = $params['type'];
		}

		if (!empty($params['current_section']))
		{
			$current_section = $params['current_section'];
			$smarty->assign('current_section', $current_section);
		}

		if (!empty($params['filter_section']))
		{
			$filter_section = $params['filter_section'];
			$smarty->assign('filter_section', $filter_section);
		}
		
		if (!empty($params['parent_id']))
		{
			$parent_id = $params['parent_id'];
			$smarty->assign('parent_id', $parent_id);
		}
		
		$smarty->assign('section', $params['section']);
		$smarty->assign('level', $level);
		
		switch($type)
		{
			case "option":
				$template[] = $smarty->fetch('admin/sections/section_option.tpl');
				break;
			default:
				$template[] = $smarty->fetch('admin/sections/section_item.tpl');
				break;
		}
		

		
		if (!empty($params['section']['children']))
		{
			$level += 1;
			foreach($params['section']['children'] as $key=>$val)
			{
				$template[] = smarty_function_get_sections(array("section" => $params['section']['children'][$key]), $smarty, $level, $type, $current_section, $parent_id);
			}
		}
		
		return implode("\n", $template);
	}
}

?>