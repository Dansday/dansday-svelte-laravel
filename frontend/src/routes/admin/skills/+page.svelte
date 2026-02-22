<script lang="ts">
	import { enhance } from '$app/forms';

	let { data } = $props();
	const skills = $derived(data.skills ?? { design_skills: [], dev_skills: [] });
	const design = $derived(skills.design_skills ?? []);
	const dev = $derived(skills.dev_skills ?? []);
	let editingId = $state<number | null>(null);
	const inputClass = 'rounded border border-ash-600 bg-ash-900 px-2 py-1.5 text-ash-100 focus:border-cyan focus:outline-none';
</script>

<svelte:head><title>Skills</title></svelte:head>

<div class="mb-6 flex items-center justify-between">
	<h1 class="text-xl font-semibold text-ash-100">Skills</h1>
</div>

{#if data.form?.message && !data.form?.success}
	<div class="mb-4 rounded-lg border border-red-500/30 bg-red-500/10 px-4 py-2 text-sm text-red-400">{data.form.message}</div>
{/if}
{#if data.form?.success}
	<div class="mb-4 rounded-lg border border-green-500/30 bg-green-500/10 px-4 py-2 text-sm text-green-400">Saved.</div>
{/if}

{#if skills !== null}
	<div class="rounded-lg border border-ash-700 bg-ash-800 shadow mb-6">
		<div class="flex flex-wrap items-end justify-between gap-4 border-b border-ash-700 px-4 py-3">
			<h2 class="m-0 font-semibold text-ash-200">Add skill</h2>
			<form method="POST" action="?/create" use:enhance class="flex flex-wrap items-end gap-3">
				<div>
					<label for="new_type" class="mb-1 block text-xs text-ash-500">Type</label>
					<select id="new_type" name="type" class="rounded border border-ash-600 bg-ash-900 px-3 py-2 text-ash-100 focus:border-cyan focus:outline-none">
						<option value="design">Design</option>
						<option value="development">Development</option>
					</select>
				</div>
				<div>
					<label for="new_title" class="mb-1 block text-xs text-ash-500">Title</label>
					<input id="new_title" type="text" name="title" required maxlength="55" class={inputClass} />
				</div>
				<input type="hidden" name="order_design" value={design.length + 1} />
				<input type="hidden" name="order_dev" value={dev.length + 1} />
				<button type="submit" class="rounded bg-cyan px-4 py-2 text-sm font-medium text-ash-900 hover:opacity-90">Add</button>
			</form>
		</div>
	</div>

	<div class="mb-6">
		<h2 class="mb-2 text-sm font-semibold text-ash-400">Design</h2>
		<div class="overflow-hidden rounded-lg border border-ash-700 bg-ash-800 shadow">
			<table class="w-full text-sm">
				<thead class="border-b border-ash-700 bg-ash-800">
					<tr>
						<th class="px-4 py-2 text-left font-medium text-ash-400">Title</th>
						<th class="w-24 px-4 py-2 text-left font-medium text-ash-400">Order</th>
						<th class="w-48 px-4 py-2 text-right font-medium text-ash-400">Actions</th>
					</tr>
				</thead>
				<tbody class="divide-y divide-ash-700">
					{#each design as item (item.id)}
						{#if editingId === item.id}
							<tr class="bg-ash-800">
								<td colspan="3" class="px-4 py-2">
									<form method="POST" action="?/update" use:enhance class="flex flex-wrap items-center gap-2">
										<input type="hidden" name="id" value={item.id} />
										<input type="hidden" name="type" value="design" />
										<input type="text" name="title" value={item.title} required maxlength="55" class="{inputClass} min-w-[8rem]" />
										<input type="number" name="order" value={item.order} min="1" class="{inputClass} w-16" />
										<button type="submit" class="rounded bg-cyan px-3 py-1.5 text-sm text-ash-900">Save</button>
										<button type="button" class="rounded border border-ash-600 px-3 py-1.5 text-sm text-ash-300 hover:bg-ash-700" onclick={() => (editingId = null)}>Cancel</button>
									</form>
								</td>
							</tr>
						{:else}
							<tr class="bg-ash-800 hover:bg-ash-700/50">
								<td class="px-4 py-2 text-ash-200">{item.title}</td>
								<td class="px-4 py-2 text-ash-400">{item.order}</td>
								<td class="px-4 py-2 text-right">
									<a href="/admin/skills?orderUp={item.id}" class="mr-1 rounded border border-ash-600 px-2 py-1 text-xs text-ash-300 hover:bg-ash-700">↑</a>
									<a href="/admin/skills?orderDown={item.id}" class="mr-1 rounded border border-ash-600 px-2 py-1 text-xs text-ash-300 hover:bg-ash-700">↓</a>
									<button type="button" class="mr-1 rounded border border-ash-600 px-2 py-1 text-xs text-ash-300 hover:bg-ash-700" onclick={() => (editingId = item.id)}>Edit</button>
									<form method="POST" action="?/delete" use:enhance class="inline">
										<input type="hidden" name="id" value={item.id} />
										<button type="submit" class="rounded border border-ash-600 px-2 py-1 text-xs text-red-400 hover:bg-red-500/10">Delete</button>
									</form>
								</td>
							</tr>
						{/if}
					{/each}
				</tbody>
			</table>
		</div>
	</div>

	<div>
		<h2 class="mb-2 text-sm font-semibold text-ash-400">Development</h2>
		<div class="overflow-hidden rounded-lg border border-ash-700 bg-ash-800 shadow">
			<table class="w-full text-sm">
				<thead class="border-b border-ash-700 bg-ash-800">
					<tr>
						<th class="px-4 py-2 text-left font-medium text-ash-400">Title</th>
						<th class="w-24 px-4 py-2 text-left font-medium text-ash-400">Order</th>
						<th class="w-48 px-4 py-2 text-right font-medium text-ash-400">Actions</th>
					</tr>
				</thead>
				<tbody class="divide-y divide-ash-700">
					{#each dev as item (item.id)}
						{#if editingId === item.id}
							<tr class="bg-ash-800">
								<td colspan="3" class="px-4 py-2">
									<form method="POST" action="?/update" use:enhance class="flex flex-wrap items-center gap-2">
										<input type="hidden" name="id" value={item.id} />
										<input type="hidden" name="type" value="development" />
										<input type="text" name="title" value={item.title} required maxlength="55" class="{inputClass} min-w-[8rem]" />
										<input type="number" name="order" value={item.order} min="1" class="{inputClass} w-16" />
										<button type="submit" class="rounded bg-cyan px-3 py-1.5 text-sm text-ash-900">Save</button>
										<button type="button" class="rounded border border-ash-600 px-3 py-1.5 text-sm text-ash-300 hover:bg-ash-700" onclick={() => (editingId = null)}>Cancel</button>
									</form>
								</td>
							</tr>
						{:else}
							<tr class="bg-ash-800 hover:bg-ash-700/50">
								<td class="px-4 py-2 text-ash-200">{item.title}</td>
								<td class="px-4 py-2 text-ash-400">{item.order}</td>
								<td class="px-4 py-2 text-right">
									<a href="/admin/skills?orderUp={item.id}" class="mr-1 rounded border border-ash-600 px-2 py-1 text-xs text-ash-300 hover:bg-ash-700">↑</a>
									<a href="/admin/skills?orderDown={item.id}" class="mr-1 rounded border border-ash-600 px-2 py-1 text-xs text-ash-300 hover:bg-ash-700">↓</a>
									<button type="button" class="mr-1 rounded border border-ash-600 px-2 py-1 text-xs text-ash-300 hover:bg-ash-700" onclick={() => (editingId = item.id)}>Edit</button>
									<form method="POST" action="?/delete" use:enhance class="inline">
										<input type="hidden" name="id" value={item.id} />
										<button type="submit" class="rounded border border-ash-600 px-2 py-1 text-xs text-red-400 hover:bg-red-500/10">Delete</button>
									</form>
								</td>
							</tr>
						{/if}
					{/each}
				</tbody>
			</table>
		</div>
	</div>
{:else}
	<div class="rounded-lg border border-red-500/30 bg-red-500/10 px-4 py-2 text-sm text-red-400">Failed to load skills.</div>
{/if}
