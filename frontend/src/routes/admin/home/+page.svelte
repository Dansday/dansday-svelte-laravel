<script lang="ts">
	import { enhance } from '$app/forms';

	let { data } = $props();
	const home = $derived(data.home ?? {});
	const inputClass = 'w-full max-w-md rounded border border-ash-600 bg-ash-900 px-3 py-2 text-ash-100 focus:border-cyan focus:outline-none';
</script>

<svelte:head><title>Home</title></svelte:head>

<div class="mb-6 flex items-center justify-between">
	<h1 class="text-xl font-semibold text-ash-100">Home</h1>
</div>

{#if data.form?.message && !data.form?.success}
	<div class="mb-4 rounded-lg border border-red-500/30 bg-red-500/10 px-4 py-2 text-sm text-red-400">{data.form.message}</div>
{/if}
{#if data.form?.success}
	<div class="mb-4 rounded-lg border border-green-500/30 bg-green-500/10 px-4 py-2 text-sm text-green-400">Saved.</div>
{/if}

{#if home !== null && Object.keys(home).length > 0}
	<div class="rounded-lg border border-ash-700 bg-ash-800 shadow">
		<div class="border-b border-ash-700 px-4 py-3">
			<h2 class="m-0 font-semibold text-ash-200">Home page content</h2>
		</div>
		<form method="POST" action="?/update" use:enhance class="p-4">
			<div class="mb-4">
				<label for="title" class="mb-1 block text-sm text-ash-400">Title</label>
				<input id="title" type="text" name="title" value={home.title ?? ''} maxlength="75" required class={inputClass} />
			</div>
			<div class="mb-4">
				<label for="description" class="mb-1 block text-sm text-ash-400">Description</label>
				<textarea id="description" name="description" rows="6" maxlength="5000" class={inputClass}>{home.description ?? ''}</textarea>
			</div>
			<div class="flex gap-2">
				<button type="submit" class="rounded bg-cyan px-4 py-2 font-medium text-ash-900 hover:opacity-90">Save</button>
			</div>
		</form>
	</div>
{:else}
	<div class="rounded-lg border border-red-500/30 bg-red-500/10 px-4 py-2 text-sm text-red-400">Home data not found.</div>
{/if}
