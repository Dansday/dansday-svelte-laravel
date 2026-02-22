<script lang="ts">
	import { enhance } from '$app/forms';

	let { data } = $props();
	const categories = $derived(data.categories ?? []);
</script>

<svelte:head><title>New article</title></svelte:head>

<h1 class="admin-page-title">New article</h1>

{#if data.form?.message && !data.form?.success}
	<p class="admin-msg admin-msg-error">{data.form.message}</p>
{/if}
{#if data.form?.success}
	<p class="admin-msg admin-msg-success">Created. <a href="/admin/articles/posts">Back to list</a></p>
{/if}

{#if categories !== null && !data.form?.success}
	<form method="POST" action="?/create" use:enhance class="admin-card" enctype="multipart/form-data">
		<div class="admin-form-group">
			<label for="title">Title</label>
			<input id="title" type="text" name="title" maxlength="55" required />
		</div>
		<div class="admin-form-group">
			<label for="short_desc">Short description</label>
			<textarea id="short_desc" name="short_desc" rows="2" maxlength="255"></textarea>
		</div>
		<div class="admin-form-group">
			<label for="author">Author</label>
			<input id="author" type="text" name="author" maxlength="55" />
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
			<label for="status">Status</label>
			<select id="status" name="status" style="max-width: 10rem; padding: 0.5rem 0.75rem; background: var(--color-ash-900); border: 1px solid var(--color-ash-600); color: var(--color-ash-100); border-radius: 4px;">
				<option value="draft">Draft</option>
				<option value="published">Published</option>
			</select>
		</div>
		<div class="admin-form-group">
			<label for="text">Content (HTML)</label>
			<textarea id="text" name="text" rows="12" required></textarea>
		</div>
		<div class="admin-form-group">
			<label for="image">Poster image (required)</label>
			<input id="image" type="file" name="image" accept=".jpg,.jpeg,.png" required />
		</div>
		<div class="admin-form-actions">
			<button type="submit" class="admin-btn admin-btn-accent">Create</button>
			<a href="/admin/articles/posts" class="admin-btn admin-btn-ghost">Cancel</a>
		</div>
	</form>
{:else if !data.form?.success}
	<p class="admin-msg admin-msg-error">Failed to load categories.</p>
	<a href="/admin/articles/posts" class="admin-btn admin-btn-ghost">Back to list</a>
{/if}
