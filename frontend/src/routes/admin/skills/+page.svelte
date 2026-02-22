<script lang="ts">
	import { enhance } from '$app/forms';

	let { data } = $props();
	const skills = $derived(data.skills ?? { design_skills: [], dev_skills: [] });
	const design = $derived(skills.design_skills ?? []);
	const dev = $derived(skills.dev_skills ?? []);
	let editingId = $state<number | null>(null);
</script>

<svelte:head><title>Skills</title></svelte:head>

<h1 class="admin-page-title">Skills</h1>

{#if data.form?.message && !data.form?.success}
	<p class="admin-msg admin-msg-error">{data.form.message}</p>
{/if}
{#if data.form?.success}
	<p class="admin-msg admin-msg-success">Saved.</p>
{/if}

{#if skills !== null}
	<div class="admin-card">
		<h2 class="admin-card-title">Add skill</h2>
		<form method="POST" action="?/create" use:enhance class="admin-form-actions" style="flex-wrap: wrap; align-items: flex-end; gap: 1rem;">
			<div class="admin-form-group" style="margin-bottom: 0;">
				<label for="new_type">Type</label>
				<select id="new_type" name="type" style="max-width: 10rem; padding: 0.5rem 0.75rem; background: var(--color-ash-900); border: 1px solid var(--color-ash-600); color: var(--color-ash-100); border-radius: 4px;">
					<option value="design">Design</option>
					<option value="development">Development</option>
				</select>
			</div>
			<div class="admin-form-group" style="margin-bottom: 0;">
				<label for="new_title">Title</label>
				<input id="new_title" type="text" name="title" required maxlength="55" />
			</div>
			<input type="hidden" name="order_design" value={design.length + 1} />
			<input type="hidden" name="order_dev" value={dev.length + 1} />
			<button type="submit" class="admin-btn admin-btn-accent">Add</button>
		</form>
	</div>

	<div class="admin-subsection">
		<h2 class="admin-card-title">Design</h2>
		<div class="admin-list">
			{#each design as item (item.id)}
				{#if editingId === item.id}
					<form method="POST" action="?/update" use:enhance class="admin-list-item" style="flex-wrap: wrap;">
						<input type="hidden" name="id" value={item.id} />
						<input type="hidden" name="type" value="design" />
						<div class="admin-form-group" style="margin-bottom: 0; flex: 1; min-width: 8rem;">
							<input type="text" name="title" value={item.title} required maxlength="55" style="margin: 0;" />
						</div>
						<div class="admin-form-group" style="margin-bottom: 0;">
							<input type="number" name="order" value={item.order} min="1" style="width: 4rem; margin: 0;" />
						</div>
						<div class="admin-list-item-actions">
							<button type="submit" class="admin-btn admin-btn-accent">Save</button>
							<button type="button" class="admin-btn admin-btn-ghost" onclick={() => (editingId = null)}>Cancel</button>
						</div>
					</form>
				{:else}
					<div class="admin-list-item">
						<span class="admin-list-item-content">{item.title}</span>
						<div class="admin-list-item-actions">
							<a href="/admin/skills?orderUp={item.id}" class="admin-btn admin-btn-ghost">↑</a>
							<a href="/admin/skills?orderDown={item.id}" class="admin-btn admin-btn-ghost">↓</a>
							<button type="button" class="admin-btn admin-btn-ghost" onclick={() => (editingId = item.id)}>Edit</button>
							<form method="POST" action="?/delete" use:enhance style="display:inline;">
								<input type="hidden" name="id" value={item.id} />
								<button type="submit" class="admin-btn admin-btn-danger">Delete</button>
							</form>
						</div>
					</div>
				{/if}
			{/each}
		</div>
	</div>

	<div class="admin-subsection">
		<h2 class="admin-card-title">Development</h2>
		<div class="admin-list">
			{#each dev as item (item.id)}
				{#if editingId === item.id}
					<form method="POST" action="?/update" use:enhance class="admin-list-item" style="flex-wrap: wrap;">
						<input type="hidden" name="id" value={item.id} />
						<input type="hidden" name="type" value="development" />
						<div class="admin-form-group" style="margin-bottom: 0; flex: 1; min-width: 8rem;">
							<input type="text" name="title" value={item.title} required maxlength="55" style="margin: 0;" />
						</div>
						<div class="admin-form-group" style="margin-bottom: 0;">
							<input type="number" name="order" value={item.order} min="1" style="width: 4rem; margin: 0;" />
						</div>
						<div class="admin-list-item-actions">
							<button type="submit" class="admin-btn admin-btn-accent">Save</button>
							<button type="button" class="admin-btn admin-btn-ghost" onclick={() => (editingId = null)}>Cancel</button>
						</div>
					</form>
				{:else}
					<div class="admin-list-item">
						<span class="admin-list-item-content">{item.title}</span>
						<div class="admin-list-item-actions">
							<a href="/admin/skills?orderUp={item.id}" class="admin-btn admin-btn-ghost">↑</a>
							<a href="/admin/skills?orderDown={item.id}" class="admin-btn admin-btn-ghost">↓</a>
							<button type="button" class="admin-btn admin-btn-ghost" onclick={() => (editingId = item.id)}>Edit</button>
							<form method="POST" action="?/delete" use:enhance style="display:inline;">
								<input type="hidden" name="id" value={item.id} />
								<button type="submit" class="admin-btn admin-btn-danger">Delete</button>
							</form>
						</div>
					</div>
				{/if}
			{/each}
		</div>
	</div>
{:else}
	<p class="admin-msg admin-msg-error">Failed to load skills.</p>
{/if}
