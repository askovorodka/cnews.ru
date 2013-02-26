<?php 

/**
 * Родительский класс моделей, реализует общие методы запросов
 * @author ashmits by 06.12.2012 10:38
 *
 */
class Model_Common extends Model
{
	private $db_table, $where;
	private $fields, $result, $row = array();
	private $limit_start, $count = 0;
	private $limit_end = null;
	
	function __construct()
	{
		parent::Model();
	}

	
	/**
	 * @author ashmits by 06.12.2012 11:30
	 * Выборка отдельной строки
	 * @param Таблица выборки $db_table
	 * @param Условие выборки $where
	 * @param Определенные поля таблицы $fields
	 * @param Сортировка $order
	 * @param Лимит выборки $limit_start
	 * @param Ограничение выборки $limit_end
	 */
	public function select($db_table, $where=null, $fields=null, $order=null, $limit_start=null, $limit_end=null)
	{
		$this->db->_reset_select();
		
		if (empty($db_table))
		{
			show_error("Не указано название таблицы");
		}
		
		//описываем лимит
		if (is_numeric($limit_start))
		{
			$this->db->limit(intval($limit_start));
			if (is_numeric($limit_end))
			{
				$this->db->limit(intval($limit_end), intval($limit_start));
			}
		}
		
		//сортировка
		if (!empty($order) and is_string($order))
		{
			$this->db->order_by( (string)$order );
		}
		elseif (!empty($order) && !is_string($order))
		{
			show_error("Невалидный параметр order by");
		}
		
		//выборка определенных полей
		if (!empty($fields))
		{
			$this->db->select($fields );
		}
		
		//условия выборки
		if (!empty($where))
		{
			$this->db->where($where);
			$this->where = $where;
		}
		
		//таблица
		$this->result = $this->db->get( (string) $db_table );
		
		$this->db_table = (string)$db_table;
		
		//результаты
		if ($this->result->num_rows() > 0)
		{
			//возвращаем первый подмассив
			return $this->result->result_array();
		}
		else
		{
			//пусто
			return null;
		}
		
	}

	
	/**
	 * Подсчет количества всех записей по условию, для пейджинга
	 * @author ashmits by 24.12.2012 10:52
	 * @param unknown_type $db_table
	 * @param unknown_type $where
	 * @return NULL
	 */
	public function select_count($db_table, $where=null)
	{
		$this->db->_reset_select();
	
		if (empty($db_table))
		{
			show_error("Не указано название таблицы");
		}
	
		//условия выборки
		if (!empty($where))
		{
			$this->db->where($where);
			$this->where = $where;
		}
	
		//таблица
		$this->db->from( (string) $db_table );
	
		$this->result = $this->db->count_all_results();
		
		//результаты
		return $this->result;
		
	}
	
	
	/**
	 * @author ashmits by 06.12.2012 11:55
	 * Выборка отдельной строки
	 * @param Таблица выборки $db_table
	 * @param Условие выборки $where
	 * @param Определенные поля таблицы $fields
	 * @param Сортировка $order
	 * @param Лимит выборки $limit_start
	 * @param Ограничение выборки $limit_end
	 */
	
	public function select_one($db_table, $where=null, $fields=null, $order=null, $limit_start=null, $limit_end=null)
	{
		//делаем общую выборку
		$this->result = $this->select($db_table, $where, $fields, $order, $limit_start, $limit_end);
		if (!empty($this->result))
		{
			//возвращаем одну строку
			return $this->result[0];
		}
		return null;
	}
	
	/**
	 * @author ashmits by 06.12.2012 12:04
	 * Обновление данных
	 * @param Таблица обновления $db_table
	 * @param Столбцы вставки $fields
	 * @param Условия $where
	 */
	public function update($db_table, $fields, $where)
	{
		if (!empty($where))
		{
			$this->db->where( $where );
		}
		//обновляем данные
		$this->db->update($db_table, $fields );
	}
	
	/**
	 * @author ashmits by 06.12.2012 12:10
	 * Вставка данных
	 * @param unknown_type $db_table
	 * @param unknown_type $fields
	 * @return int last_insert_id
	 */
	public function insert($db_table, $fields)
	{
		$this->db->insert($db_table, $fields);
		return $this->last_insert_id();
	}
	
	function delete($db_table, $where)
	{
		if (!empty($db_table) && !empty($where))
		{
			$this->db->delete($db_table, $where);
		}
	}
	
	//вывод текста запроса для отладки
	public function last_query()
	{
		return $this->db->last_query();
	}
	
	//вернуть последний добавленный id
	public function last_insert_id()
	{
		return $this->db->insert_id();
	}
	
}

?>