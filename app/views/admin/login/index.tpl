{include file="admin/blocks/header_block.tpl"}

<div class="content" style="text-align:center;">
	
	<h2>����������� ������������</h2>
	
	<form action="" method="post" class="form-horizontal" id="form_login">
		
		{if $login_error}<div class="control-group"><label class="control-label error_login">{$login_error}</label></div>{/if}
	  
		  <div class="control-group">
		    <label class="control-label" for="user_login">�����</label>
		    <div class="controls">
		      <input type="text" id="user_login" name="user_login" placeholder="�����">
		    </div>
		  </div>
	
	
		  <div class="control-group">
		    <label class="control-label" for="user_password">������</label>
		    <div class="controls">
		      <input type="password" id="user_password" name="user_password" placeholder="������">
		    </div>
		  </div>
	
	
		  <div class="control-group">
		    <div class="controls">
		      <label class="checkbox" style="width:120px;">
		        <input type="checkbox" name="save" value="1"> ��������� ����
		      </label>
		      <button type="submit" class="btn">�����</button>
		    </div>
		  </div>
	
	</form>
	
</div>

{include file="admin/blocks/footer_block.tpl"}