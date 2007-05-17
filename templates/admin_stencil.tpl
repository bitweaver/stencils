{strip}
{form}
	{jstabs}
		{jstab title="Home Stencil"}
			{legend legend="Home Stencil"}
				<input type="hidden" name="page" value="{$page}" />
				<div class="row">
					{formlabel label="Home Stencil (main stencil)" for="homeStencil"}
					{forminput}
						<select name="homeStencil" id="homeStencil">
							{section name=ix loop=$stencils}
								<option value="{$stencils[ix].stencil_id|escape}" {if $stencils[ix].stencil_id eq $home_stencil}selected="selected"{/if}>{$stencils[ix].title|escape|truncate:20:"...":true}</option>
							{sectionelse}
								<option>{tr}No records found{/tr}</option>
							{/section}
						</select>
					{/forminput}
				</div>

				<div class="row submit">
					<input type="submit" name="homeTabSubmit" value="{tr}Change preferences{/tr}" />
				</div>
			{/legend}
		{/jstab}

		{jstab title="List Settings"}
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
					<input type="submit" name="listTabSubmit" value="{tr}Change preferences{/tr}" />
				</div>
			{/legend}
		{/jstab}
	{/jstabs}
{/form}
{/strip}
