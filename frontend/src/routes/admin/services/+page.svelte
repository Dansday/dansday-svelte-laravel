<script lang="ts">
	import { enhance } from '$app/forms';

	let { data } = $props();
	const services = $derived(data.services ?? []);
	let editingId = $state<number | null>(null);
	const inputClass = 'rounded border border-ash-600 bg-ash-900 px-2 py-1.5 text-ash-100 focus:border-cyan focus:outline-none';
</script>

<svelte:head><title>Services</title></svelte:head>

<div class="mb-6 flex items-center justify-between">
	<h1 class="text-xl font-semibold text-ash-100">Services</h1>
</div>

{#if data.form?.message && !data.form?.success}
	<div class="mb-4 rounded-lg border border-red-500/30 bg-red-500/10 px-4 py-2 text-sm text-red-400">{data.form.message}</div>
{/if}
{#if data.form?.success}
	<div class="mb-4 rounded-lg border border-green-500/30 bg-green-500/10 px-4 py-2 text-sm text-green-400">Saved.</div>
{/if}

{#if services !== null}
	<div class="mb-6 rounded-lg border border-ash-700 bg-ash-800 shadow">
		<div class="flex flex-wrap items-end justify-between gap-4 border-b border-ash-700 px-4 py-3">
			<h2 class="m-0 font-semibold text-ash-200">Add service</h2>
			<form method="POST" action="?/create" use:enhance class="flex flex-wrap items-end gap-3">
				<div>
					<label for="new_title" class="mb-1 block text-xs text-ash-500">Title</label>
					<input id="new_title" type="text" name="title" required maxlength="55" class={inputClass} />
				</div>
				<div>
					<label for="new_description" class="mb-1 block text-xs text-ash-500">Description</label>
					<textarea id="new_description" name="description" rows="1" required maxlength="255" class={inputClass}></textarea>
				</div>
				<div>
					<label for="new_info" class="mb-1 block text-xs text-ash-500">Info</label>
					<textarea id="new_info" name="info" rows="1" required maxlength="510" class={inputClass}></textarea>
				</div>
				<input type="hidden" name="order" value={services.length + 1} />
				<button type="submit" class="rounded bg-cyan px-4 py-2 text-sm font-medium text-ash-900 hover:opacity-90">Add</button>
			</form>
		</div>
	</div>

	<div class="divide-y divide-ash-700 rounded-lg border border-ash-700 bg-ash-800 shadow">
		{#each services as item (item.id)}
			{#if editingId === item.id}
				<div class="p-4">
					<form method="POST" action="?/update" use:enhance class="flex flex-col gap-3">
						<input type="hidden" name="id" value={item.id} />
						<div class="grid gap-3 md:grid-cols-2">
							<div>
								<label for="svc-title-{item.id}" class="mb-1 block text-xs text-ash-500">Title</label>
								<input id="svc-title-{item.id}" type="text" name="title" value={item.title} required maxlength="55" class="w-full {inputClass}" />
							</div>
							<div>
								<label for="svc-order-{item.id}" class="mb-1 block text-xs text-ash-500">Order</label>
								<input id="svc-order-{item.id}" type="number" name="order" value={item.order} min="1" class="w-16 {inputClass}" />
							</div>
						</div>
						<div>
							<label for="svc-desc-{item.id}" class="mb-1 block text-xs text-ash-500">Description</label>
							<textarea id="svc-desc-{item.id}" name="description" rows="2" maxlength="255" class="w-full {inputClass}">{item.description}</textarea>
						</div>
						<div>
							<label for="svc-info-{item.id}" class="mb-1 block text-xs text-ash-500">Info</label>
							<textarea id="svc-info-{item.id}" name="info" rows="2" maxlength="510" class="w-full {inputClass}">{item.info}</textarea>
						</div>
						<div class="flex gap-2">
							<button type="submit" class="rounded bg-cyan px-3 py-1.5 text-sm text-ash-900">Save</button>
							<button type="button" class="rounded border border-ash-600 px-3 py-1.5 text-sm text-ash-300 hover:bg-ash-700" onclick={() => (editingId = null)}>Cancel</button>
						</div>
					</form>
				</div>
			{:else}
				<div class="flex flex-wrap items-center justify-between gap-2 px-4 py-3 hover:bg-ash-700/50">
					<div class="text-ash-200"><strong>{item.title}</strong><br /><span class="text-sm text-ash-400">{item.description}</span></div>
					<div class="flex items-center gap-1">
						<a href="/admin/services?orderUp={item.id}" class="rounded border border-ash-600 px-2 py-1 text-xs text-ash-300 hover:bg-ash-700">↑</a>
						<a href="/admin/services?orderDown={item.id}" class="rounded border border-ash-600 px-2 py-1 text-xs text-ash-300 hover:bg-ash-700">↓</a>
						<button type="button" class="rounded border border-ash-600 px-2 py-1 text-xs text-ash-300 hover:bg-ash-700" onclick={() => (editingId = item.id)}>Edit</button>
						<form method="POST" action="?/delete" use:enhance class="inline">
							<input type="hidden" name="id" value={item.id} />
							<button type="submit" class="rounded border border-ash-600 px-2 py-1 text-xs text-red-400 hover:bg-red-500/10">Delete</button>
						</form>
					</div>
				</div>
			{/if}
		{/each}
	</div>
{:else}
	<div class="rounded-lg border border-red-500/30 bg-red-500/10 px-4 py-2 text-sm text-red-400">Failed to load services.</div>
{/if}
