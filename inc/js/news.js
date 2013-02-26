$(document).ready(function(){
	
	
	//раскрываем подразделы, если они отмеченные
	if (Custom.isset($("ul.sections")))
	{
		
		$("ul.sections")
			//все вложенные разделы
			.filter(function(){ return $(this).hasClass("level1"); })
			//.css({"position" : "absolute", "float" : "left"})
			//все отмеченные чекбоксы разделов
			.filter(function(){ return $("li input:checked", $(this)).length > 0; })
			//делаем видимым
			.show()
			//ищем лейбл с плюсом
			.parent().children().filter(function(){ return $(this).hasClass('children_sections'); })
			//делаем минус, так как подразделы раскрыты
			.removeClass('icon-plus').addClass('icon-minus');
		
	}
	
	
	//автосохранение новости
	function news_save()
	{
		
		if ( $("form#news_add").valid() )
		{
			$("form#news_add").append( $("<input type='hidden' name='with_ajax' id='with_ajax' value='1'>") );
			$("#auto_save").html("Сохраняем новость...").fadeIn("fast");
			$.ajax({
				type : 'post',
				url : $("form#news_add").attr("action"),
				data : $("form#news_add").serialize(),
				success : function(response)
				{ 
					$("#with_ajax").remove();
					var news_id = $.parseJSON(response);
					$("form#news_add").attr("action", protocol + location.hostname + '/admin/news/news_edit_post/' + news_id + '/');
					$("button[type='submit']", $("form#news_add")).html('<i class="icon-ok"></i> Изменить новость');
					$("#auto_save").delay(3000).fadeOut("fast");
				}
			});
			
		}
	}

	/**
	 * Автосохранение статьи
	 */
	function article_save()
	{
		
		if ( $("form#article_add").valid() )
		{
			$("form#article_add").append( $("<input type='hidden' name='with_ajax' id='with_ajax' value='1'>") );
			$("#auto_save").html("Сохраняем статью...").fadeIn("fast");
			$.ajax({
				type : 'post',
				url : $("form#article_add").attr("action"),
				data : $("form#article_add").serialize(),
				success : function(response)
				{
					$("#with_ajax").remove();
					var article_id = $.parseJSON(response);
					$("form#article_add").attr("action", protocol + location.hostname + '/admin/articles/article_edit_post/' + article_id + '/');
					$("button[type='submit']", $("form#article_add")).html('<i class="icon-ok"></i> Изменить статью');
					$("#auto_save").delay(3000).fadeOut("fast");
				}
			});
			
		}
	}
	
	
	//каждые пол минуты сохраняем редактируемую/добавляемую новость
	if (Custom.isset($("form#news_add")))
	{
		if ( $("input[name='current_status']", $("form#news_add")).val() == 0 )
		{
			window.setInterval(news_save, 60000);
		}
	}

	if (Custom.isset($("form#article_add")))
	{
		if ( $("input[name='current_status']", $("form#article_add")).val() == 0 )
		{
			window.setInterval(article_save, 60000);
		}
	}
	
	
	
	/**
	 * Обработка фильтра новостей
	 * @author ashmits by 07.02.2013 13:10
	 */
	var form;
	if ((form = Custom.isset($("form#news_list_filter")))  ||  (form = Custom.isset($("form#articles_list_filter"))))
	{
		
		$("input[name='filter_tags']", $(form)).autocomplete({
            source: function (request, response) {
                $.ajax({
                    url: protocol + location.hostname + '/admin/ajax/search_tags/'  + (Custom.isset($("form#articles_list_filter")) ? 'articles/' : ''),
                    type: "POST",
                    data : {'query' : $("input[name='filter_tags']", $(form)).val()},
                    success: function (data) {
                        response($.map(data, function (item) {
                            return {
                                label: item.tags_name,
                                value: item.tags_name
                            }
                        }))
                    },
                    error: function (a, b, c) {
                        debugger;
                    }
                });

            },
            minLength: 1
        });		
		
		$("button[type='submit']", $(form)).click(function(){
			
			var hash = Custom.get_hash_from_post($(form));
			if (hash)
			{
				location = $(form).attr("action") + hash + '/';
			}
			
			return false;
			
		});
	}

	
	
	/**
	 * Подразделы в списке подключенных разделов новости
	 */
	$("label.children_sections").click(function(){
		if ($(this).hasClass("icon-plus"))
		{
			$($(this).prop("for"), $(this).parent()).fadeIn("fast");
			$(this).removeClass("icon-plus").addClass("icon-minus");
		}
		else
		{
			$($(this).prop("for"),$(this).parent()).fadeOut("fast");
			$(this).removeClass("icon-minus").addClass("icon-plus");
		}
	});
	
	
	
	
	
	if (Custom.isset($("form#news_add")) || Custom.isset($("form#article_add")))
	{
		$(".small_text").wysihtml5();
		var myCustomTemplates = {
				  html : function(locale) {
				    return "<li>" +
				           "<div class='btn-group'>" +
				           "<a class='btn' data-wysihtml5-command='tags' title='" + locale.html.edit + "'>Добавить в теги</a>" +
				           "</div>" +
				           "</li>";
				  }
				}
		$('.text').wysihtml5({
		   customTemplates: myCustomTemplates
		});
	}
	
	
});