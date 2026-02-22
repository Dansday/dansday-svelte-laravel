<script lang="ts">
	import { enhance } from '$app/forms';

	let { data } = $props();
	const home = $derived(data.home ?? {});
</script>

<svelte:head><title>Home</title></svelte:head>

<h1 class="admin-page-title">Home</h1>

{#if data.form?.message && !data.form?.success}
	<p class="admin-msg admin-msg-error">{data.form.message}</p>
{/if}
{#if data.form?.success}
	<p class="admin-msg admin-msg-success">Saved.</p>
{/if}

{#if home !== null && Object.keys(home).length > 0}
	<form method="POST" action="?/update" use:enhance class="admin-card">
		<h2 class="admin-card-title">Home page content</h2>
		<div class="admin-form-group">
			<label for="title">Title</label>
			<input id="title" type="text" name="title" value={home.title ?? ''} maxlength="75" required />
		</div>
		<div class="admin-form-group">
			<label for="description">Description</label>
			<textarea id="description" name="description" rows="6" maxlength="5000">{home.description ?? ''}</textarea>
		</div>
		<div class="admin-form-actions">
			<button type="submit" class="admin-btn admin-btn-accent">Save</button>
		</div>
	</form>
{:else}
	<p class="admin-msg admin-msg-error">Home data not found.</p>
{/if}
