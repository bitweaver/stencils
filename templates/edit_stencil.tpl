{* $Header: /cvsroot/bitweaver/_bit_stencils/templates/edit_stencil.tpl,v 1.3 2007/08/23 15:56:05 squareing Exp $ *}
{strip}
<div class="floaticon">{bithelp}</div>

<div class="admin stencil">
	{if $preview}
		<h2>Preview {$gContent->mInfo.title|escape}</h2>
		<div class="preview">
			{include file="bitpackage:stencil/stencil_display.tpl" page=`$gContent->mInfo.stencil_id`}
		</div>
	{/if}

	<div class="header">
		<h1>
			{if $gContent->mInfo.stencil_id}
				{tr}{tr}Edit{/tr} {$gContent->mInfo.title|escape}{/tr}
			{else}
				{tr}Create New Record{/tr}
			{/if}
		</h1>
	</div>

	<div class="body">
		{form enctype="multipart/form-data" id="editstencilform"}
			{jstabs}
				{jstab}
					{legend legend="Edit/Create Stencil Record"}
						<input type="hidden" name="stencil[stencil_id]" value="{$gContent->mInfo.stencil_id}" />

						<div class="row">
							{formlabel label="Title" for="title"}
							{forminput}
								<input type="text" size="50" maxlength="200" name="stencil[title]" id="title" value="{$gContent->mInfo.title|escape}" />
							{/forminput}
						</div>

						<div class="row">
							{formlabel label="Description" for="description"}
							{forminput}
								<input size="50" type="text" name="stencil[description]" id="description" value="{$gContent->mInfo.description|escape}" />
								{formhelp note="Brief description of the page."}
							{/forminput}
						</div>

						{textarea name="stencil[edit]"}{$gContent->mInfo.data}{/textarea}

						{* any simple service edit options *}
						{include file="bitpackage:liberty/edit_services_inc.tpl serviceFile=content_edit_mini_tpl}

						<div class="row submit">
							<input type="submit" name="preview" value="{tr}Preview{/tr}" /> 
							<input type="submit" name="save_stencil" value="{tr}Save{/tr}" />
						</div>
					{/legend}
				{/jstab}

				{* any service edit template tabs *}
				{include file="bitpackage:liberty/edit_services_inc.tpl serviceFile=content_edit_tab_tpl}
			{/jstabs}
		{/form}
	</div><!-- end .body -->
</div><!-- end .stencil -->

{/strip}
