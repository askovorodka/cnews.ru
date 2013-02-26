<?php

class Model_glossary extends Model {
	
	function Model_glossary()
	{
		parent::Model();
	}

	function _select($where = '', $select = '*', $order = "", $limit = "")
	{
		$this->db->select($select);
		$this->db->from('glossary');
		if (!empty($where)) $this->db->where($where);
		if (!empty($order)) $this->db->orderby($order);
		if (!empty($limit)) $this->db->limit($limit);

		$query = $this->db->get();
		return $query->result_array();
	}
	
	function _insert($data)
	{
		$this->db->insert('glossary', $data); 
		return $this->db->insert_id();
	}
	
	function _update($id, $data)
	{
		$this->db->where('id', $id);
		$this->db->update('glossary', $data); 
	}
	
	function _delete($data)
	{
		$this->db->where($data);
		$this->db->delete('glossary'); 
	}

	function get_first()
	{
		$query = $this->db->query("
			select distinct(left(header, 1)) as letter
			from glossary
			where left(header, 1) RLIKE '[а-я]'
			order by header
		");
		$result = $query->result_array();

		$a_alph = array("А", "Б", "В", "Г", "Д", "Е", "Ё", "Ж", "З", "И", "Й", "К", "Л", "М", "Н", "О", "П", "Р", "С", "Т", "У", "Ф", "Х", "Ц", "Ч", "Ш", "Щ", "Ы", "Э", "Ю", "Я");
		$alph_ru = array();
		foreach ($a_alph as $alph)
		{
			$a = array();
			$a['letter'] = $alph;
			$a['code'] = str_replace("%", "_", urlencode($a['letter']));
			foreach ($result as $key => &$val)
			{
				if ($val['letter'] == $alph)
				{
					$a['link'] = 1;
				}
			}
			
			$alph_ru[] = $a;
		}


		$two['rus'] = $alph_ru;

		// Английский алфавит
		$query = $this->db->query("
			select distinct(left(header, 1)) as letter
			from glossary
			where left(header, 1) RLIKE '[a-z]'
			order by header
		");
		$result = $query->result_array();

		$alph = "A";
		$alph_en = array();
		for ($i = 1; $i <= 26; $i++)
		{
			$a = array();
			$a['letter'] = $alph;
			$a['code'] = str_replace("%", "_", urlencode($a['letter']));
			foreach ($result as $key => &$val)
			{
				if ($val['letter'] == $alph)
				{
					$a['link'] = 1;
				}
			}
			
			$alph_en[] = $a;
			$alph++;
		}

		$two['eng'] = $alph_en;

		return $two;
	}


	function _select_by_pages($where = '', $n_this_page = 1, $nchar = 12)
	{
		if (!empty($where)) $where = "where ".$where;

		$query = $this->db->query("
			select count(*) as count
			from glossary
			$where
			order by header
		");
		$result = $query->result_array();
		$n_row = $result[0]['count'];

		// Число строк на одну страницу
		$n_char_on_page = $nchar;	
		// Определяем число страниц
		if ($n_row > 0) $n_pages = ceil($n_row / $n_char_on_page);
		 else $n_pages = 0;

		// Строка, с которой нужно начинать отображение
		$limit = ($n_this_page - 1) * $n_char_on_page;
		$result['count'] = $n_pages;
		$result['n_rows'] = $n_row;
		
		$query = $this->db->query("
			select *
			from glossary
			$where
			order by header
			limit $limit, $n_char_on_page
		");
		$result['array'] = $query->result_array();	

		return ($result);
	}

}