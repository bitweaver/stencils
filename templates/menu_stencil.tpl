{strip}
<a class="dropdown-toggle" data-toggle="dropdown" href="#"> {tr}{$packageMenuTitle}{/tr} <b class="caret"></b></a>
<ul class="dropdown-menu">
	{if $gBitUser->hasPermission( 'p_stencil_read')  || $gBitUser->hasPermission( 'p_stencil_remove' ) }
		<li><a class="item" href="{$smarty.const.STENCIL_PKG_URL}list_stencils.php">{tr}List Stencils{/tr}</a></li>
	{/if}
	{if $gBitUser->hasPermission( 'p_stencil_create' ) || $gBitUser->hasPermission( 'p_stencil_update' ) }
		<li><a class="item" href="{$smarty.const.STENCIL_PKG_URL}edit.php">{tr}Create Stencil{/tr}</a></li>
	{/if}
</ul>
{/strip}
