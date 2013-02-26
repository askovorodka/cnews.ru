/*
 * ���������� ����
 */

$(document).ready(function(){

	$.validator.addMethod('filesize', function(value, element, param){
		return this.optional(element) || (element.files[0].size <= param)
	});
	
	
	//��������� �����, �������� �� �������������
	var response_login = true;
	$.validator.addMethod('login_exist', function(value, element){
		
		$.ajax({
			url : protocol + location.hostname + "/admin/ajax/get_user_by_login/",
			type : "post",
			data : {user_login : value},
			success : function(response_server){
				//������������ ����� �������
				is_object(response_server) ? response_login = false : response_login = true;
			}
		});
		
		return response_login;
		
	});

	
	//��������� email, �������� �� �������������
	var response_email = true;
	$.validator.addMethod('email_exist', function(value, element){
		
		$.ajax({
			url : protocol + location.hostname + "/admin/ajax/get_user_by_email/",
			type : "post",
			data : {user_email : value},
			success : function(response_server){
				//������������ ����� �������
				is_object(response_server) ? response_email = false : response_email = true;
			}
		});
		
		return response_email;
		
	});

	
	/**
	 * ��������� ����������/�������������� ������
	 */
	$("form#article_add").validate({

		ignore : ".ignore",

		rules : {
			article_title: {required : true},
			article_annonce: {required : true},
			article_text: {required : true}
		},

		messages : {
			article_title : {required : " "},
			article_annonce : {required : " "},
			article_text : {required : " "}
		},

		highlight : function(label){
			$(label).closest('.control-group').addClass('error');
		},

		success: function(label) {
			label
			   .addClass('valid')
			   .closest('.control-group').addClass('success');
		}
		
	});
	
	
	
	/**
	 * ��������� ����������/�������������� ��������
	 */
	$("form#news_add").validate({

		ignore : ".ignore",

		rules : {
			news_title: {required : true},
			news_annonce: {required : true},
			news_text: {required : true}
		},

		messages : {
			news_title : {required : " "},
			news_annonce : {required : " "},
			news_text : {required : " "}
		},

		highlight : function(label){
			$(label).closest('.control-group').addClass('error');
		},

		success: function(label) {
			label
			   .addClass('valid')
			   .closest('.control-group').addClass('success');
		}
		
	});
	
	/**
	 * ����� ������� � ��������� �����
	 */
	$("form#news_add").submit(function(){
		var array = new Array();
		$("ul li input[type='radio']:checked", $("#sections")).each(function(){
			array[array.length] = $(this).val();
		});
		
		if (array.length > 0)
		{
			$("<input type='hidden' name='sections_main'>").val( array.join() ).appendTo($("form#news_add"));
		}
		
	});

	/**
	 * ��������� ���������� ������� �����
	 */
	$("form#section_add").validate({

		ignore : ".ignore",

		rules : {
			section_name: {required : true}
		},

		messages : {
			section_name : {required : " "}
		},

		highlight : function(label){
			$(label).closest('.control-group').addClass('error');
		},

		success: function(label) {
			label
			   .addClass('valid')
			   .closest('.control-group').addClass('success');
		}

	});
	
	/**
	 * ��������� ����� ����������/�������������� ������������
	 */
	$("form#user_add").validate({

		ignore : ".ignore",

		rules : {
			group_name: {required : true},
			user_login: {required : true, login_exist : true},
			user_email: {required : true, email : true, email_exist : true},
			user_name:  {required : true},
			user_password:  {required : true}
		},

		messages : {
			group_name : {required : " "},
			user_login : {required : " ", login_exist : "������������ � ����� ������� ��� ����"},
			user_email : {required : " ", email : "�������� ������ email", email_exist : "������������ � ����� email ��� ����"},
			user_name :  {required : " "},
			user_password :  {required : " "}
		},

		highlight : function(label){
			$(label).closest('.control-group').addClass('error');
		},

		success: function(label) {
			label
			   .addClass('valid')
			   .closest('.control-group').addClass('success');
		}

	});


	/**
	 * ��������� ����� �������� �������
	 */
	$("form#form_login").validate({
		
		ignore : ".ignore",
		
		rules : {
			user_login: {required : true},
			user_password: {required : true}
		},
		
		messages : {
			user_login : {required : " "},
			user_password : {required : " "}
		},
		
		highlight : function(label){
			$(label).closest('.control-group').addClass('error');
		},
		
		success: function(label) {
			label
			   .addClass('valid')
			   .closest('.control-group').addClass('success');
		}
	
	});
	
	
	/**
	 * ��������� ����� �������� �������
	 */
	$("form#generate_table").validate({
		
		ignore : ".ignore",
		
		rules : {
			rows_count: {required : true, number:true},
			cols_count: {required : true, number:true}
		},
		
		messages : {
			rows_count : {required : " ", number : "������� �����"},
			cols_count : {required : " ", number : "������� �����"}
		},
		
		highlight : function(label){
			$(label).closest('.control-group').addClass('error');
		},
		
		success: function(label) {
			label
			   .addClass('valid')
			   .closest('.control-group').addClass('success');
		}
	
	});
	
	/**
	 * ��������� ����� ��������������
	 */
	var edit_table_validate = $("form#table_edit").validate({
		
		ignore : ".ignore",
		
		rules : {
			description : {required : true}
		},
		
		messages : {
			description : {required : " "}
		},
		
		highlight : function(label){
			$(label).closest('.control-group').addClass('error');
		},
		
		success: function(label) {
			label
			   .addClass('valid')
			   .closest('.control-group').addClass('success');
		}
	
	});
	
	
	
	/**
	 * ��������� ����� ���������� �������
	 */
	$("form#table_add").validate({
		
		ignore : ".ignore",
		
		rules : {
			description : {required : true},
			table : {required : true}
		
		},
		
		messages : {
			description : {required : " "},
			table : {required : " "}
		},
		
		highlight : function(label){
			$(label).closest('.control-group').addClass('error');
		},
		
		success: function(label) {
			label
			   .addClass('valid')
			   .closest('.control-group').addClass('success');
		}
		
	});
	
	
	/**
	 * ��������� ����� ���������� ��������
	 */
	$("form#interview_add").validate({
		
		ignore : ".ignore",
		
		rules : {
			person : {required : true},
			description : {required : true},
			small_text : {required : true},
			text : {required : true},
			image : {required : true},
			logo : {required : true},
			logo_url : {required : true}
		},
		
		messages : {
			person : {required : " "},
			description : {required : " "},
			small_text : {required : " "},
			text : {required : " "},
			image : {required : " "},
			logo : {required : " "},
			logo_url : {required : " "}
		},
		
		highlight : function(label){
			$(label).closest('.control-group').addClass('error');
		},
		
		success: function(label) {
			label
			   .addClass('valid')
			   .closest('.control-group').addClass('success');
		}
		
	});

	$("#reviews_interviews_add_btn").click(function(){
		if ( $("form#interview_add").valid() )
		{
			$("form#interview_add").submit();
		}
	});
	
	
	/**
	 * ��������� ����� ���������� �����
	 */
	$("form#case_add").validate({
		
		ignore : ".ignore",
		
		rules : {
			name : {required : true},
			small_text : {required : true},
			text : {required : true},
			image : {required : true},
			banner_image : {required : true},
			banner_url : {required : true}
		},
		
		messages : {
			name : {required : " "},
			small_text : {required : " "},
			text : {required : " "},
			image : {required : " "},
			banner_image : {required : " "},
			banner_url : {required : " "}
		},
		
		highlight : function(label){
			$(label).closest('.control-group').addClass('error');
		},
		
		success: function(label) {
			label
			   .addClass('valid')
			   .closest('.control-group').addClass('success');
		}
		
	});
	
	$("#reviews_case_add_btn").click(function(){
		if ( $("form#case_add").valid() )
		{
			$("form#case_add").submit();
		}
	});
	

	
	/**
	 * ��������� ����� ���������� ������
	 */
	$("form#articles_add").validate({
		
		ignore : ".ignore",
		
		rules : {
			name : {required : true},
			small_text : {required : true},
			text : {required : true},
			image : {required : true}
		},
		
		messages : {
			name : {required : " "},
			small_text : {required : " "},
			text : {required : " "},
			image : {required : " "}
		},
		
		highlight : function(label){
			$(label).closest('.control-group').addClass('error');
		},
		
		success: function(label) {
			label
			   .addClass('valid')
			   .closest('.control-group').addClass('success');
		}
		
	});

	$("#reviews_articles_add_btn").click(function(){
		if ( $("form#articles_add").valid() )
		{
			$("form#articles_add").submit();
		}
	});
	
	
	/**
	 * ��������� ����� ���������� ������
	 */
	$("form#reviews_add").validate({
		rules : {
			name : {required : true}
		},
		messages : {
			name : {required : ' '}
		},
		highlight : function(label){
			$(label).closest('.control-group').addClass('error');
		},
		success: function(label) {
			label
			   .addClass('valid')
			   .closest('.control-group').addClass('success');
		}
		
	});

	
	$("#reviews_add_btn").click(function(){
		if ( $("form#reviews_add").valid() )
		{
			$("form#reviews_add").submit();
		}
	});
	
	/**
	 * ��������� ����� ���������� ���������
	 */
	$("form#add_header").validate({
		rules : {
			header_name : {required : true}
		},
		messages : {
			header_name : {required : "������� �������� ���������"}
		},
		highlight : function(label){
			$(label).closest('.control-group').addClass('error');
		},
		success: function(label) {
			label
			   .addClass('valid')
			   .closest('.control-group').addClass('success');
		}
		
	});
	
	
});