<script lang="ts">
	import { enhance } from '$app/forms';

	let { data } = $props();
	const articles = $derived(data.articles ?? []);
	const categories = $derived(data.categories ?? []);
	const catMap = $derived(new Map((categories as { id: number; name: string }[]).map((c) => [c.id, c.name])));
</script>

<svelte:head><title>Articles</title></svelte:head>

<div class="mb-6 flex items-center justify-between">
	<h1 class="text-xl font-semibold text-ash-100">Articles</h1>
	<a href="/admin/articles/posts/new" class="rounded bg-cyan px-4 py-2 text-sm font-medium text-ash-900 hover:opacity-90">New article</a>
</div>

{#if data.form?.message && !data.form?.success}
	<div class="mb-4 rounded-lg border border-red-500/30 bg-red-500/10 px-4 py-2 text-sm text-red-400">{data.form.message}</div>
{/if}
{#if data.form?.success}
	<div class="mb-4 rounded-lg border border-green-500/30 bg-green-500/10 px-4 py-2 text-sm text-green-400">Deleted.</div>
{/if}

{#if articles !== null}
	<div class="overflow-hidden rounded-lg border border-ash-700 bg-ash-800 shadow">
		<table class="w-full text-sm">
			<thead class="border-b border-ash-700 bg-ash-800">
				<tr>
					<th class="px-4 py-2 text-left font-medium text-ash-400">Title</th>
					<th class="w-48 px-4 py-2 text-right font-medium text-ash-400">Actions</th>
				</tr>
			</thead>
			<tbody class="divide-y divide-ash-700">
				{#each articles as item (item.id)}
					<tr class="bg-ash-800 hover:bg-ash-700/50">
						<td class="px-4 py-2 text-ash-200"><strong>{item.title}</strong><span class="ml-1 text-ash-500">— {catMap.get(item.category_id) ?? item.category_id} · {item.status}</span></td>
						<td class="px-4 py-2 text-right">
							<a href="/admin/articles/posts?orderUp={item.id}" class="mr-1 rounded border border-ash-600 px-2 py-1 text-xs text-ash-300 hover:bg-ash-700">↑</a>
							<a href="/admin/articles/posts?orderDown={item.id}" class="mr-1 rounded border border-ash-600 px-2 py-1 text-xs text-ash-300 hover:bg-ash-700">↓</a>
							<a href="/admin/articles/posts/{item.id}" class="mr-1 rounded border border-ash-600 px-2 py-1 text-xs text-ash-300 hover:bg-ash-700">Edit</a>
							<form method="POST" action="?/delete" use:enhance class="inline">
								<input type="hidden" name="id" value={item.id} />
								<button type="submit" class="rounded border border-ash-600 px-2 py-1 text-xs text-red-400 hover:bg-red-500/10">Delete</button>
							</form>
						</td>
					</tr>
				{/each}
			</tbody>
		</table>
	</div>
{:else}
	<div class="rounded-lg border border-red-500/30 bg-red-500/10 px-4 py-2 text-sm text-red-400">Failed to load articles.</div>
{/if}
