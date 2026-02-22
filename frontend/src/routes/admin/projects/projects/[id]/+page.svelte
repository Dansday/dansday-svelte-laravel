<script lang="ts">
	import { enhance } from '$app/forms';

	let { data } = $props();
	const project = $derived(data.project ?? {});
	const categories = $derived(data.categories ?? []);
</script>

<svelte:head><title>Edit project</title></svelte:head>

<h1 class="admin-page-title">Edit project</h1>

{#if data.form?.message && !data.form?.success}
	<p class="admin-msg admin-msg-error">{data.form.message}</p>
{/if}
{#if data.form?.success}
	<p class="admin-msg admin-msg-success">Saved.</p>
{/if}

{#if project && project.id}
	<form method="POST" action="?/update" use:enhance class="admin-card" enctype="multipart/form-data">
		{#if project.image}
			<input type="hidden" name="image_current" value={project.image} />
		{/if}
		<div class="admin-form-group">
			<label for="title">Title</label>
			<input id="title" type="text" name="title" value={project.title ?? ''} maxlength="55" required />
		</div>
		<div class="admin-form-group">
			<label for="short_desc">Short description</label>
			<textarea id="short_desc" name="short_desc" rows="2" maxlength="110">{project.short_desc ?? ''}</textarea>
		</div>
		<div class="admin-form-group">
			<label for="category">Category</label>
			<select id="category" name="category" style="max-width: 14rem; padding: 0.5rem 0.75rem; background: var(--color-ash-900); border: 1px solid var(--color-ash-600); color: var(--color-ash-100); border-radius: 4px;">
				{#each categories as cat}
					<option value={cat.id} selected={project.category_id === cat.id}>{cat.name}</option>
				{/each}
			</select>
		</div>
		<div class="admin-form-group">
			<label><input type="hidden" name="enable_project" value="0" /><input type="checkbox" name="enable_project" value="1" checked={project.enable === 1} /> Visible on site</label>
		</div>
		<div class="admin-form-group">
			<label for="description">Description (HTML)</label>
			<textarea id="description" name="description" rows="12">{project.description ?? ''}</textarea>
		</div>
		<div class="admin-form-group">
			<label for="image">Poster image (optional, replace current)</label>
			<input id="image" type="file" name="image" accept=".jpg,.jpeg,.png" />
		</div>
		<div class="admin-form-actions">
			<button type="submit" class="admin-btn admin-btn-accent">Save</button>
			<a href="/admin/projects/projects" class="admin-btn admin-btn-ghost">Back to list</a>
		</div>
	</form>
{:else}
	<p class="admin-msg admin-msg-error">Project not found.</p>
	<a href="/admin/projects/projects" class="admin-btn admin-btn-ghost">Back to list</a>
{/if}
