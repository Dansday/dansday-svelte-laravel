<script lang="ts">
	import { enhance } from '$app/forms';

	let { data } = $props();
	const experiences = $derived(data.experiences ?? { edu_experiences: [], emp_experiences: [] });
	const edu = $derived(experiences.edu_experiences ?? []);
	const emp = $derived(experiences.emp_experiences ?? []);
	let editingId = $state<number | null>(null);
	const inputClass = 'rounded border border-ash-600 bg-ash-900 px-2 py-1.5 text-ash-100 focus:border-cyan focus:outline-none';
</script>

<svelte:head><title>Experiences</title></svelte:head>

<div class="mb-6 flex items-center justify-between">
	<h1 class="text-xl font-semibold text-ash-100">Experiences</h1>
</div>

{#if data.form?.message && !data.form?.success}
	<div class="mb-4 rounded-lg border border-red-500/30 bg-red-500/10 px-4 py-2 text-sm text-red-400">{data.form.message}</div>
{/if}
{#if data.form?.success}
	<div class="mb-4 rounded-lg border border-green-500/30 bg-green-500/10 px-4 py-2 text-sm text-green-400">Saved.</div>
{/if}

{#if experiences !== null}
	<div class="mb-6 rounded-lg border border-ash-700 bg-ash-800 shadow">
		<div class="flex flex-wrap items-end justify-between gap-4 border-b border-ash-700 px-4 py-3">
			<h2 class="m-0 font-semibold text-ash-200">Add experience</h2>
			<form method="POST" action="?/create" use:enhance class="flex flex-wrap items-end gap-3">
				<div>
					<label for="new_type" class="mb-1 block text-xs text-ash-500">Type</label>
					<select id="new_type" name="type" class="rounded border border-ash-600 bg-ash-900 px-3 py-2 text-ash-100 focus:border-cyan focus:outline-none">
						<option value="education">Education</option>
						<option value="employment">Employment</option>
					</select>
				</div>
				<div>
					<label for="new_title" class="mb-1 block text-xs text-ash-500">Title</label>
					<input id="new_title" type="text" name="title" required maxlength="55" class={inputClass} />
				</div>
				<div>
					<label for="new_period" class="mb-1 block text-xs text-ash-500">Period</label>
					<input id="new_period" type="text" name="period" required maxlength="55" class={inputClass} />
				</div>
				<div>
					<label for="new_description" class="mb-1 block text-xs text-ash-500">Description</label>
					<textarea id="new_description" name="description" rows="1" required maxlength="255" class={inputClass}></textarea>
				</div>
				<input type="hidden" name="order_edu" value={edu.length + 1} />
				<input type="hidden" name="order_emp" value={emp.length + 1} />
				<button type="submit" class="rounded bg-cyan px-4 py-2 text-sm font-medium text-ash-900 hover:opacity-90">Add</button>
			</form>
		</div>
	</div>

	<div class="mb-6">
		<h2 class="mb-2 text-sm font-semibold text-ash-400">Education</h2>
		<div class="divide-y divide-ash-700 rounded-lg border border-ash-700 bg-ash-800 shadow">
			{#each edu as item (item.id)}
				{#if editingId === item.id}
					<div class="p-4">
						<form method="POST" action="?/update" use:enhance class="flex flex-col gap-3">
							<input type="hidden" name="id" value={item.id} />
							<input type="hidden" name="type" value="education" />
							<div class="grid gap-3 md:grid-cols-3">
								<div>
									<label for="edu-title-{item.id}" class="mb-1 block text-xs text-ash-500">Title</label>
									<input id="edu-title-{item.id}" type="text" name="title" value={item.title} required maxlength="55" class="w-full {inputClass}" />
								</div>
								<div>
									<label for="edu-period-{item.id}" class="mb-1 block text-xs text-ash-500">Period</label>
									<input id="edu-period-{item.id}" type="text" name="period" value={item.period} required maxlength="55" class="w-full {inputClass}" />
								</div>
								<div>
									<label for="edu-order-{item.id}" class="mb-1 block text-xs text-ash-500">Order</label>
									<input id="edu-order-{item.id}" type="number" name="order" value={item.order} min="1" class="w-16 {inputClass}" />
								</div>
							</div>
							<div>
								<label for="edu-desc-{item.id}" class="mb-1 block text-xs text-ash-500">Description</label>
								<textarea id="edu-desc-{item.id}" name="description" rows="2" maxlength="255" class="w-full {inputClass}">{item.description}</textarea>
							</div>
							<div class="flex gap-2">
								<button type="submit" class="rounded bg-cyan px-3 py-1.5 text-sm text-ash-900">Save</button>
								<button type="button" class="rounded border border-ash-600 px-3 py-1.5 text-sm text-ash-300 hover:bg-ash-700" onclick={() => (editingId = null)}>Cancel</button>
							</div>
						</form>
					</div>
				{:else}
					<div class="flex flex-wrap items-center justify-between gap-2 px-4 py-3 hover:bg-ash-700/50">
						<div class="text-ash-200"><strong>{item.title}</strong> — {item.period}<br /><span class="text-sm text-ash-400">{item.description}</span></div>
						<div class="flex items-center gap-1">
							<a href="/admin/experiences?orderUp={item.id}" class="rounded border border-ash-600 px-2 py-1 text-xs text-ash-300 hover:bg-ash-700">↑</a>
							<a href="/admin/experiences?orderDown={item.id}" class="rounded border border-ash-600 px-2 py-1 text-xs text-ash-300 hover:bg-ash-700">↓</a>
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
	</div>

	<div>
		<h2 class="mb-2 text-sm font-semibold text-ash-400">Employment</h2>
		<div class="divide-y divide-ash-700 rounded-lg border border-ash-700 bg-ash-800 shadow">
			{#each emp as item (item.id)}
				{#if editingId === item.id}
					<div class="p-4">
						<form method="POST" action="?/update" use:enhance class="flex flex-col gap-3">
							<input type="hidden" name="id" value={item.id} />
							<input type="hidden" name="type" value="employment" />
							<div class="grid gap-3 md:grid-cols-3">
								<div>
									<label for="emp-title-{item.id}" class="mb-1 block text-xs text-ash-500">Title</label>
									<input id="emp-title-{item.id}" type="text" name="title" value={item.title} required maxlength="55" class="w-full {inputClass}" />
								</div>
								<div>
									<label for="emp-period-{item.id}" class="mb-1 block text-xs text-ash-500">Period</label>
									<input id="emp-period-{item.id}" type="text" name="period" value={item.period} required maxlength="55" class="w-full {inputClass}" />
								</div>
								<div>
									<label for="emp-order-{item.id}" class="mb-1 block text-xs text-ash-500">Order</label>
									<input id="emp-order-{item.id}" type="number" name="order" value={item.order} min="1" class="w-16 {inputClass}" />
								</div>
							</div>
							<div>
								<label for="emp-desc-{item.id}" class="mb-1 block text-xs text-ash-500">Description</label>
								<textarea id="emp-desc-{item.id}" name="description" rows="2" maxlength="255" class="w-full {inputClass}">{item.description}</textarea>
							</div>
							<div class="flex gap-2">
								<button type="submit" class="rounded bg-cyan px-3 py-1.5 text-sm text-ash-900">Save</button>
								<button type="button" class="rounded border border-ash-600 px-3 py-1.5 text-sm text-ash-300 hover:bg-ash-700" onclick={() => (editingId = null)}>Cancel</button>
							</div>
						</form>
					</div>
				{:else}
					<div class="flex flex-wrap items-center justify-between gap-2 px-4 py-3 hover:bg-ash-700/50">
						<div class="text-ash-200"><strong>{item.title}</strong> — {item.period}<br /><span class="text-sm text-ash-400">{item.description}</span></div>
						<div class="flex items-center gap-1">
							<a href="/admin/experiences?orderUp={item.id}" class="rounded border border-ash-600 px-2 py-1 text-xs text-ash-300 hover:bg-ash-700">↑</a>
							<a href="/admin/experiences?orderDown={item.id}" class="rounded border border-ash-600 px-2 py-1 text-xs text-ash-300 hover:bg-ash-700">↓</a>
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
	</div>
{:else}
	<div class="rounded-lg border border-red-500/30 bg-red-500/10 px-4 py-2 text-sm text-red-400">Failed to load experiences.</div>
{/if}
