<script lang="ts">
	import { enhance } from '$app/forms';
	import SummernoteEditor from '$lib/components/admin/SummernoteEditor.svelte';

	let { data } = $props();
	const categories = $derived(data.categories ?? []);
	const inputClass = 'w-full rounded border border-ash-600 bg-ash-900 px-3 py-2 text-ash-100 focus:border-cyan focus:outline-none';
	const selectClass = 'rounded border border-ash-600 bg-ash-900 px-3 py-2 text-ash-100 focus:border-cyan focus:outline-none';
</script>

<svelte:head><title>New article</title></svelte:head>

<div class="mb-6 flex items-center justify-between">
	<h1 class="text-xl font-semibold text-ash-100">New article</h1>
</div>

{#if data.form?.message && !data.form?.success}
	<div class="mb-4 rounded-lg border border-red-500/30 bg-red-500/10 px-4 py-2 text-sm text-red-400">{data.form.message}</div>
{/if}
{#if data.form?.success}
	<div class="mb-4 rounded-lg border border-green-500/30 bg-green-500/10 px-4 py-2 text-sm text-green-400">
		Created. <a href="/admin/articles/posts" class="text-cyan hover:underline">Back to list</a>
	</div>
{/if}

{#if categories !== null && !data.form?.success}
	<div class="rounded-lg border border-ash-700 bg-ash-800 shadow">
		<div class="border-b border-ash-700 px-4 py-3">
			<h2 class="m-0 font-semibold text-ash-200">New post</h2>
		</div>
		<form method="POST" action="?/create" use:enhance class="p-4" enctype="multipart/form-data">
			<div class="mb-4 grid gap-4 md:grid-cols-2">
				<div>
					<label for="title" class="mb-1 block text-sm text-ash-400">Title</label>
					<input id="title" type="text" name="title" maxlength="55" required class={inputClass} />
				</div>
				<div>
					<label for="author" class="mb-1 block text-sm text-ash-400">Author</label>
					<input id="author" type="text" name="author" maxlength="55" class={inputClass} />
				</div>
			</div>
			<div class="mb-4">
				<label for="short_desc" class="mb-1 block text-sm text-ash-400">Short description</label>
				<textarea id="short_desc" name="short_desc" rows="2" maxlength="255" class={inputClass}></textarea>
			</div>
			<div class="mb-4 grid gap-4 md:grid-cols-2">
				<div>
					<label for="category" class="mb-1 block text-sm text-ash-400">Category</label>
					<select id="category" name="category" required class={selectClass}>
						<option value="">Select…</option>
						{#each categories as cat}
							<option value={cat.id}>{cat.name}</option>
						{/each}
					</select>
				</div>
				<div>
					<label for="status" class="mb-1 block text-sm text-ash-400">Status</label>
					<select id="status" name="status" class={selectClass}>
						<option value="draft">Draft</option>
						<option value="published">Published</option>
					</select>
				</div>
			</div>
			<div class="mb-4">
				<label for="article-new-text" class="mb-1 block text-sm text-ash-400">Content</label>
				<textarea id="article-new-text" name="text" rows="12" required></textarea>
				<SummernoteEditor selector="#article-new-text" />
			</div>
			<div class="mb-4">
				<label for="image" class="mb-1 block text-sm text-ash-400">Poster image (required)</label>
				<input id="image" type="file" name="image" accept=".jpg,.jpeg,.png" required class="text-ash-300" />
			</div>
			<div class="flex gap-2">
				<button type="submit" class="rounded bg-cyan px-4 py-2 font-medium text-ash-900 hover:opacity-90">Create</button>
				<a href="/admin/articles/posts" class="rounded border border-ash-600 bg-ash-700 px-4 py-2 text-ash-200 hover:bg-ash-600">Cancel</a>
			</div>
		</form>
	</div>
{:else if !data.form?.success}
	<div class="rounded-lg border border-red-500/30 bg-red-500/10 px-4 py-2 text-sm text-red-400">Failed to load categories.</div>
	<a href="/admin/articles/posts" class="rounded border border-ash-600 bg-ash-700 px-4 py-2 text-ash-200 hover:bg-ash-600">Back to list</a>
{/if}
