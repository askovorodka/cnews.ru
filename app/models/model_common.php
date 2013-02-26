<?php 

/**
 * ������������ ����� �������, ��������� ����� ������ ��������
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
	 * ������� ��������� ������
	 * @param ������� ������� $db_table
	 * @param ������� ������� $where
	 * @param ������������ ���� ������� $fields
	 * @param ���������� $order
	 * @param ����� ������� $limit_start
	 * @param ����������� ������� $limit_end
	 */
	public function select($db_table, $where=null, $fields=null, $order=null, $limit_start=null, $limit_end=null)
	{
		$this->db->_reset_select();
		
		if (empty($db_table))
		{
			show_error("�� ������� �������� �������");
		}
		
		//��������� �����
		if (is_numeric($limit_start))
		{
			$this->db->limit(intval($limit_start));
			if (is_numeric($limit_end))
			{
				$this->db->limit(intval($limit_end), intval($limit_start));
			}
		}
		
		//����������
		if (!empty($order) and is_string($order))
		{
			$this->db->order_by( (string)$order );
		}
		elseif (!empty($order) && !is_string($order))
		{
			show_error("���������� �������� order by");
		}
		
		//������� ������������ �����
		if (!empty($fields))
		{
			$this->db->select($fields );
		}
		
		//������� �������
		if (!empty($where))
		{
			$this->db->where($where);
			$this->where = $where;
		}
		
		//�������
		$this->result = $this->db->get( (string) $db_table );
		
		$this->db_table = (string)$db_table;
		
		//����������
		if ($this->result->num_rows() > 0)
		{
			//���������� ������ ���������
			return $this->result->result_array();
		}
		else
		{
			//�����
			return null;
		}
		
	}

	
	/**
	 * ������� ���������� ���� ������� �� �������, ��� ���������
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
			show_error("�� ������� �������� �������");
		}
	
		//������� �������
		if (!empty($where))
		{
			$this->db->where($where);
			$this->where = $where;
		}
	
		//�������
		$this->db->from( (string) $db_table );
	
		$this->result = $this->db->count_all_results();
		
		//����������
		return $this->result;
		
	}
	
	
	/**
	 * @author ashmits by 06.12.2012 11:55
	 * ������� ��������� ������
	 * @param ������� ������� $db_table
	 * @param ������� ������� $where
	 * @param ������������ ���� ������� $fields
	 * @param ���������� $order
	 * @param ����� ������� $limit_start
	 * @param ����������� ������� $limit_end
	 */
	
	public function select_one($db_table, $where=null, $fields=null, $order=null, $limit_start=null, $limit_end=null)
	{
		//������ ����� �������
		$this->result = $this->select($db_table, $where, $fields, $order, $limit_start, $limit_end);
		if (!empty($this->result))
		{
			//���������� ���� ������
			return $this->result[0];
		}
		return null;
	}
	
	/**
	 * @author ashmits by 06.12.2012 12:04
	 * ���������� ������
	 * @param ������� ���������� $db_table
	 * @param ������� ������� $fields
	 * @param ������� $where
	 */
	public function update($db_table, $fields, $where)
	{
		if (!empty($where))
		{
			$this->db->where( $where );
		}
		//��������� ������
		$this->db->update($db_table, $fields );
	}
	
	/**
	 * @author ashmits by 06.12.2012 12:10
	 * ������� ������
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
	
	//����� ������ ������� ��� �������
	public function last_query()
	{
		return $this->db->last_query();
	}
	
	//������� ��������� ����������� id
	public function last_insert_id()
	{
		return $this->db->insert_id();
	}
	
}

?>