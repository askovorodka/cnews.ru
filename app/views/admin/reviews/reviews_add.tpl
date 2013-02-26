{include file="admin/blocks/header_block.tpl"}

{include file="admin/blocks/left_menu_block.tpl"}

{include file="admin/blocks/breadcrumbs_block.tpl"}

<div class="content">
	<form action="{$DOMAIN}/admin/reviews/{if $reviews}reviews_edit_post/{$reviews.id}{else}reviews_add_post{/if}/" method="post" class="add_form" id="reviews_add">
		<fieldset>
			<legend>{if $reviews}��������� ������ {$reviews.name}{else}���������� ������{/if}</legend>
			
			<div class="control-group edit_status">
				<label class="control-label" for="review_status">������ ������</label>
				<div class="controls">
					<select id="review_status" name="review_status"><option value="1"{if $reviews.review_status==1} selected{/if}>�������</option><option value="0" {if !$reviews or $reviews.review_status==0}selected{/if}>��������</option></select>
				</div>
			</div>
			
			<label>��� ������</label>
			<select name="type">{foreach from=$reviews_types item=item key=key}<option {if $reviews && $reviews.type==$key}selected{/if} value="{$key}">{$item}</option>{/foreach}</select>
			
			
			{if $reviews}
				<div class="control-group">
					<label class="control-label" for="date_picker">���� ����������</label>
					<div class="controls">
						<input class="input-xlarge select_date" type="text" name="date" id="date_picker" value="{$reviews.date|date_format:'%d.%m.%Y %H:%M:%S'}" />
					</div>
				</div>
			{/if}
			
			<div class="control-group">
				<label class="control-label" for="name">�������� ������</label>
				<div class="controls">
					<input class="input-xlarge" type="text" name="name" id="name" value="{$reviews.name}" />
				</div>
			</div>
			
			<div class="control-group">
				<label class="control-label" for="image">�������� (270x220)</label>{if $reviews.image|isimage}<img src="{$reviews.image}" width="100" />{/if}
				<div class="controls">
					<input class="input-xlarge" type="text" name="image" id="image" value="{$reviews.image}" />
					<a href="" class="select_image" for="image">�������</a>
				</div>
			</div>
			
			<div class="control-group">
				<label class="control-label" for="banner_image">��������� �������� (108x28)</label>{if $reviews.banner_image|isimage}<img src="{$reviews.banner_image}" width="100" />{/if}
				<div class="controls">
					<input class="input-xlarge" type="text" name="banner_image" id="banner_image" value="{if !empty($reviews)}{$reviews.banner_image}{else}http://www.cnews.ru/img/design2008/analitics/CNewsLogo.gif{/if}" />
					<a href="" class="select_image" for="banner_image">�������</a>
				</div>
			</div>
			
			<div class="control-group">
				<label class="control-label" for="banner_url">��������� ������</label>
				<div class="controls">
					<input class="input-xlarge" type="text" name="banner_url" id="banner_url" value="{if !empty($reviews)}{$reviews.banner_url}{else}http://cna.cnews.ru/{/if}" />
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="banner_right_image">��������� �������� "��� ���������" (108x19)</label>{if $reviews.banner_right_image|isimage}<img src="{$reviews.banner_right_image}" width="100" />{/if}
				<div class="controls">
					<input class="input-xlarge" type="text" name="banner_right_image" id="banner_right_image" value="{$reviews.banner_right_image}" />
					<a href="" class="select_image" for="banner_right_image">�������</a>
				</div>
			</div>
			
			<div class="control-group">
				<label class="control-label" for="banner_right_url">��������� ������ "��� ���������"</label>
				<div class="controls">
					<input class="input-xlarge" type="text" name="banner_right_url" id="banner_right_url" value="{$reviews.banner_right_url}" />
				</div>
			</div>

			
			<div class="control-group">
				<label class="control-label" for="text">����� ������</label>
				<div class="controls">
					<textarea class="input-xlarge text" name="text" rows="20" cols="15" id="text">{$reviews.text}</textarea>
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="pre_release">��������������� �����</label>
				<div class="controls">
					<textarea class="input-xlarge pre_release" name="pre_release" rows="20" cols="15" id="pre_release">{$reviews.pre_release}</textarea>
				</div>
			</div>
			
			<div class="control-group">
				<label class="control-label" for="footer">����� Footer</label>
				<div class="controls">
					<textarea class="input-xlarge small_text" name="footer" rows="10" id="footer">{$reviews.footer}</textarea>
				</div>
			</div>
			
			<button type="button" id="reviews_add_btn" class="btn btn-success">{if $reviews}<i class="icon-ok"></i> �������� �����{else}<i class="icon-plus"></i> �������� �����{/if}</button>
		</fieldset>
	</form>
</div>

{include file="admin/blocks/footer_block.tpl"}