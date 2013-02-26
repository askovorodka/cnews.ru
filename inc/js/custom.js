//пользовательские функции

var protocol = (location.hostname == 'v2.adm.cnews.ru') ? 'https://' : 'http://';

var Custom = function(){
	try{
		/**
		 * получает хэш из инпутов формы
		 */
		this.get_hash_from_post = function(form){
			if( this.is_object(form) )
			{
				var hash =
						$.ajax({
						type : 'post',
						url : protocol + location.hostname + '/admin/ajax/get_hash_from_post/',
						data : $(form).serialize(),
						async : false,
						cache : false
						}).responseText;
				if ($.trim(hash) != "")
				{
					return $.parseJSON(hash);
				}
				
				return null;
				
			}
		},
		
		this.isset = function(element){
			if (typeof $(element).html() != 'undefined')
			{
				return $(element);
			}
			return false;
		},
		
		this.is_object = function(element)
		{
			return typeof(element) == "object"
		},
		
		this.to_nbsp = function(num){
			str="";
			for(i=1; i<=num; i++) 
				str = str + "&nbsp;";
			return str;
		},
		
		this.delete_row_button = function(){ return $("<button></button>").attr("type","button").addClass("btn").html("Удалить").click(function(){ $(this).parent().parent().remove(); }); },
		
		this.add_row_button = function(){
			//кнопка добавления новой строки
			return $("<button></button>").attr("type","button").addClass("btn btn-success").html("Добавить").click(function(){
				//клонируем новую строчку
				var new_row = $("tr.add_new_row").clone();
				//добавляем класс удаления в новую строчку
				$("td:last", $(new_row)).addClass("delete");
				//вставляем новую строчку после перед добавочной строкой
				$(new_row).insertBefore( $("tr.add_new_row") );
				//удаляем кнопку Добавить из клонированной строки
				$("button", $("td:last", $(new_row))).remove();
				//удаляем класс
				$(new_row).removeClass("add_new_row");
				//добавляем кнопку в новую добавленную строку Удалить
				$("td:last", $(new_row)).append( Custom.delete_row_button() );
				//очищаем инпуты
				$("input", $("td", $("tr.add_new_row"))).val("");
			});

		}

	}catch(e){}
};



/**
 * Объект активного пользователя админки
 */
var User = function()
{
	try
	{
		this.get_group = function(){ return this.user.group_name; },
		this.get_login =  function(){ return this.user.user_login },
		this.get_id = function(){ return this.user.user_id },
		this.get_name = function(){ return this.user.user_name }
	}catch(e){}
};


/**
 * Конфиг
 */
var Config = function()
{
	try
	{
		this.get_admin_group = function(){ return this.items.redactor_group }
	}catch(e){}
}

var Custom = new Custom();
var active_user =  new User();

//конструктор класса User, полчает данные о пользователе
try{
User.prototype.user = $.parseJSON(
			$.ajax({
			url : protocol + location.hostname + '/admin/ajax/get_active_user/',
			type : 'get',
			async : false,
			cache : false
			}).responseText);
}catch(e){}

//конструктор класса Config
try{
Config.prototype.items = $.parseJSON(
		$.ajax({
		url : protocol + location.hostname + '/admin/ajax/get_config/',
		type : 'get',
		async : false,
		cache : false
		}).responseText);
}catch(e){}
