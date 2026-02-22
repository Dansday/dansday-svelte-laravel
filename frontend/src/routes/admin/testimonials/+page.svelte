<script lang="ts">
	import { enhance } from '$app/forms';

	let { data } = $props();
	const testimonials = $derived(data.testimonials ?? []);
	let editingId = $state<number | null>(null);
	const inputClass = 'rounded border border-ash-600 bg-ash-900 px-2 py-1.5 text-ash-100 focus:border-cyan focus:outline-none';
</script>

<svelte:head><title>Testimonials</title></svelte:head>

<div class="mb-6 flex items-center justify-between">
	<h1 class="text-xl font-semibold text-ash-100">Testimonials</h1>
</div>

{#if data.form?.message && !data.form?.success}
	<div class="mb-4 rounded-lg border border-red-500/30 bg-red-500/10 px-4 py-2 text-sm text-red-400">{data.form.message}</div>
{/if}
{#if data.form?.success}
	<div class="mb-4 rounded-lg border border-green-500/30 bg-green-500/10 px-4 py-2 text-sm text-green-400">Saved.</div>
{/if}

{#if testimonials !== null}
	<div class="mb-6 rounded-lg border border-ash-700 bg-ash-800 shadow">
		<div class="flex flex-wrap items-end justify-between gap-4 border-b border-ash-700 px-4 py-3">
			<h2 class="m-0 font-semibold text-ash-200">Add testimonial</h2>
			<form method="POST" action="?/create" use:enhance class="flex flex-wrap items-end gap-3">
				<div>
					<label for="new_name" class="mb-1 block text-xs text-ash-500">Name</label>
					<input id="new_name" type="text" name="name" required maxlength="55" class={inputClass} />
				</div>
				<div>
					<label for="new_company" class="mb-1 block text-xs text-ash-500">Company</label>
					<input id="new_company" type="text" name="company" required maxlength="55" class={inputClass} />
				</div>
				<div>
					<label for="new_text" class="mb-1 block text-xs text-ash-500">Text</label>
					<textarea id="new_text" name="text" rows="1" required maxlength="255" class={inputClass}></textarea>
				</div>
				<input type="hidden" name="order" value={testimonials.length + 1} />
				<button type="submit" class="rounded bg-cyan px-4 py-2 text-sm font-medium text-ash-900 hover:opacity-90">Add</button>
			</form>
		</div>
	</div>

	<div class="divide-y divide-ash-700 rounded-lg border border-ash-700 bg-ash-800 shadow">
		{#each testimonials as item (item.id)}
			{#if editingId === item.id}
				<div class="p-4">
					<form method="POST" action="?/update" use:enhance class="flex flex-col gap-3">
						<input type="hidden" name="id" value={item.id} />
						<div class="grid gap-3 md:grid-cols-3">
							<div>
								<label for="test-name-{item.id}" class="mb-1 block text-xs text-ash-500">Name</label>
								<input id="test-name-{item.id}" type="text" name="name" value={item.name} required maxlength="55" class="w-full {inputClass}" />
							</div>
							<div>
								<label for="test-company-{item.id}" class="mb-1 block text-xs text-ash-500">Company</label>
								<input id="test-company-{item.id}" type="text" name="company" value={item.company} required maxlength="55" class="w-full {inputClass}" />
							</div>
							<div>
								<label for="test-order-{item.id}" class="mb-1 block text-xs text-ash-500">Order</label>
								<input id="test-order-{item.id}" type="number" name="order" value={item.order} min="1" class="w-16 {inputClass}" />
							</div>
						</div>
						<div>
							<label for="test-text-{item.id}" class="mb-1 block text-xs text-ash-500">Text</label>
							<textarea id="test-text-{item.id}" name="text" rows="3" maxlength="255" class="w-full {inputClass}">{item.text}</textarea>
						</div>
						<div class="flex gap-2">
							<button type="submit" class="rounded bg-cyan px-3 py-1.5 text-sm text-ash-900">Save</button>
							<button type="button" class="rounded border border-ash-600 px-3 py-1.5 text-sm text-ash-300 hover:bg-ash-700" onclick={() => (editingId = null)}>Cancel</button>
						</div>
					</form>
				</div>
			{:else}
				<div class="flex flex-wrap items-center justify-between gap-2 px-4 py-3 hover:bg-ash-700/50">
					<div class="text-ash-200"><strong>{item.name}</strong> — {item.company}<br /><span class="text-sm text-ash-400">"{item.text}"</span></div>
					<div class="flex items-center gap-1">
						<a href="/admin/testimonials?orderUp={item.id}" class="rounded border border-ash-600 px-2 py-1 text-xs text-ash-300 hover:bg-ash-700">↑</a>
						<a href="/admin/testimonials?orderDown={item.id}" class="rounded border border-ash-600 px-2 py-1 text-xs text-ash-300 hover:bg-ash-700">↓</a>
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
	<div class="rounded-lg border border-red-500/30 bg-red-500/10 px-4 py-2 text-sm text-red-400">Failed to load testimonials.</div>
{/if}
