{* $Header$ *}
{strip}
<div class="floaticon">{bithelp}</div>

<div class="edit stencil">
	{if $preview}
		<h2>Preview {$gContent->mInfo.title|escape}</h2>
		<div class="preview">
			{include file="bitpackage:stencil/stencil_display.tpl" page=$gContent->mInfo.stencil_id}
		</div>
	{/if}

	<div class="header">
		<h1>
			{if $gContent->mInfo.stencil_id}
				{tr}{tr}Edit{/tr} {$gContent->mInfo.title|escape}{/tr}
			{else}
				{tr}Create New Stencil{/tr}
			{/if}
		</h1>
	</div>
	
	<div class="body">
		{form enctype="multipart/form-data" id="editstencilform"}
			{jstabs}
				{jstab title=$gContent->mInfo.title|default:"Edit"}
					{legend legend="Edit Stencil Record"}
						<input type="hidden" name="stencil[stencil_id]" value="{$gContent->mInfo.stencil_id}" />

						<div class="control-group">
							{formfeedback error=$feedback.errors.title}
							{formlabel label="Title" for="title"}
							{forminput}
								<input type="text" size="50" maxlength="200" name="stencil[title]" id="title" value="{$gContent->mInfo.title|escape}" />
							{/forminput}
						</div>

						<div class="control-group">
							{formlabel label="Description" for="description"}
							{forminput}
								<input size="50" type="text" name="stencil[description]" id="description" value="{$gContent->mInfo.description|escape}" />
								{formhelp note="Brief description of the stencil."}
							{/forminput}
						</div>

						{formfeedback error=$feedback.errors.fields}
						{textarea name="stencil[edit]" edit=$gContent->mInfo.data}

						{* any simple service edit options *}
						{include file="bitpackage:liberty/edit_services_inc.tpl" serviceFile="content_edit_mini_tpl"}

						<div class="control-group submit">
							<input type="submit" class="btn btn-default" name="preview" value="{tr}Preview{/tr}" /> 
							<input type="submit" class="btn btn-default" name="save_stencil" value="{tr}Save{/tr}" />
						</div>
					{/legend}
				{/jstab}

				{* any service edit template tabs *}
				{include file="bitpackage:liberty/edit_services_inc.tpl" serviceFile="content_edit_tab_tpl"}
			{/jstabs}
		{/form}
	</div><!-- end .body -->
</div><!-- end .stencil -->

{/strip}
