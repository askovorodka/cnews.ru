{include file="admin/blocks/header_block.tpl"}

{include file="admin/blocks/left_menu_block.tpl"}

{include file="admin/blocks/breadcrumbs_block.tpl"}

<div class="content">
	
	<h2>Структура обзора: {$reviews.name}</h2>

	<div class="tabbable">
	  <ul class="nav nav-tabs">
		
	    <li class="active"><a href="#reviews" data-toggle="tab">Общая информация</a></li>
	    <li><a href="#headers" data-toggle="tab">Заголовки глав</a></li>
	    <li><a href="#articles" data-toggle="tab">Статьи</a></li>
	    <li><a href="#interviews" data-toggle="tab">Интервью</a></li>
	    <li><a href="#cases" data-toggle="tab">Кейсы</a></li>
	    <li><a href="#tables" data-toggle="tab">Таблицы</a></li>
		
	  </ul>
	  
	  <div class="tab-content">
	    <div class="tab-pane active" id="reviews">
	      
	      <table class="reviews_table">
	      		<tr>
	      			<td colspan="2"><h3>{$reviews.name|strip_tags}</h3></td>
	      		</tr>
	      		<tr>
	      			<td style="width:270px;">{if $reviews.image|isimage}<img src="{$reviews.image}" alt="Нет фото" />{else}Нет фото{/if}</td>
	      			<td><small><i>Дата: {$reviews.date}</i></small><br /><small><i>Статус обзора: {if $reviews.review_status == 1}Включен{else}Выключен{/if}</i></small><br />{$reviews.text}</td>
	      		</tr>
	      		<tr>
	      			<td><a href="{$reviews.banner_url}">{if $reviews.banner_image|isimage}<img src="{$reviews.banner_image}" alt="Нет фото" />{else}Нет фото{/if}</a>
	      			{if $reviews.banner_right_image|isimage}&nbsp;&nbsp;&nbsp;<a href="{$reviews.banner_right_url}"><img src="{$reviews.banner_right_image}" /></a>{/if}
	      			</td>
	      			<td></td>
	      			
	      		</tr>
	      </table>
	      
		<div class="control-group">
			<button type="button" class="btn" onClick="location='{$DOMAIN}/admin/reviews/reviews_edit/{$reviews.id}/'"><i class="icon-edit"></i> Редактировать обзор</button>
		</div>
		
		<hr>
		
		{include file="admin/reviews/reviews_types_block.tpl"}
		
	    </div>
	    
	    <div class="tab-pane" id="headers">
	    	<table class="table table-striped th_clear">
	    		<tr>
	    			<th>Название</th><th>Определенные контент-типы</th>
	    		</tr>
		      	{foreach from=$headers item=header}
		      		<tr>
		      			<td><a href="{$DOMAIN}/admin/reviews/reviews_headers_types/{$reviews.id}/{$header.id}/">{$header.name}</a></td>
		      			<td>{foreach from=$header.structure item=item name=struct}{$item.name}{if !$smarty.foreach.struct.last}, {/if}{/foreach}</td>
		      		</tr>
		      	{/foreach}
	    	</table>

			<div class="control-group">
				<button type="button" class="btn" onClick="location='{$DOMAIN}/admin/reviews/reviews_headers/{$reviews.id}/'"><i class="icon-edit"></i> Редактировать заголовки глав</button>
			</div>

	    </div>
	    
	    
	    <div class="tab-pane" id="articles">
	    	<table class="table table-striped th_clear">
	    		<tr>
	    			<th nowrap>Дата публикации</th>
	    			<th>Статус статьи</th>
	    			<th>Название</th>
	    			<th>Изображение</th>
	    			<th>Краткое описание</th>
	    			<th>Полное описание</th>
	    		</tr>
	    		{foreach from=$articles item=article}
	    			<tr>
	    				<td>{$article.date|date_format:"%d.%m.%Y %H:%M"}</td>
	    				<td>{if $article.article_status == 1}Вкл.{else}Выкл.{/if}</td>
	    				<td>{$article.name|strip_tags}</td>
	    				<td><img src="{$article.image}" style="width:70px;" /></td>
	    				<td>{$article.small_text|truncate:"50"}</td>
	    				<td>{$article.text|truncate:"70"}</td>
	    			</tr>
	    		{/foreach}
	    	</table>
	    	
			<div class="control-group">
				<button type="button" class="btn" onClick="location='{$DOMAIN}/admin/reviews_articles/{$reviews.id}/'"><i class="icon-edit"></i> Редактировать статьи</button>
			</div>
	    	
	    </div>


	    <div class="tab-pane" id="interviews">
	    	<table class="table table-striped th_clear">
	    		<tr>
	    			<th nowrap>Дата публикации</th>
	    			<th>Статус интервью</th>
	    			<th>Персона</th>
	    			<th>Изображение</th>
	    			<th>Краткое описание</th>
	    			<th>Полное описание</th>
	    			<th>Логотип</th>
	    		</tr>
	    		{foreach from=$interviews item=interview}
	    			<tr>
	    				<td>{$interview.date|date_format:"%d.%m.%Y %H:%M"}</td>
	    				<td>{if $interview.interview_status == 1}Вкл.{else}Выкл.{/if}</td>
	    				<td>{$interview.person|strip_tags}</td>
	    				<td><img src="{$interview.image}" style="width:70px;" /></td>
	    				<td>{$interview.small_text|truncate:"50"}</td>
	    				<td>{$interview.description|truncate:"70"}</td>
	    				<td><a href="{$interview.logo_url}"><img src="{$interview.logo}" /></a></td>
	    			</tr>
	    		{/foreach}
	    	</table>
	    	
			<div class="control-group">
				<button type="button" class="btn" onClick="location='{$DOMAIN}/admin/reviews_interviews/{$reviews.id}/'"><i class="icon-edit"></i> Редактировать интервью</button>
			</div>
	    	
	    </div>


	    <div class="tab-pane" id="cases">
	    	<table class="table table-striped th_clear">
	    		<tr>
	    			<th nowrap>Дата публикации</th>
	    			<th>Статус кейса</th>
	    			<th>Название</th>
	    			<th>Изображение</th>
	    			<th>Краткое описание</th>
	    			<th>Полное описание</th>
	    			<th>Баннер</th>
	    		</tr>
	    		{foreach from=$cases item=case}
	    			<tr>
	    				<td>{$case.date|date_format:"%d.%m.%Y %H:%M"}</td>
	    				<td>{if $case.case_status == 1}Вкл.{else}Выкл.{/if}</td>
	    				<td>{$case.name|strip_tags}</td>
	    				<td><img src="{$case.image}" style="width:70px;" /></td>
	    				<td>{$case.small_text|truncate:"50"}</td>
	    				<td>{$case.text|truncate:"70"}</td>
	    				<td><a href="{$case.banner_url}"><img src="{$case.banner_image}" /></a></td>
	    			</tr>
	    		{/foreach}
	    	</table>
	    	
			<div class="control-group">
				<button type="button" class="btn" onClick="location='{$DOMAIN}/admin/reviews_cases/{$reviews.id}/'"><i class="icon-edit"></i> Редактировать кейсы</button>
			</div>
	    	
	    </div>

	    
	    <div class="tab-pane" id="tables">
	    	<table class="table table-striped th_clear">
	    		<tr>
	    			<th nowrap>Дата публикации</th>
	    			<th>Статус таблицы</th>
	    			<th>Описание</th>
	    			<th></th>
	    		</tr>
	    		{foreach from=$tables item=table}
	    			<tr>
	    				<td>{$table.date|date_format:"%d.%m.%Y %H:%M"}</td>
	    				<td>{if $table.table_status == 1}Вкл.{else}Выкл.{/if}</td>
	    				<td>{$table.description|truncate:"50"}</td>
	    				<td><a href="{$DOMAIN}/admin/reviews_tables/table_view/{$table.id}/">Смотреть таблицу</a></td>
	    			</tr>
	    		{/foreach}
	    	</table>
			
			<div class="control-group">
				<button type="button" class="btn" onClick="location='{$DOMAIN}/admin/reviews_tables/{$reviews.id}/'"><i class="icon-edit"></i> Редактировать таблицы</button>
			</div>
	    	
	    </div>
	    
	  </div>
	  
	</div>
	
	
	
	
</div>

{include file="admin/blocks/footer_block.tpl"}