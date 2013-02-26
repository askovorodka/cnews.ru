<?php
class Model_cnews_pub extends Model {
	
	var $lineid = 4;

	var $main_sql_select = "
		  select 
			NEWS.*, to_char(NEWS.NEWS_DATE, 'YYYY/MM/DD') as URL_DATE, to_char(NEWS.NEWS_DATE, 'DY, DD MON YYYY HH24:MI:SS') as XML_DATE,
			TITLE.VALUE as TITLE, URI.VALUE AS URI, IMG.VALUE as IMG, DESCRIPTION.VALUE as ANONS
	";

	var $main_sql_from = "
		  from NEWS
		  JOIN news_attrs NA on NA.news_id = NEWS.news_id and NA.attr_id = 11300 and NA.value = 510761
		  left join VARCHAR_ATTRS TITLE on TITLE.ATTR_ID = 11200 and TITLE.NEWS_ID = NEWS.NEWS_ID 
		  left join VARCHAR_ATTRS URI on URI.ATTR_ID = 12487 and URI.NEWS_ID = NEWS.NEWS_ID
		  left join VARCHAR_ATTRS IMG on IMG.ATTR_ID = 12383 and IMG.NEWS_ID = NEWS.NEWS_ID 
		  left join VARCHAR_ATTRS DESCRIPTION on DESCRIPTION.ATTR_ID = 11220 and DESCRIPTION.NEWS_ID = NEWS.NEWS_ID  
	";

	function Model_cnews_pub()
	{
		parent::Model();
	}
	
	function _select($limit = 10, $where = "", $order = "DESC")
	{

		$news = $this->db_cnews->query("
			select *
			from (  
			  {$this->main_sql_select}
			  {$this->main_sql_from}
			  where NEWS.NEWS_ID > 510000 and NEWS.NEWSLINE_ID = {$this->lineid} $where
							and DELETE_DATE is NULL and DESCRIPTION.VALUE is not NULL and IMG.VALUE is not NULL
			  order by NEWS.NEWS_DATE desc
			) q
			where rownum <= $limit
		");				         

		$news = $news->result_array();	
		oci_close($this->db_cnews->conn_id);
				
		return $news;		
	} 
	
	function _select_by_id($id = "")
	{
		$news = $this->db_cnews->query("
			  {$this->main_sql_select}
			  {$this->main_sql_from}
			  where NEWS.NEWS_ID = $id and NEWS.NEWSLINE_ID = {$this->lineid}  and DELETE_DATE is NULL and DESCRIPTION.VALUE is not NULL and IMG.VALUE is not NULL
			  order by NEWS.NEWS_DATE desc
		");
		$news = $news->result_array();
		
		oci_close($this->db_cnews->conn_id);
		
		if (!empty($news[0]))
		{
			return $news[0];
		}
		else
		{
			return 0;
		}				
	}
	
	
	function _select_count($where = "")
	{
		$news = $this->db_cnews->query("
		    SELECT count(*) as count 
			{$this->main_sql_from}
			  where NEWS.NEWS_ID > 510000 and NEWS.NEWSLINE_ID = {$this->lineid}  $where
							and DELETE_DATE is NULL and DESCRIPTION.VALUE is not NULL and IMG.VALUE is not NULL
		");
		$news = $news->result_array();
		
		oci_close($this->db_cnews->conn_id);

		return $news[0]['COUNT'];				
	}
	
	function _select_by_page($limit_start = 0, $limit_stop = 15, $where = "")
	{
		$limit_start = $limit_start - 1;

		$sql = "	
			select * from (
			  select aq.*, rownum rn from (
				  {$this->main_sql_select}
				  {$this->main_sql_from}
				  where NEWS.NEWS_ID > 510000 and NEWS.NEWSLINE_ID = {$this->lineid} $where
				  order by NEWS.NEWS_DATE desc
			  ) aq
			  where rownum < $limit_stop
			)
			where rn > $limit_start		
		";
			         
		$news = $this->db_cnews->query($sql);			
		$news = $news->result_array();
		oci_close($this->db_cnews->conn_id);
		
		return $news;
		
	}	
	
}