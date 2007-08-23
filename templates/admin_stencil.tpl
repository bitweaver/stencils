{strip}
{form}
	{legend legend="List Settings"}
		<input type="hidden" name="page" value="{$page}" />
		{foreach from=$formStencilLists key=item item=output}
			<div class="row">
				{formlabel label=`$output.label` for=$item}
				{forminput}
					{html_checkboxes name="$item" values="y" checked=$gBitSystem->getConfig($item) labels=false id=$item}
					{formhelp note=`$output.note` page=`$output.page`}
				{/forminput}
			</div>
		{/foreach}

		<div class="row submit">
			<input type="submit" name="apply" value="{tr}Change preferences{/tr}" />
		</div>
	{/legend}
{/form}
{/strip}
