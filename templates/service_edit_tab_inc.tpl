{strip}
{jstab title="Stencils"}
	{legend legend="Available Stencils"}
		{foreach from=$stencilList item=stencil}
			<h2>{$stencil.title}</h2>
			<p class="description">{$stencil.description}</p>
			<div style="float:left; width:48%; overflow:auto;">
				<pre><code>{$stencil.data}</code></pre>
			</div>
			<div style="float:right; width:48%; overflow:auto;">
				<pre><code>{$stencil.usage}</code></pre>
			</div>
		{/foreach}
	{/legend}
{/jstab}
{/strip}
