<script lang="ts">
	import { enhance } from '$app/forms';

	let { data } = $props();
	const projects = $derived(data.projects ?? []);
	const categories = $derived(data.categories ?? []);
	const catMap = $derived(new Map((categories as { id: number; name: string }[]).map((c) => [c.id, c.name])));
</script>

<svelte:head><title>Projects</title></svelte:head>

<h1 class="admin-page-title">Projects</h1>

{#if data.form?.message && !data.form?.success}
	<p class="admin-msg admin-msg-error">{data.form.message}</p>
{/if}
{#if data.form?.success}
	<p class="admin-msg admin-msg-success">Deleted.</p>
{/if}

{#if projects !== null}
	<div class="admin-form-actions" style="margin-bottom: 1rem;">
		<a href="/admin/projects/projects/new" class="admin-btn admin-btn-accent">New project</a>
	</div>

	<div class="admin-list">
		{#each projects as item (item.id)}
			<div class="admin-list-item">
				<div class="admin-list-item-content">
					<strong>{item.title || '(No title)'}</strong>
					<span style="color: var(--color-ash-500); font-size: 0.8125rem;"> — {catMap.get(item.category_id) ?? item.category_id} {item.enable ? '' : '· Hidden'}</span>
				</div>
				<div class="admin-list-item-actions">
					<a href="/admin/projects/projects?orderUp={item.id}" class="admin-btn admin-btn-ghost">↑</a>
					<a href="/admin/projects/projects?orderDown={item.id}" class="admin-btn admin-btn-ghost">↓</a>
					<a href="/admin/projects/projects/{item.id}" class="admin-btn admin-btn-ghost">Edit</a>
					<form method="POST" action="?/delete" use:enhance style="display:inline;">
						<input type="hidden" name="id" value={item.id} />
						<button type="submit" class="admin-btn admin-btn-danger">Delete</button>
					</form>
				</div>
			</div>
		{/each}
	</div>
{:else}
	<p class="admin-msg admin-msg-error">Failed to load projects.</p>
{/if}
