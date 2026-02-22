<script lang="ts">
	import { enhance } from '$app/forms';

	let { data } = $props();
	const general = $derived(data.general ?? {});
</script>

<svelte:head><title>General</title></svelte:head>

<h1 class="admin-page-title">General</h1>

{#if data.form?.message && !data.form?.success}
	<p class="admin-msg admin-msg-error">{data.form.message}</p>
{/if}
{#if data.form?.success}
	<p class="admin-msg admin-msg-success">Saved.</p>
{/if}

{#if general}
	<form method="POST" action="?/update" use:enhance class="admin-card">
		<h2 class="admin-card-title">Site settings</h2>
		<div class="admin-form-group">
			<label for="title">Site title</label>
			<input id="title" type="text" name="title" value={general.title ?? ''} maxlength="55" />
		</div>
		<div class="admin-form-group">
			<label for="description">Description (meta)</label>
			<textarea id="description" name="description" rows="2" maxlength="255">{general.description ?? ''}</textarea>
		</div>
		<div class="admin-form-group">
			<label for="analytics_code">Analytics code (e.g. G-XXXX)</label>
			<input id="analytics_code" type="text" name="analytics_code" value={general.analytics_code ?? ''} maxlength="55" />
		</div>
		<div class="admin-form-group">
			<label for="social_links">Social links (JSON array, e.g. [&#123;"title":"GitHub","text":"https://..."&#125;])</label>
			<textarea id="social_links" name="social_links" rows="4">{typeof general.social_links === 'string' ? general.social_links : JSON.stringify(general.social_links ?? [], null, 2)}</textarea>
		</div>
		<div class="admin-form-group">
			<label for="image_favicon">Favicon (PNG, 155×155 min, optional)</label>
			<input id="image_favicon" type="file" name="image_favicon" accept=".png" />
		</div>
		<div class="admin-form-actions">
			<button type="submit" class="admin-btn admin-btn-accent">Save</button>
		</div>
	</form>
{:else}
	<p class="admin-msg admin-msg-error">General settings not found. Run db:seed.</p>
{/if}
