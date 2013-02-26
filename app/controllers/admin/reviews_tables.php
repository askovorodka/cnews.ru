<?php 

require_once APPPATH . 'controllers/starter.php';

/**
 * Контроллер таблицы обзора
 * @author ashmits by 12.12.2012 10:18
 *
 */

class Reviews_Tables extends Controller
{
	
	public function __construct()
	{
		parent::Controller();
		$this->load->library(array('admin_menu','header_block', 'validate', 'breadcrumbs_block','reviews_collector','exceltotable','generate_table','my_users','my_history','reviews_views'));
		$this->load->helper(array('my_auth','my_url'));
		$this->load->model(array('model_common'));
		
		//проверка авторизации в админке
		if (!$this->my_users->get_active_admin_user())
		{
			My_Url_Helper::redirect(DOMAIN.'/admin/login/?from=' . My_Url_Helper::get_current_url());
		}
		
		$this->my_users->check_permission(array('admin','redactor','reviews_author', 'reviews_writer','reviews_redactor'));
		
		//активное левое меню Обзоры
		$this->admin_menu->set_active_section('reviews');
		//передаем меню в шаблон
		$this->admin_menu->set();
		
		//$this->output->enable_profiler(TRUE);
	}
	
	
	function preview($table_id)
	{
		
		$table = $this->validate->validate_table_by_id(intval($table_id));
		$reviews = $this->validate->validate_reviews_by_id($table['reviews_id']);
		
		if (!empty($table))
		{
			//$table['structure'] = Common_Helper::set_structure_table($table['structure'], $table['description']);
			$table['structure'] = Common_Helper::table_configure_by_limit($table['structure'], $table['description'], null);
		}
		
		$this->smarty->assign('reviews', $reviews);
		$this->smarty->assign('table', $table);
		$this->smarty->display('front/reviews/table_preview');
		
	}
	
	/**
	 * Главная страница таблиц обзоров
	 * @param unknown_type $reviews_id
	 */
	function index($reviews_id=null)
	{
		
		//находим обзор
		$reviews = $this->validate->validate_reviews_by_id($reviews_id, false);
		
		//находим таблицы обзора
		$tables = $this->reviews_views->get_tables_list_by_reviews(intval($reviews_id));
		
		//Блоки для отображения
		$this->header_block->set_title('Обзоры : Таблицы обзора');
		
		$this->breadcrumbs_block->add("Список обзоров", DOMAIN.'/admin/reviews/');
		$this->breadcrumbs_block->add($reviews['name'], DOMAIN.'/admin/reviews/reviews_single/'.$reviews['id'].'/');
		$this->breadcrumbs_block->add("Список таблиц обзора", null);
		
		$this->header_block->set();
		$this->breadcrumbs_block->set();
		
		$this->smarty->assign('reviews', $reviews);
		$this->smarty->assign('tables', $tables);
		$this->smarty->display('admin/reviews/reviews_tables');
		
	}
	
	
	/**
	 * Добавление таблицы
	 * @param int $reviews_id
	 */
	function table_add($reviews_id = null)
	{
		
		$reviews = $this->validate->validate_reviews_by_id($reviews_id, false);
				
		//Блоки для отображения
		$this->header_block->set_title('Обзоры : Добавить таблицу');
		
		$this->breadcrumbs_block->add("Список обзоров", DOMAIN.'/admin/reviews/');
		$this->breadcrumbs_block->add($reviews['name'], DOMAIN.'/admin/reviews/reviews_single/'.$reviews['id'].'/');
		$this->breadcrumbs_block->add("Список таблиц обзора", DOMAIN.'/admin/reviews_tables/'.intval($reviews_id).'/');
		$this->breadcrumbs_block->add("Добавление таблицы", null);
		
		//передаем блоки в шаблон
		$this->header_block->set();
		$this->breadcrumbs_block->set();
		
		$this->smarty->assign('reviews', $reviews);
		$this->smarty->display('admin/reviews/reviews_table_add');
		
	}
	
