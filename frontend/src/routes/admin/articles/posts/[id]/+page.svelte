<script lang="ts">
	import { enhance } from '$app/forms';

	let { data } = $props();
	const post = $derived(data.post ?? {});
	const categories = $derived(data.categories ?? []);
</script>

<svelte:head><title>Edit article</title></svelte:head>

<h1 class="admin-page-title">Edit article</h1>

{#if data.form?.message && !data.form?.success}
	<p class="admin-msg admin-msg-error">{data.form.message}</p>
{/if}
{#if data.form?.success}
	<p class="admin-msg admin-msg-success">Saved.</p>
{/if}

{#if post && post.id}
	<form method="POST" action="?/update" use:enhance class="admin-card" enctype="multipart/form-data">
		{#if post.image}
			<input type="hidden" name="image_current" value={post.image} />
		{/if}
		<div class="admin-form-group">
			<label for="title">Title</label>
			<input id="title" type="text" name="title" value={post.title ?? ''} maxlength="55" required />
		</div>
		<div class="admin-form-group">
			<label for="short_desc">Short description</label>
			<textarea id="short_desc" name="short_desc" rows="2" maxlength="255">{post.short_desc ?? ''}</textarea>
		</div>
		<div class="admin-form-group">
			<label for="author">Author</label>
			<input id="author" type="text" name="author" value={post.author ?? ''} maxlength="55" />
		</div>
		<div class="admin-form-group">
			<label for="category">Category</label>
			<select id="category" name="category" style="max-width: 14rem; padding: 0.5rem 0.75rem; background: var(--color-ash-900); border: 1px solid var(--color-ash-600); color: var(--color-ash-100); border-radius: 4px;">
				{#each categories as cat}
					<option value={cat.id} selected={post.category_id === cat.id}>{cat.name}</option>
				{/each}
			</select>
		</div>
		<div class="admin-form-group">
			<label for="status">Status</label>
			<select id="status" name="status" style="max-width: 10rem; padding: 0.5rem 0.75rem; background: var(--color-ash-900); border: 1px solid var(--color-ash-600); color: var(--color-ash-100); border-radius: 4px;">
				<option value="draft" selected={post.status === 'draft'}>Draft</option>
				<option value="published" selected={post.status === 'published'}>Published</option>
			</select>
		</div>
		<div class="admin-form-group">
			<label for="text">Content (HTML)</label>
			<textarea id="text" name="text" rows="12">{post.text ?? ''}</textarea>
		</div>
		<div class="admin-form-group">
			<label for="image">Poster image (optional)</label>
			<input id="image" type="file" name="image" accept=".jpg,.jpeg,.png" />
		</div>
		<div class="admin-form-group">
			<label for="slug">Slug</label>
			<input id="slug" type="text" name="slug" value={post.slug ?? ''} />
		</div>
		<div class="admin-form-actions">
			<button type="submit" class="admin-btn admin-btn-accent">Save</button>
			<a href="/admin/articles/posts" class="admin-btn admin-btn-ghost">Back to list</a>
		</div>
	</form>
{:else}
	<p class="admin-msg admin-msg-error">Article not found.</p>
	<a href="/admin/articles/posts" class="admin-btn admin-btn-ghost">Back to list</a>
{/if}
