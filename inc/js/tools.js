$(document).ready(function(){
	
	if (Custom.isset($("form#news_add, form#article_add")))
	{
		
		//�������� �������, ����� �������� �����������
		$("input[type='radio'][name='section_main']").change(function(){
			var self = this;
			$("input[type='checkbox']").filter(function(){
				return $(this).attr("name").match(new RegExp('^sections\\[' + $(self).val() + '\\]'));
			}).attr("checked","true");
		});
		
		$("input[type='checkbox']").filter(function()
		{
			
			return $(this).attr("name").match(new RegExp('^sections\\[.*\\]$'));
			
		}).change(function(){
			var section_id = $(this).attr("section_id");
			
			if (typeof $(this).attr("checked") == "undefined")
			{
				$("input[type='radio'][name='section_main']").filter(function(){ return $(this).val() == section_id; }).attr("checked",false);
			}
			
		});
		
	}
	
	if (Custom.isset($('#colorpicker')))
	{
		$('#colorpicker').colorpicker();
	}
	
	
	$("a.reviews_generate").click(function(){
		var reviews_id = parseInt($(this).attr("reviews_id"));
		if (reviews_id > 0)
		{
			$.ajax({
				type : 'post',
				url : protocol + location.hostname + '/admin/ajax/reviews_generate/',
				data : {'reviews_id' : reviews_id},
				success : function(response){
					if ($.parseJSON(response) == 1)
					{
						bootbox.dialog("������� ������������", [{"label" : "�������", "class" : "btn-primary"}]);
					}
				}
			});
		}
		return false;
	});
	
	
	if (Custom.isset($("a.go_table_source_code")))
	{
		$("a.go_table_source_code").click(function(){
			var table_id = parseInt($(this).attr("table_id"));
			
			$.ajax({
				type : 'post',
				url : protocol + location.hostname + '/admin/ajax/get_table_source_code/',
				data : {'table_id' : table_id},
				success : function(response){ 
					if ($.trim(response) != "")
					{
						var table = $.parseJSON(response);
						var description = $.trim(table.description) != "" ? "<p>" + table.description + "</p>\n" : "";
						var source = $.trim(table.source) != "" ? "\n<p>" + table.source + "</p>\n" : "";
						var rating = $.trim(table.rating) != "" ? "<p>" + table.rating + "</p>\n" : "";
						bootbox.dialog($("<textarea></textarea>").addClass("table_source_code").html(description + table.structure + source + rating).click(function(){$(this).select();}), [{
						    "label" : "������� ����",
						    "class" : "btn-primary"
						}]);
					}
				}
			})
			
		});
	}
	
	
	/**
	 * ������ ������� �������� �������������
	 */
	if (Custom.isset($("#tables_list_filter_btn")))
	{
		$("#tables_list_filter_btn").click(function(){
			var hash = Custom.get_hash_from_post( $("#tables_list_filter") );
			if (hash)
			{
				location = protocol + location.hostname + "/admin/tables/tables_list/" + hash + "/";
			}
			
			return false;
			
		});
	}
	
	
	//��������� ������ ���������� ���������� �������
	if (Custom.isset($("table#table_sort")))
	{

		$("tbody tr th a", $("table#table_sort")).each(function(){
			var href = $(this).attr("href");
			$(this).attr("href","#");
			if ($(this).attr("hash") != "")
				{
					var hash = $(this).attr("hash");
					var sort_field = $(this).attr("sort_field");
					var self = this;
					$.ajax({
						url : protocol + location.hostname + '/admin/ajax/get_sort_hash/',
						type : 'post',
						data : {'hash' : hash, 'sort_field' : sort_field},
						success : function(response){ $(self).attr("href", href + '/' + response + '/'); }
					});
				}
		});
	}
	
	
	//��������� ���� ������� ������� � ������
	if (Custom.isset($("input.table_code")))
		{
			$("input.table_code").click(function(){ $(this).select() });
		}
	
	
	//���� ����� �������������� �� ��������
	if (Custom.isset($(".edit_status")))
		{
		
			//���� ������� ������������ �������, ������ �������������� �������
			if (active_user.get_group() == 'reviews_writer' || active_user.get_group() == 'news_writer' )
			{
				$(".edit_status").remove();
			}
		}
	
	
	//������� ������ �� �������������� ���������� ������� ������������ �� �� ������ �������� � ����� 
	if (Custom.isset($("table tr td[user_id]")))
		{
			
			//����� ������������
			var cnf = new Config();
			
			$("table tr td[user_id]").each(function(){
				
				if ($(this).attr("user_id") != active_user.get_id())
					{
						//���� ������������ �� �� ��������� ������ � �� ��� ��������, ������� ������ �������������� � ��������
						if ( $.inArray(active_user.get_group(), cnf.get_admin_group()) < 0 )
							{
								$(this).empty();
								$(this).parent().addClass("disabled");
							}
					}
				
			});
			
		}
	
	
	/**
	 * ������������� ������ �������� � �������
	 */
	if (Custom.isset($("#table_sections")))
		{
			$("#table_sections tr:not(:nth-child(1))").each(function(){
				var class_name = $(this).attr("class");
				var level = parseInt( $(this).attr("class").replace('level_',"") );
				if (level > 1)
					$("td:nth-child(2)", $(this)).css("padding-left", level*20);
			});
		}
	
	
	/**
	 * ������������� ������ �������� � select
	 */
	if (Custom.isset($("#sections_select")))
		{
			$("option", $(this)).each(function(){
				var class_name = $(this).attr("class");
				if (typeof class_name == 'string')
					{
						var level = parseInt( class_name.replace('level_',"") );
						if (level > 0)
							{
								$(this).html( Custom.to_nbsp(level*2) + $(this).html() );
								if (level >= 2)
								{
									if ($("#sections_select").attr("name") != "filter_sections")
									{
										$(this).attr("disabled","true");
									}
								}
							}
					}
			});
		}
	
	
	/**
	 * ������ ������� �������� �������������
	 */
	$("#change_filter_btn").click(function(){
		
		var hash = Custom.get_hash_from_post($("#change_history_filter"));
		if (hash)
		{
			location = protocol + location.hostname + "/admin/users/history_changes/" + hash + "/";
		}
		
		return false;
		
	});
	
	
	//���������� �������� ��� ������������� ���������-���� ��������
	if (Custom.isset($("form#headers_content_types_checked")))
		{
			//������ ������
		 	$("input[article]").change(function(){ 
		 		
		 		var article_id = parseInt($(this).attr("article"));
		 		var name = $(this).attr("name");
		 		if ($(this).is(":checked"))
		 			{
		 				$("input[article='" + article_id + "']").not(this)
		 				.attr("checked", false)
		 				.attr("disabled","true")
		 				.siblings("label").addClass("label_disabled");
		 			}
		 		else
		 			{
		 				$("input[article='" + article_id + "']").not(this)
		 				.attr("checked", false)
		 				.attr("disabled",false)
		 				.siblings("label").removeClass("label_disabled");
		 			}
		 		
		 	});
		 	
		 	//������ ��������
		 	$("input[interview]").change(function(){ 
		 		
		 		var interview_id = parseInt($(this).attr("interview"));
		 		var name = $(this).attr("name");
		 		if ($(this).is(":checked"))
		 			{
		 				$("input[interview='" + interview_id + "']").not(this)
		 				.attr("checked", false)
		 				.attr("disabled","true")
		 				.siblings("label").addClass("label_disabled");
		 			}
		 		else
		 			{
		 				$("input[interview='" + interview_id + "']").not(this)
		 				.attr("checked", false)
		 				.attr("disabled",false)
		 				.siblings("label").removeClass("label_disabled");
		 			}
		 		
		 	});
		 	
		 	//������ �����
		 	$("input[case]").change(function(){ 
		 		
		 		var case_id = parseInt($(this).attr("case"));
		 		var name = $(this).attr("name");
		 		if ($(this).is(":checked"))
		 			{
		 				$("input[case='" + case_id + "']").not(this)
		 				.attr("checked", false)
		 				.attr("disabled","true")
		 				.siblings("label").addClass("label_disabled");
		 			}
		 		else
		 			{
		 				$("input[case='" + case_id + "']").not(this)
		 				.attr("checked", false)
		 				.attr("disabled",false)
		 				.siblings("label").removeClass("label_disabled");
		 			}
		 		
		 	});
		 	
		 	//������ �������
		 	$("input[table]").change(function(){ 
		 		
		 		var table_id = parseInt($(this).attr("table"));
		 		var name = $(this).attr("name");
		 		if ($(this).is(":checked"))
		 			{
		 				$("input[table='" + table_id + "']").not(this)
		 				.attr("checked", false)
		 				.attr("disabled","true")
		 				.siblings("label").addClass("label_disabled");
		 			}
		 		else
		 			{
		 				$("input[table='" + table_id + "']").not(this)
		 				.attr("checked", false)
		 				.attr("disabled",false)
		 				.siblings("label").removeClass("label_disabled");
		 			}
		 		
		 	});
		 	
		}
	
	$("#headers_table_sort").tableDnD();
	
	$("form#table_edit").submit(function(){
		
		if ( $(this).valid() )
		{
				
				if (Custom.isset($("table.from_excel")))
				{
					$("tr.add_new_row").remove();
					//������� ������ � ������� �������� �����
					$("td.delete", $("table.from_excel")).remove();
					//����� �� �������, ������� ����� �� input � ��������� ��� � html, ����� ������� input�
					$("th,td", $("table.from_excel")).each(function(){
						var cell = this;
						var value = $("input[type='text']", $(cell)).val();
						$("input[type='text']", $(cell)).remove();
						$(cell).html(value);
					});
					
					$("<input></input>").attr({"name" : "structure", "type" : "hidden"}).val( $("table.from_excel").parent().html() ).appendTo($(this));
					return true;
				}
			
		}
		return false;
	});
	
	
	/*
	 * ������������� ������� ��� ��������������
	 */
	if (Custom.isset($("div.edit_table")) && Custom.isset("table.from_excel", $("div.edit_table")))
	{
		$("td,th", $("table.from_excel")).each(function(){
			var table = this;
			var content = $(table).html();
			$(table).html("").append($("<input></input>").attr({"value" : content,"type":"text"}));
		});
		
		//������� ������ �������� �����
		$("tr", $("table.from_excel")).each(function(){
			//������� ���.������ ��� ������ ��������
			$("<td></td>").addClass("delete").insertAfter( $("th:last,td:last", $(this)) );
		});
		
		//��������� ��������� ������ �������
		$("tr:last", $("table.from_excel")).clone().addClass("add_new_row").insertAfter($("tr:last", $("table.from_excel")));
		$("td:last", $("tr.add_new_row")).removeClass("delete").empty().append( Custom.add_row_button() );
		$("tr.add_new_row td input").val("");
		
		//��������� ������ � ���.������
		$("td.delete").append( Custom.delete_row_button() );
	}

	//����������
	if (Custom.isset($("#date_picker")))
	{
		$('#date_picker').datetimepicker({
			timeFormat:'HH:mm:ss',
			dateFormat:'dd.mm.yy'
		});
	}

	if (Custom.isset($("#date_start_picker")))
	{
		$('#date_start_picker').datetimepicker({
			timeFormat:'HH:mm:ss',
			dateFormat:'dd.mm.yy'
		});
	}


	if (Custom.isset($("#date_end_picker")))
	{
		$('#date_end_picker').datetimepicker({
			timeFormat:'HH:mm:ss',
			dateFormat:'dd.mm.yy'
		});
	}

	/**
	 * ������ �� �������� ������
	 */
	$(".article_delete").click(function(e){
		var self = this;
		e.preventDefault();
		bootbox.confirm("������� ������ ?", function(response){ 
			if (response)
			{
				//�������������� �� �������� ������
				location = $(self).attr("href");
			}
		});
	
	});
	

	/**
	 * �������� �������
	 */
	$(".news_delete").click(function(e){
		var self = this;
		e.preventDefault();
		bootbox.confirm("������� ������� ?", function(response){ 
			if (response)
			{
				//�������������� �� �������� �����
				location = $(self).attr("href");
			}
		});
	
	});
	
	
	/**
	 * �������� ������� �����
	 */
	$(".section_delete").click(function(e){
		var self = this;
		e.preventDefault();
		bootbox.confirm("������� ������ � ��� ���������� ?", function(response){ 
			if (response)
			{
				//�������������� �� �������� �����
				location = $(self).attr("href");
			}
		});
	
	});


	/**
	 * �������� ������������
	 */
	$(".user_delete").click(function(e){
		var self = this;
		e.preventDefault();
		bootbox.confirm("������� ������������ ?", function(response){ 
			if (response)
			{
				//�������������� �� �������� �����
				location = $(self).attr("href");
			}
		});
	
	});


	/**
	 * �������� �������
	 */
	$(".table_delete").click(function(e){
		var self = this;
		e.preventDefault();
		bootbox.confirm("������� ������� ?", function(response){ 
			if (response)
			{
				//�������������� �� �������� �����
				location = $(self).attr("href");
			}
		});
	
	});
	
	
	/**
	 * �������� �����
	 */
	$(".case_delete").click(function(e){
		var self = this;
		e.preventDefault();
		bootbox.confirm("������� ���� ?", function(response){ 
			if (response)
			{
				//�������������� �� �������� �����
				location = $(self).attr("href");
			}
		});
	
	});
	
	
	/**
	 * �������� ��������
	 */
	$(".interview_delete").click(function(e){
		var self = this;
		e.preventDefault();
		bootbox.confirm("������� �������� ?", function(response){ 
			if (response)
			{
				//�������������� �� �������� ��������
				location = $(self).attr("href");
			}
		});
	
	});
	
	
	/**
	 * �������� ������
	 */
	$(".articles_delete").click(function(e){
		var self = this;
		e.preventDefault();
		bootbox.confirm("������� ������ ?", function(response){ 
			if (response)
			{
				//�������������� �� �������� ������
				location = $(self).attr("href");
			}
		});
	
	});
	
	
	/**
	 * ���� ��������� ����� ���������� ������, �������������� wysivig
	 */
	if ( Custom.isset($("form#articles_add")) || Custom.isset($("form#interview_add")) || Custom.isset($("form#case_add")) || Custom.isset($("form#reviews_add")) )
		{
		
			$(".small_text").wysihtml5();
			$(".text").wysihtml5();
			
			if (Custom.isset($("form#reviews_add")))
			{
				$(".pre_release").wysihtml5();
			}
			
		}


	/*
	 * ������ �� �������� ������
	 */
	$(".reviews_delete").click(function(e){
		var self = this;
		e.preventDefault();
		bootbox.confirm("������� ����� ?", function(response){ 
			if (response)
			{
				//�������������� �� �������� ������
				location = $(self).attr("href");
			}
		});
	
	});
	
	
	/**
	 * �������� ���������� ���������
	 */
	$(".headers_delete").click(function(e){
		var self = this;
		e.preventDefault();
		bootbox.confirm("������� ��������� ?", function(response){ 
			if (response)
			{
				//�������������� �� �������� ������
				location = $(self).attr("href");
			}
		});
		
	});
	
	
	if ($("#sortable_headers"))
	{
		$( "#sortable_headers" ).sortable();
		$( "#sortable_headers" ).disableSelection();		
	}
	
	//��������� ���������
	$.datepicker.regional['ru'] = {
		closeText: '�������',
		prevText: '<����',
		nextText: '����>',
		currentText: '�������',
		monthNames: ['������','�������','����','������','���','����',
		'����','������','��������','�������','������','�������'],
		monthNamesShort: ['���','���','���','���','���','���',
		'���','���','���','���','���','���'],
		dayNames: ['�����������','�����������','�������','�����','�������','�������','�������'],
		dayNamesShort: ['���','���','���','���','���','���','���'],
		dayNamesMin: ['��','��','��','��','��','��','��'],
		weekHeader: '��',
		dateFormat: 'dd.mm.yy',
		firstDay: 1,
		isRTL: false,
		showMonthAfterYear: false,
		yearSuffix: ''
	};
	$.datepicker.setDefaults($.datepicker.regional['ru']);
	
});