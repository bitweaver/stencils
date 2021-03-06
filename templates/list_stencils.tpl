{* $Header$ *}
{strip}
<div class="floaticon">{bithelp}</div>

<div class="listing stencil">
	<div class="header">
		<h1>{tr}Stencil Records{/tr}</h1>
	</div>

	<div class="body">
		{minifind sort_mode=$sort_mode}

		{form id="checkform"}
			<input type="hidden" name="offset" value="{$control.offset|escape}" />
			<input type="hidden" name="sort_mode" value="{$control.sort_mode|escape}" />

			<table class="table data">
				<tr>
					{if $gBitSystem->isFeatureActive( 'stencil_list_stencil_id' ) eq 'y'}
						<th>{smartlink ititle="Stencil Id" isort=stencil_id offset=$control.offset iorder=desc idefault=1}</th>
					{/if}

					{if $gBitSystem->isFeatureActive( 'stencil_list_title' ) eq 'y'}
						<th>{smartlink ititle="Title" isort=title offset=$control.offset}</th>
					{/if}

					{if $gBitSystem->isFeatureActive( 'stencil_list_description' ) eq 'y'}
						<th>{smartlink ititle="Description" isort=description offset=$control.offset}</th>
					{/if}

					{if $gBitSystem->isFeatureActive( 'stencil_list_data' ) eq 'y'}
						<th>{smartlink ititle="Text" isort=data offset=$control.offset}</th>
					{/if}

					{if $gBitUser->hasPermission( 'p_stencil_remove' )}
						<th>{tr}Actions{/tr}</th>
					{/if}
				</tr>

				{foreach item=stencil from=$stencilsList}
					<tr class="{cycle values="even,odd"}">
						{if $gBitSystem->isFeatureActive( 'stencil_list_stencil_id' )}
							<td><a href="{$smarty.const.STENCIL_PKG_URL}index.php?stencil_id={$stencil.stencil_id|escape:"url"}" title="{$stencil.stencil_id}">{$stencil.stencil_id}</a></td>
						{/if}

						{if $gBitSystem->isFeatureActive( 'stencil_list_title' )}
							<td><a href="{$smarty.const.STENCIL_PKG_URL}index.php?stencil_id={$stencil.stencil_id|escape:"url"}" title="{$stencil.stencil_id}">{$stencil.title|escape}</a></td>
						{/if}

						{if $gBitSystem->isFeatureActive( 'stencil_list_description' )}
							<td>{$stencil.description|escape}</td>
						{/if}

						{if $gBitSystem->isFeatureActive( 'stencil_list_data' )}
							<td>{$stencil.data|escape}</td>
						{/if}
						<td class="actionicon">
							{if $gBitUser->hasPermission( 'p_stencil_update' )}
								{smartlink ititle="Edit" ifile="edit.php" booticon="icon-edit" stencil_id=$stencil.stencil_id}
							{/if}
							{if $gBitUser->hasPermission( 'p_stencil_remove' )}
								{smartlink ititle="Delete" ifile="remove_stencil.php" booticon="icon-trash" stencil_id=$stencil.stencil_id}
							{/if}
							{if $gBitUser->hasPermission( 'p_stencil_remove' )}
								<input type="checkbox" name="checked[]" title="{$stencil.title|escape}" value="{$stencil.stencil_id}" />
							{/if}
						</td>
					</tr>
				{foreachelse}
					<tr class="norecords"><td colspan="16">
						{tr}No records found{/tr}
					</td></tr>
				{/foreach}
			</table>

			{if $gBitUser->hasPermission( 'p_stencil_remove' )}
				<div style="text-align:right;">
					<script type="text/javascript">/* <![CDATA[ check / uncheck all */
						document.write("<label for=\"switcher\">{tr}Select All{/tr}</label> ");
						document.write("<input name=\"switcher\" id=\"switcher\" type=\"checkbox\" onclick=\"BitBase.switchCheckboxes(this.form.id,'checked[]','switcher')\" /><br />");
					/* ]]> */</script>

					<select name="submit_mult" onchange="this.form.submit();">
						<option value="" selected="selected">{tr}with checked{/tr}:</option>
						{if $gBitUser->hasPermission( 'p_stencil_remove' )}
							<option value="remove_stencils">{tr}remove{/tr}</option>
						{/if}
					</select>

					<noscript><div><input type="submit" class="btn btn-default" value="{tr}Submit{/tr}" /></div></noscript>
				</div>
			{/if}
		{/form}

		{pagination}
	</div><!-- end .body -->
</div><!-- end .admin -->
{/strip}
