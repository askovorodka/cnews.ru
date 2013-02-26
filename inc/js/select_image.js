/**
 * Выборка картинок
 */

	var ImageLoader = function(){
		try{
			
		this.current_element = null,
		
		this.current_iframe = null,
		
		this.return_to_wisiwyg = null,
		
		this.set_current_element = function(element){ this.current_element = eval(element); },
		
		this.iframe_class = "layout",
		
		this.current_wisiwyg = "",
		
		this.render_iframe = function(iframe){ 
			
			try{
				return $(iframe).css({ "left" : this.current_element.offset().left, "top" : this.current_element.offset().top });
			}
			catch(e){ $.error('Не задан текущий элемент'); }
			
			},
		
		this.insert = function(iframe){
				
				/**
				 * Формируем слой вида 
				 * 	<div absolute>
				 * 		<div relative>
				 * 			<div absolute>button</div>
				 * 			<div absolute>iframe</div>
				 * 		</div>
				 * 	</div>
				 */
				var btn_div = $("<div></div>").addClass("btn_div");
				var iframe_div = $("<div></div>").addClass("iframe_div");
				var container = $("<div></div>").addClass("container");
				var layout = $("<div></div>").addClass("layout");
				var btn = $("<button class='btn btn-inverse btn-small'>Закрыть</button>").attr("id","close_image_layer");
				
				$(iframe_div).append($(iframe));
				$(btn_div).append($(btn));
				$(container).append($(iframe_div));
				$(container).append($(btn_div));
				$(layout).append($(container));
				
				return $(layout).appendTo("body");
				
		},
		
		this.remove_iframe = function(){ $("." + this.iframe_class).remove(); this.current_iframe = null; }
		
		this.image = function(){ return $("<img></img>").attr("src", this.get_src()); },
		
		this.get_src = function(){  },
		
		this.create_image = function(src){ return $("<img></img>").attr("src", src); },
		
		this.set_hide = function(element){ 
			$(element).click(function(){
				var elem = new ImageLoader(); 
				elem.remove_iframe();
			});
		},
		
		this.set_return_to_wisiwyg = function(){
			
			this.return_to_wisiwyg = true;
			
		},
		
		this.create_frame = function(src){
			
			this.remove_iframe();
			
			this.current_iframe = this.render_iframe(  this.insert( $("<iframe></iframe>").attr("src", src).addClass(this.iframe_class) ) );
			
			if (this.return_to_wisiwyg == true)
			{
				this.result_to_editor();
			}
			else
			{
				this.iframe_worked();
			}
		},
		
		this.set_frame_class = function(class_name){ this.iframe_class = class_name; }
		
		this.iframe_worked = function(){
		
			var self = this;
			
			if (Custom.is_object(this.current_iframe))
			{
				$("iframe", this.current_iframe).load(function(){
		            
					var iframe = this.contentWindow.document;
					
					$(iframe).ready(function(){

						$("input#link_to_file", $(iframe)).click(function(){
							if ($.trim($(this).val()) != "")
							{
								$(this).select();
								$(self.current_element).val( $(this).val() );
								self.remove_iframe();
							}
						});

					});
					
				});
			}
			
		},
		
		this.result_to_editor = function(){
			
			var self = this;
			
			if (Custom.is_object(this.current_iframe))
			{
				$("iframe", this.current_iframe).load(function(){
		            
					var iframe = this.contentWindow.document;
					
					$(iframe).ready(function(){

						$("input#link_to_file", $(iframe)).click(function(){
							if ($.trim($(this).val()) != "")
							{
								
								$(this).select();
								
								try{
									//в IE не пашет
									self.current_wisiwyg.composer.commands.exec("insertImage", $(this).val() );
									
								}catch(error){ alert( "Ошибка IE :( попробуйте другой браузер." ); }
								
								self.remove_iframe();
								
							}
						});

					});
					
				});
			}

		}
		
		}catch(e) { console.log(e.toString()); }
		
	}


$(document).ready(function(){
	
	var image = new ImageLoader();
	
	$("a.select_image").click(function(){
		
		image.set_current_element( eval($("#" + $(this).attr("for"))) );
		image.create_frame("/admin/ajax/get_remote_url/?url=https://adm.zoom.cnews.ru/index.php/file_uploader/");
		image.set_hide( $("#close_image_layer") );
		return false;
	});
	
});