	/**
	 * Форма конструктора таблицы
	 * @param int $reviews_id
	 */
	function table_generate($reviews_id)
	{
		
		$reviews = $this->validate->validate_reviews_by_id($reviews_id, false);
				
		//Блоки для отображения
		$this->header_block->set_title('Обзоры : Создать таблицу');
		
		$this->breadcrumbs_block->add("Список обзоров", DOMAIN.'/admin/reviews/');
		$this->breadcrumbs_block->add($reviews['name'], DOMAIN.'/admin/reviews/reviews_single/'.$reviews['id'].'/');
		$this->breadcrumbs_block->add("Список таблиц обзора", DOMAIN.'/admin/reviews_tables/'.intval($reviews_id).'/');
		$this->breadcrumbs_block->add("Создание таблицы", null);
		
		//передаем блоки в шаблон
		$this->header_block->set();
		$this->breadcrumbs_block->set();
		
		$this->smarty->assign('reviews', $reviews);
		$this->smarty->display('admin/reviews/reviews_table_generate');
		
	}
	
	/**
	 * Генерация таблицы
	 * @param int $reviews_id
	 */
	function generate_post($reviews_id=null)
	{
		//валидация обзора
		$reviews = $this->validate->validate_reviews_by_id($reviews_id, false);
		
		//генерируем таблицу
		$table = $this->generate_table->empty_table();
		
		//готовим данные для занесения в БД
		$data = $this->reviews_collector->table_editadd(null,$table);
		
		if (!empty($data))
		{
			//добавляем новую таблицу в БД
			$table_id = $this->model_common->insert("reviews_tables", $data);
			
			$this->my_history->add_to_history('reviews_tables','insert', $reviews_id, "Сгенерированна новая таблица &laquo;" . $data['description'] . "&raquo;");
			//перенаправляем на страницу редактирования таблицы, для заполнения
			My_Url_Helper::redirect(DOMAIN.'/admin/reviews_tables/table_edit/' . $table_id.'/');
		}
		
	}
	
	
	/**
	 * Загрузка таблицы
	 * @param unknown_type $reviews_id
	 */
	function table_add_post($reviews_id=null)
	{
		
		/*
		$this->load->library('ExcelReader');
		//$this->excelreader->readfile(ROOT.'/files/24/table_test.xlsx');
		$this->excelreader->readfile(ROOT.'/files/24/price.xls');
		exit();
		*/
		
		$reviews = $this->validate->validate_reviews_by_id($reviews_id, false);
		
		//подключаем либу загрузки файлов
		$config_upload = $this->config->item('table_upload');
		//создаем, если нет, папку /files/id_обзора/ для хранения файлов *.xls
		if (!is_dir($config_upload['upload_path'].'/'.$reviews_id))
		{
			@mkdir($config_upload['upload_path'].'/'.$reviews_id);
			@chmod($config_upload['upload_path'].'/'.$reviews_id, 0777);
		}
		
		$config_upload['upload_path'] = $config_upload['upload_path'] . '/' . $reviews_id;
		$this->load->library('upload', $config_upload);
		
		//грузим файл на сервер
		if (!$this->upload->do_upload("table"))
		{
			show_error("Ошибка загрузки файла " . $this->upload->display_errors());
		}
		
		$data = $this->upload->data();
		
		if (empty($data))
		{
			show_error("Ошибка загрузки файла");
		}
		
		//полный путь до файла на сервере
		$file = $data['full_path'];
		
		//обрабатываем *.xls файл и генерируем таблицы из каждого листа
		$this->exceltotable->read_file($file);
		
		//возвращаем созданные html таблицы в массив
		$tables = $this->exceltotable->get_tables();
		
		
		if (!empty($tables))
		{
			//в одном файле *.xls может быть несколько листов с таблицами
			foreach ($tables as $key=>$val)
			{
				//формируем данные для запроса
				$data = $this->reviews_collector->table_editadd(null, (string)$val);
				//добавляем
				$this->model_common->insert("reviews_tables", $data);
				
				$this->my_history->add_to_history('reviews_tables','insert', $reviews_id, "Спарсена новая таблица &laquo;" . $data['description'] . "&raquo;");
				
			}
		}
		
		//перенаправляем
		My_Url_Helper::redirect(DOMAIN.'/admin/reviews_tables/'.$reviews_id.'/');
		
	}

	
	
	
	/**
	 * Редактирование таблицы
	 * @param int $table_id
	 */
	function table_edit($table_id)
	{
		
		$table = $this->validate->validate_table_by_id($table_id);
	
		$reviews = $this->validate->validate_reviews_by_id($table['reviews_id'], false);
				
		//Блоки
		$this->header_block->set_title('Обзоры : Редактирование таблицы');
		
		$this->breadcrumbs_block->add("Список обзоров", DOMAIN.'/admin/reviews/');
		$this->breadcrumbs_block->add($reviews['name'], DOMAIN.'/admin/reviews/reviews_single/'.$reviews['id'].'/');
		$this->breadcrumbs_block->add("Список таблиц обзора", DOMAIN.'/admin/reviews_tables/'.intval($table['reviews_id']).'/');
		$this->breadcrumbs_block->add("Редактирование таблицы", null);
		
		$this->header_block->set();
		$this->breadcrumbs_block->set();
		
		$this->smarty->assign('table', $table);
		$this->smarty->display('admin/reviews/reviews_table_edit');
	
	}
	
	
	/**
	 * Просмотр отдельной таблицы
	 * @param int $table_id
	 */
	function table_view($table_id = null)
	{

		$table = $this->validate->validate_table_by_id($table_id, false);
				
		$reviews = $this->validate->validate_reviews_by_id($table['reviews_id'], false);
				
		//Блоки
		$this->header_block->set_title('Обзоры : Просмотр таблицы');
		
		$this->breadcrumbs_block->add("Список обзоров", DOMAIN.'/admin/reviews/');
		$this->breadcrumbs_block->add($reviews['name'], DOMAIN.'/admin/reviews/reviews_single/'.$reviews['id'].'/');
		$this->breadcrumbs_block->add("Список таблиц обзора", DOMAIN.'/admin/reviews_tables/'.intval($table['reviews_id']).'/');
		$this->breadcrumbs_block->add("Просмотр таблицы", null);
		
		$this->header_block->set();
		$this->breadcrumbs_block->set();
		
		$this->smarty->assign('table', $table);
		$this->smarty->display('admin/reviews/reviews_table_view');
		
	}
	
	
	function table_edit_post($table_id = null)
	{
		$table = $this->validate->validate_table_by_id($table_id);
	
		$data = $this->reviews_collector->table_editadd($table);
	
		if (!empty($data))
		{
			$this->model_common->update("reviews_tables", $data, array("id" => intval($table_id)));
			
			$this->my_history->add_to_history('reviews_tables','update', $table['reviews_id'], "Обновлена таблица &laquo;" . $data['description'] . "&raquo;");
		}
	
		My_Url_Helper::redirect(DOMAIN.'/admin/reviews_tables/'.$data['reviews_id'].'/');
		
	}
	
	
	/**
	 * Удаление таблицы
	 * @param int $table_id
	 */
	function table_delete($table_id)
	{
		
		$table = $this->validate->validate_table_by_id($table_id);	
		
		$this->model_common->delete("reviews_tables", array("id" => intval($table_id)));
		
		$this->my_history->add_to_history('reviews_tables','delete', $table['reviews_id'], "Удалена таблица &laquo;" . $table['description'] . "&raquo;");
	
		My_Url_Helper::redirect($this->input->server('HTTP_REFERER'));
	
	}
	
	
}

?>