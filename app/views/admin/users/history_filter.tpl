<div>
	<form class="form-inline" id="change_history_filter" action="" method="post">
		
		<input class="input-small" type="text" name="date_start" id="date_start_picker" value="{if !empty($filter_params.date_start)}{$filter_params.date_start}{/if}" placeholder="���� �..." />
		
		<input class="input-small" type="text" name="date_end" id="date_end_picker" value="{if !empty($filter_params.date_end)}{$filter_params.date_end}{/if}" placeholder="���� ��..." />
		
		<select name="change_user"><option selected value=""> - ��� ������������ - </option>{foreach from=$users item=user}<option value="{$user.user_id}"{if $filter_params.change_user == $user.user_id}selected{/if}>{$user.user_name}</option>{/foreach}</select>
		
		<select name="change_operation_type"><option selected value=""> - ��� �������� - </option>{foreach from=$change_types item=type key=key}<option value="{$key}"{if $filter_params.change_operation_type == $key}selected{/if}>{$type}</option>{/foreach}</select>
		
		<select name="change_object"><option selected value=""> - ��� ������� - </option>{foreach from=$change_objects item=object key=key}<option value="{$key}"{if $filter_params.change_object == $key}selected{/if}>{$object.name}</option>{/foreach}</select>
		
		<button type="submit" class="btn btn-success" id="change_filter_btn">�����������</button>
		
	</form>
</div>