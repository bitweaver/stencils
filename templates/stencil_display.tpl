{include file="bitpackage:liberty/services_inc.tpl" serviceLocation='nav' serviceHash=$gContent->mInfo}
<div class="display stencil">
	<div class="floaticon">
		{if $print_page ne 'y'}
			{if $gBitUser->hasPermission( 'p_stencil_update' )}
				<a title="{tr}Edit this stencil{/tr}" href="{$smarty.const.STENCIL_PKG_URL}edit.php?stencil_id={$gContent->mInfo.stencil_id}">{booticon iname="icon-edit" ipackage="icons" iexplain="Edit Stencil"}</a>
			{/if}
			{if $gBitUser->hasPermission( 'p_stencil_remove' )}
				<a title="{tr}Remove this stencil{/tr}" href="{$smarty.const.STENCIL_PKG_URL}remove_stencil.php?stencil_id={$gContent->mInfo.stencil_id}">{booticon iname="icon-trash" ipackage="icons" iexplain="Remove Stencil"}</a>
			{/if}
		{/if}<!-- end print_page -->
	</div><!-- end .floaticon -->

	<div class="header">
		<h1>{$gContent->mInfo.title|escape|default:"Stencil"}</h1>
		<p>{$gContent->mInfo.description|escape}</p>
		<div class="date">
			{tr}Created by{/tr}: {displayname user=$gContent->mInfo.creator_user user_id=$gContent->mInfo.creator_user_id real_name=$gContent->mInfo.creator_real_name}, {tr}Last modification by{/tr}: {displayname user=$gContent->mInfo.modifier_user user_id=$gContent->mInfo.modifier_user_id real_name=$gContent->mInfo.modifier_real_name}, {$gContent->mInfo.last_modified|bit_long_datetime}
		</div>
	</div><!-- end .header -->

	<div class="body">
		<div class="content">
			{include file="bitpackage:liberty/services_inc.tpl" serviceLocation='body' serviceHash=$gContent->mInfo}
			{$gContent->mInfo.parsed_data}
		</div><!-- end .content -->
	</div><!-- end .body -->
</div><!-- end .stencil -->
{include file="bitpackage:liberty/services_inc.tpl" serviceLocation='view' serviceHash=$gContent->mInfo}
