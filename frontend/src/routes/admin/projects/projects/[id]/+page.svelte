<script lang="ts">
	import { enhance } from '$app/forms';
	import SummernoteEditor from '$lib/components/admin/SummernoteEditor.svelte';

	let { data } = $props();
	const project = $derived(data.project ?? {});
	const categories = $derived(data.categories ?? []);
	const inputClass = 'w-full rounded border border-ash-600 bg-ash-900 px-3 py-2 text-ash-100 focus:border-cyan focus:outline-none';
	const selectClass = 'rounded border border-ash-600 bg-ash-900 px-3 py-2 text-ash-100 focus:border-cyan focus:outline-none';
</script>

<svelte:head><title>Edit project</title></svelte:head>

<div class="mb-6 flex items-center justify-between">
	<h1 class="text-xl font-semibold text-ash-100">Edit project</h1>
</div>

{#if data.form?.message && !data.form?.success}
	<div class="mb-4 rounded-lg border border-red-500/30 bg-red-500/10 px-4 py-2 text-sm text-red-400">{data.form.message}</div>
{/if}
{#if data.form?.success}
	<div class="mb-4 rounded-lg border border-green-500/30 bg-green-500/10 px-4 py-2 text-sm text-green-400">Saved.</div>
{/if}

{#if project && project.id}
	<div class="rounded-lg border border-ash-700 bg-ash-800 shadow">
		<div class="border-b border-ash-700 px-4 py-3">
			<h2 class="m-0 font-semibold text-ash-200">Edit project</h2>
		</div>
		<form method="POST" action="?/update" use:enhance class="p-4" enctype="multipart/form-data">
			{#if project.image}
				<input type="hidden" name="image_current" value={project.image} />
			{/if}
			<div class="mb-4 grid gap-4 md:grid-cols-2">
				<div>
					<label for="title" class="mb-1 block text-sm text-ash-400">Title</label>
					<input id="title" type="text" name="title" value={project.title ?? ''} maxlength="55" required class={inputClass} />
				</div>
				<div>
					<label for="category" class="mb-1 block text-sm text-ash-400">Category</label>
					<select id="category" name="category" class={selectClass}>
						{#each categories as cat}
							<option value={cat.id} selected={project.category_id === cat.id}>{cat.name}</option>
						{/each}
					</select>
				</div>
			</div>
			<div class="mb-4">
				<label for="short_desc" class="mb-1 block text-sm text-ash-400">Short description</label>
				<textarea id="short_desc" name="short_desc" rows="2" maxlength="110" class={inputClass}>{project.short_desc ?? ''}</textarea>
			</div>
			<div class="mb-4">
				<label class="flex items-center gap-2 text-sm text-ash-400">
					<input type="hidden" name="enable_project" value="0" />
					<input type="checkbox" name="enable_project" value="1" checked={project.enable === 1} class="rounded border-ash-600" />
					Visible on site
				</label>
			</div>
			<div class="mb-4">
				<label for="project-description" class="mb-1 block text-sm text-ash-400">Description</label>
				<textarea id="project-description" name="description" rows="12">{project.description ?? ''}</textarea>
				<SummernoteEditor selector="#project-description" />
			</div>
			<div class="mb-4">
				<label for="image" class="mb-1 block text-sm text-ash-400">Poster image (optional)</label>
				<input id="image" type="file" name="image" accept=".jpg,.jpeg,.png" class="text-ash-300" />
			</div>
			<div class="flex gap-2">
				<button type="submit" class="rounded bg-cyan px-4 py-2 font-medium text-ash-900 hover:opacity-90">Save</button>
				<a href="/admin/projects/projects" class="rounded border border-ash-600 bg-ash-700 px-4 py-2 text-ash-200 hover:bg-ash-600">Back to list</a>
			</div>
		</form>
	</div>
{:else}
	<div class="mb-4 rounded-lg border border-red-500/30 bg-red-500/10 px-4 py-2 text-sm text-red-400">Project not found.</div>
	<a href="/admin/projects/projects" class="rounded border border-ash-600 bg-ash-700 px-4 py-2 text-ash-200 hover:bg-ash-600">Back to list</a>
{/if}
