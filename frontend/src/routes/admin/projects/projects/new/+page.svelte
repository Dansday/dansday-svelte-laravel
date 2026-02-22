<script lang="ts">
	import { enhance } from '$app/forms';

	let { data } = $props();
	const categories = $derived(data.categories ?? []);
</script>

<svelte:head><title>New project</title></svelte:head>

<h1 class="admin-page-title">New project</h1>

{#if data.form?.message && !data.form?.success}
	<p class="admin-msg admin-msg-error">{data.form.message}</p>
{/if}
{#if data.form?.success}
	<p class="admin-msg admin-msg-success">Created. <a href="/admin/projects/projects">Back to list</a></p>
{/if}

{#if categories !== null && !data.form?.success}
	<form method="POST" action="?/create" use:enhance class="admin-card" enctype="multipart/form-data">
		<div class="admin-form-group">
			<label for="title">Title</label>
			<input id="title" type="text" name="title" maxlength="55" />
		</div>
		<div class="admin-form-group">
			<label for="short_desc">Short description</label>
			<textarea id="short_desc" name="short_desc" rows="2" maxlength="110"></textarea>
		</div>
		<div class="admin-form-group">
			<label for="category">Category</label>
			<select id="category" name="category" required style="max-width: 14rem; padding: 0.5rem 0.75rem; background: var(--color-ash-900); border: 1px solid var(--color-ash-600); color: var(--color-ash-100); border-radius: 4px;">
				<option value="">Select…</option>
				{#each categories as cat}
					<option value={cat.id}>{cat.name}</option>
				{/each}
			</select>
		</div>
		<div class="admin-form-group">
			<label><input type="hidden" name="enable_project" value="0" /><input type="checkbox" name="enable_project" value="1" checked /> Visible on site</label>
		</div>
		<div class="admin-form-group">
			<label for="description">Description (HTML)</label>
			<textarea id="description" name="description" rows="12" required></textarea>
		</div>
		<div class="admin-form-group">
			<label for="image">Poster image (required)</label>
			<input id="image" type="file" name="image" accept=".jpg,.jpeg,.png" required />
		</div>
		<div class="admin-form-actions">
			<button type="submit" class="admin-btn admin-btn-accent">Create</button>
			<a href="/admin/projects/projects" class="admin-btn admin-btn-ghost">Cancel</a>
		</div>
	</form>
{:else if !data.form?.success}
	<p class="admin-msg admin-msg-error">Failed to load categories.</p>
	<a href="/admin/projects/projects" class="admin-btn admin-btn-ghost">Back to list</a>
{/if}
