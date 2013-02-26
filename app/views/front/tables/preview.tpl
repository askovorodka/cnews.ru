{include file="front/blocks/header_block.tpl" not_left_side="true"}
		
		<div class="rating_large">
			{$table.structure}
			<div class="bottom_bar">
				{if $table.source}<p class="source">{$table.source}</p>{/if}
				{if $table.rating}<p class="legend grey_text">{$table.rating}</p>{/if}
			</div>
		</div>
		<a href="javascript:window.history.go(-1 );" class="button">Вернуться назад</a>
		<!--// main part -->
	</div>

{include file="front/blocks/footer_block.tpl"}