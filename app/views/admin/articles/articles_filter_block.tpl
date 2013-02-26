<div style="clear:both;">
	<form action="{$DOMAIN}/admin/articles/articles_list/" method="post" class="form-inline" id="articles_list_filter">
		<input class="input-small" type="text" name="date_start" id="date_start_picker" value="{if !empty($array.date_start)}{$array.date_start}{/if}" placeholder="Дата с..." />
		<input class="input-small" type="text" name="date_end" id="date_end_picker" value="{if !empty($array.date_end)}{$array.date_end}{/if}" placeholder="Дата по..." />
		<input class="input-medium" type="text" name="filter_tags" value="{if !empty($array.filter_tags)}{$array.filter_tags|escape:'html'}{/if}" placeholder="Искать по тегу..." />
		<select name="users_user_id"><option selected value=""> - все пользователи - </option>{foreach from=$users item=user}<option value="{$user.user_id}"{if $array.users_user_id == $user.user_id}selected{/if}>{$user.user_name}</option>{/foreach}</select>
		<select name="filter_sections" id="sections_select"><option selected value=""> - все разделы - </option>{foreach from=$sections item=item} {get_sections section=`$item` type="option" filter_section=`$array.filter_sections`} {/foreach}</select>
		<label class="checkbox"><input type="checkbox" name="article_top" value="1"{if $array.article_top == 1} checked{/if} />Главная статья</label>
		<label class="checkbox"><input type="checkbox" name="article_is_advert" value="1"{if $array.article_is_advert == 1} checked{/if} />Рекламная статья</label>
		<button type="submit" class="btn btn-success" id="articles_list_filter_btn">Фильтровать</button>
	</form>
</div>