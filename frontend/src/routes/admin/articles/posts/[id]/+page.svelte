<script lang="ts">
	import { enhance } from '$app/forms';
	import SummernoteEditor from '$lib/components/admin/SummernoteEditor.svelte';

	let { data } = $props();
	const post = $derived(data.post ?? {});
	const categories = $derived(data.categories ?? []);
	const inputClass =
		'w-full rounded border border-ash-600 bg-ash-900 px-3 py-2 text-ash-100 focus:border-cyan focus:outline-none';
	const selectClass = 'rounded border border-ash-600 bg-ash-900 px-3 py-2 text-ash-100 focus:border-cyan focus:outline-none';
</script>

<svelte:head><title>Edit article</title></svelte:head>

<div class="mb-6 flex items-center justify-between">
	<h1 class="text-xl font-semibold text-ash-100">Edit article</h1>
</div>

{#if data.form?.message && !data.form?.success}
	<div class="mb-4 rounded-lg border border-red-500/30 bg-red-500/10 px-4 py-2 text-sm text-red-400">{data.form.message}</div>
{/if}
{#if data.form?.success}
	<div class="mb-4 rounded-lg border border-green-500/30 bg-green-500/10 px-4 py-2 text-sm text-green-400">Saved.</div>
{/if}

{#if post && post.id}
	<div class="rounded-lg border border-ash-700 bg-ash-800 shadow">
		<div class="border-b border-ash-700 px-4 py-3">
			<h2 class="m-0 font-semibold text-ash-200">Edit post</h2>
		</div>
		<form method="POST" action="?/update" use:enhance class="p-4" enctype="multipart/form-data">
			{#if post.image}
				<input type="hidden" name="image_current" value={post.image} />
			{/if}
			<div class="mb-4 grid gap-4 md:grid-cols-2">
				<div>
					<label for="title" class="mb-1 block text-sm text-ash-400">Title</label>
					<input id="title" type="text" name="title" value={post.title ?? ''} maxlength="55" required class={inputClass} />
				</div>
				<div>
					<label for="author" class="mb-1 block text-sm text-ash-400">Author</label>
					<input id="author" type="text" name="author" value={post.author ?? ''} maxlength="55" class={inputClass} />
				</div>
			</div>
			<div class="mb-4">
				<label for="short_desc" class="mb-1 block text-sm text-ash-400">Short description</label>
				<textarea id="short_desc" name="short_desc" rows="2" maxlength="255" class={inputClass}>{post.short_desc ?? ''}</textarea>
			</div>
			<div class="mb-4 grid gap-4 md:grid-cols-2">
				<div>
					<label for="category" class="mb-1 block text-sm text-ash-400">Category</label>
					<select id="category" name="category" class={selectClass}>
						{#each categories as cat}
							<option value={cat.id} selected={post.category_id === cat.id}>{cat.name}</option>
						{/each}
					</select>
				</div>
				<div>
					<label for="status" class="mb-1 block text-sm text-ash-400">Status</label>
					<select id="status" name="status" class={selectClass}>
						<option value="draft" selected={post.status === 'draft'}>Draft</option>
						<option value="published" selected={post.status === 'published'}>Published</option>
					</select>
				</div>
			</div>
			<div class="mb-4">
				<label for="article-text" class="mb-1 block text-sm text-ash-400">Content</label>
				<textarea id="article-text" name="text" rows="12">{post.text ?? ''}</textarea>
				<SummernoteEditor selector="#article-text" />
			</div>
			<div class="mb-4">
				<label for="image" class="mb-1 block text-sm text-ash-400">Poster image (optional)</label>
				<input id="image" type="file" name="image" accept=".jpg,.jpeg,.png" class="text-ash-300" />
			</div>
			<div class="mb-4">
				<label for="slug" class="mb-1 block text-sm text-ash-400">Slug</label>
				<input id="slug" type="text" name="slug" value={post.slug ?? ''} class={inputClass} />
			</div>
			<div class="flex gap-2">
				<button type="submit" class="rounded bg-cyan px-4 py-2 font-medium text-ash-900 hover:opacity-90">Save</button>
				<a href="/admin/articles/posts" class="rounded border border-ash-600 bg-ash-700 px-4 py-2 text-ash-200 hover:bg-ash-600">Back to list</a>
			</div>
		</form>
	</div>
{:else}
	<div class="mb-4 rounded-lg border border-red-500/30 bg-red-500/10 px-4 py-2 text-sm text-red-400">Article not found.</div>
	<a href="/admin/articles/posts" class="rounded border border-ash-600 bg-ash-700 px-4 py-2 text-ash-200 hover:bg-ash-600">Back to list</a>
{/if}
