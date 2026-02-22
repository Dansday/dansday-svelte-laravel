<script lang="ts">
	import { enhance } from '$app/forms';

	let { data } = $props();
	const general = $derived(data.general ?? {});
</script>

<svelte:head><title>General</title></svelte:head>

<div class="mb-6 flex items-center justify-between">
	<h1 class="text-xl font-semibold text-ash-100">General</h1>
</div>

{#if data.form?.message && !data.form?.success}
	<div class="mb-4 rounded-lg border border-red-500/30 bg-red-500/10 px-4 py-2 text-sm text-red-400">
		{data.form.message}
	</div>
{/if}
{#if data.form?.success}
	<div class="mb-4 rounded-lg border border-green-500/30 bg-green-500/10 px-4 py-2 text-sm text-green-400">
		Saved.
	</div>
{/if}

{#if general && Object.keys(general).length > 0}
	<div class="rounded-lg border border-ash-700 bg-ash-800 shadow">
		<div class="border-b border-ash-700 px-4 py-3">
			<h2 class="m-0 font-semibold text-ash-200">Site settings</h2>
		</div>
		<form method="POST" action="?/update" use:enhance class="p-4" enctype="multipart/form-data">
			<div class="mb-4">
				<label for="title" class="mb-1 block text-sm text-ash-400">Site title</label>
				<input
					id="title"
					type="text"
					name="title"
					value={general.title ?? ''}
					maxlength="55"
					class="w-full max-w-md rounded border border-ash-600 bg-ash-900 px-3 py-2 text-ash-100 focus:border-cyan focus:outline-none"
				/>
			</div>
			<div class="mb-4">
				<label for="description" class="mb-1 block text-sm text-ash-400">Description (meta)</label>
				<textarea
					id="description"
					name="description"
					rows="2"
					maxlength="255"
					class="w-full max-w-md rounded border border-ash-600 bg-ash-900 px-3 py-2 text-ash-100 focus:border-cyan focus:outline-none"
				>{general.description ?? ''}</textarea>
			</div>
			<div class="mb-4">
				<label for="analytics_code" class="mb-1 block text-sm text-ash-400">Analytics code (e.g. G-XXXX)</label>
				<input
					id="analytics_code"
					type="text"
					name="analytics_code"
					value={general.analytics_code ?? ''}
					maxlength="55"
					class="w-full max-w-md rounded border border-ash-600 bg-ash-900 px-3 py-2 text-ash-100 focus:border-cyan focus:outline-none"
				/>
			</div>
			<div class="mb-4">
				<label for="social_links" class="mb-1 block text-sm text-ash-400">Social links (JSON array)</label>
				<textarea
					id="social_links"
					name="social_links"
					rows="4"
					class="w-full max-w-md rounded border border-ash-600 bg-ash-900 px-3 py-2 font-mono text-sm text-ash-100 focus:border-cyan focus:outline-none"
				>{typeof general.social_links === 'string' ? general.social_links : JSON.stringify(general.social_links ?? [], null, 2)}</textarea>
			</div>
			<div class="mb-4">
				<label for="image_favicon" class="mb-1 block text-sm text-ash-400">Favicon (PNG, 155×155 min, optional)</label>
				<input id="image_favicon" type="file" name="image_favicon" accept=".png" class="text-ash-300" />
			</div>
			<div class="flex gap-2">
				<button
					type="submit"
					class="rounded bg-cyan px-4 py-2 text-ash-900 font-medium hover:opacity-90"
				>
					Save
				</button>
			</div>
		</form>
	</div>
{:else}
	<div class="rounded-lg border border-red-500/30 bg-red-500/10 px-4 py-2 text-sm text-red-400">
		General settings not found. Run db:seed.
	</div>
{/if}
