{if $headers}
<div class="reviews_types">
	<h4>Подключение контент-материалов к заголовкам</h4>
	
	<form action="{$DOMAIN}/admin/reviews/reviews_headers_types_checked/{$reviews.id}/" method="post" id="headers_content_types_checked">
	
		<div class="tabbable tabs-left">
			
			<ul class="nav nav-tabs">
				{foreach from=$headers item=header name="for_headers"}
					<li{if $smarty.foreach.for_headers.first} class="active"{/if}><a href="#header{$header.id}" data-toggle="tab">{$header.name|strip_tags}</a></li>
				{/foreach}
			</ul>
			
			<div class="tab-content">
				{foreach from=$headers item=header_content name="for_headers_content"}
					<div class="tab-pane{if $smarty.foreach.for_headers_content.first} active{/if}" id="header{$header_content.id}">
						
						{foreach from=$header_content.structure item=item key=key}
							{include file="admin/reviews/reviews_types_checked_block.tpl" item=`$item` header_id=`$header_content.id`}
						{foreachelse}
							Типы не подключены
						{/foreach}
						
					</div>
				{/foreach}
			</div>
			
		</div>
	
		<button type="submit" class="btn btn-success">Сохранить отмеченные</button>
	
	</form>
	
</div>
{/if}