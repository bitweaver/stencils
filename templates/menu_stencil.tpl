{strip}
	<ul>
		{if $gBitUser->hasPermission( 'p_stencil_read')}
			<li><a class="item" href="{$smarty.const.STENCIL_PKG_URL}index.php">{tr}Stencils Home{/tr}</a></li>
		{/if}
		{if $gBitUser->hasPermission( 'p_stencil_read')  || $gBitUser->hasPermission( 'p_stencil_remove' ) }
			<li><a class="item" href="{$smarty.const.STENCIL_PKG_URL}list_stencils.php">{tr}List Stencils{/tr}</a></li>
		{/if}
		{if $gBitUser->hasPermission( 'p_stencil_create' ) || $gBitUser->hasPermission( 'p_stencil_edit' ) }
			<li><a class="item" href="{$smarty.const.STENCIL_PKG_URL}edit.php">{tr}Create Stencil{/tr}</a></li>
		{/if}
	</ul>
{/strip}
