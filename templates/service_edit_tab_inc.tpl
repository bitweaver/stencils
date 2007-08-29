{strip}
{if $stencilList}
	{jstab title="Stencils"}
		{legend legend="Available Stencils"}
			{foreach from=$stencilList item=stencil name=sten}
				{if !$smarty.foreach.sten.first}
					<div class="clear"><!-- --></div>
					<hr />
				{/if}
				<h2>
					{$stencil.title}
					{if $gBitUser->hasPermission( 'p_stencil_edit' )}
						{smartlink ititle="Edit" ibiticon="icons/accessories-text-editor" ipackage=stencil ifile=edit.php stencil_id=$stencil.stencil_id}
					{/if}
				</h2>
				{*
				<p class="description">{$stencil.description}</p>
				<div style="float:left; width:48%; overflow:auto;">
					<pre><code>{$stencil.data|escape}</code></pre>
				</div>
				<div style="float:right; width:48%; overflow:auto;">
					<pre><code>{$stencil.usage}</code></pre>
				</div>
				*}
				<pre><code>{$stencil.usage}</code></pre>
			{/foreach}
		{/legend}
	{/jstab}
{/if}
{/strip}
