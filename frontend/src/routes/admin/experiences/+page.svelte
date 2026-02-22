<script lang="ts">
	import { enhance } from '$app/forms';

	let { data } = $props();
	const experiences = $derived(data.experiences ?? { edu_experiences: [], emp_experiences: [] });
	const edu = $derived(experiences.edu_experiences ?? []);
	const emp = $derived(experiences.emp_experiences ?? []);
	let editingId = $state<number | null>(null);
</script>

<svelte:head><title>Experiences</title></svelte:head>

<h1 class="admin-page-title">Experiences</h1>

{#if data.form?.message && !data.form?.success}
	<p class="admin-msg admin-msg-error">{data.form.message}</p>
{/if}
{#if data.form?.success}
	<p class="admin-msg admin-msg-success">Saved.</p>
{/if}

{#if experiences !== null}
	<div class="admin-card">
		<h2 class="admin-card-title">Add experience</h2>
		<form method="POST" action="?/create" use:enhance style="display: grid; gap: 1rem; max-width: 28rem;">
			<div class="admin-form-group" style="margin-bottom: 0;">
				<label for="new_type">Type</label>
				<select id="new_type" name="type" style="max-width: 12rem; padding: 0.5rem 0.75rem; background: var(--color-ash-900); border: 1px solid var(--color-ash-600); color: var(--color-ash-100); border-radius: 4px;">
					<option value="education">Education</option>
					<option value="employment">Employment</option>
				</select>
			</div>
			<div class="admin-form-group" style="margin-bottom: 0;"><label for="new_title">Title</label><input id="new_title" type="text" name="title" required maxlength="55" /></div>
			<div class="admin-form-group" style="margin-bottom: 0;"><label for="new_period">Period</label><input id="new_period" type="text" name="period" required maxlength="55" /></div>
			<div class="admin-form-group" style="margin-bottom: 0;"><label for="new_description">Description</label><textarea id="new_description" name="description" rows="2" required maxlength="255"></textarea></div>
			<input type="hidden" name="order_edu" value={edu.length + 1} />
			<input type="hidden" name="order_emp" value={emp.length + 1} />
			<button type="submit" class="admin-btn admin-btn-accent">Add</button>
		</form>
	</div>

	<div class="admin-subsection">
		<h2 class="admin-card-title">Education</h2>
		<div class="admin-list">
			{#each edu as item (item.id)}
				{#if editingId === item.id}
					<form method="POST" action="?/update" use:enhance class="admin-list-item" style="flex-direction: column; align-items: stretch; gap: 0.75rem;">
						<input type="hidden" name="id" value={item.id} />
						<input type="hidden" name="type" value="education" />
						<div style="display: grid; grid-template-columns: 1fr 1fr auto; gap: 0.5rem; align-items: end;">
							<div class="admin-form-group" style="margin-bottom: 0;"><label>Title</label><input type="text" name="title" value={item.title} required maxlength="55" style="margin: 0;" /></div>
							<div class="admin-form-group" style="margin-bottom: 0;"><label>Period</label><input type="text" name="period" value={item.period} required maxlength="55" style="margin: 0;" /></div>
							<div class="admin-form-group" style="margin-bottom: 0;"><label>Order</label><input type="number" name="order" value={item.order} min="1" style="width: 4rem; margin: 0;" /></div>
						</div>
						<div class="admin-form-group" style="margin-bottom: 0;"><label>Description</label><textarea name="description" rows="2" maxlength="255" style="margin: 0;">{item.description}</textarea></div>
						<div class="admin-form-actions" style="margin-top: 0;"><button type="submit" class="admin-btn admin-btn-accent">Save</button><button type="button" class="admin-btn admin-btn-ghost" onclick={() => (editingId = null)}>Cancel</button></div>
					</form>
				{:else}
					<div class="admin-list-item">
						<div class="admin-list-item-content"><strong>{item.title}</strong> — {item.period}<br /><span style="color: var(--color-ash-400); font-size: 0.875rem;">{item.description}</span></div>
						<div class="admin-list-item-actions">
							<a href="/admin/experiences?orderUp={item.id}" class="admin-btn admin-btn-ghost">↑</a>
							<a href="/admin/experiences?orderDown={item.id}" class="admin-btn admin-btn-ghost">↓</a>
							<button type="button" class="admin-btn admin-btn-ghost" onclick={() => (editingId = item.id)}>Edit</button>
							<form method="POST" action="?/delete" use:enhance style="display:inline;"><input type="hidden" name="id" value={item.id} /><button type="submit" class="admin-btn admin-btn-danger">Delete</button></form>
						</div>
					</div>
				{/if}
			{/each}
		</div>
	</div>

	<div class="admin-subsection">
		<h2 class="admin-card-title">Employment</h2>
		<div class="admin-list">
			{#each emp as item (item.id)}
				{#if editingId === item.id}
					<form method="POST" action="?/update" use:enhance class="admin-list-item" style="flex-direction: column; align-items: stretch; gap: 0.75rem;">
						<input type="hidden" name="id" value={item.id} />
						<input type="hidden" name="type" value="employment" />
						<div style="display: grid; grid-template-columns: 1fr 1fr auto; gap: 0.5rem; align-items: end;">
							<div class="admin-form-group" style="margin-bottom: 0;"><label>Title</label><input type="text" name="title" value={item.title} required maxlength="55" style="margin: 0;" /></div>
							<div class="admin-form-group" style="margin-bottom: 0;"><label>Period</label><input type="text" name="period" value={item.period} required maxlength="55" style="margin: 0;" /></div>
							<div class="admin-form-group" style="margin-bottom: 0;"><label>Order</label><input type="number" name="order" value={item.order} min="1" style="width: 4rem; margin: 0;" /></div>
						</div>
						<div class="admin-form-group" style="margin-bottom: 0;"><label>Description</label><textarea name="description" rows="2" maxlength="255" style="margin: 0;">{item.description}</textarea></div>
						<div class="admin-form-actions" style="margin-top: 0;"><button type="submit" class="admin-btn admin-btn-accent">Save</button><button type="button" class="admin-btn admin-btn-ghost" onclick={() => (editingId = null)}>Cancel</button></div>
					</form>
				{:else}
					<div class="admin-list-item">
						<div class="admin-list-item-content"><strong>{item.title}</strong> — {item.period}<br /><span style="color: var(--color-ash-400); font-size: 0.875rem;">{item.description}</span></div>
						<div class="admin-list-item-actions">
							<a href="/admin/experiences?orderUp={item.id}" class="admin-btn admin-btn-ghost">↑</a>
							<a href="/admin/experiences?orderDown={item.id}" class="admin-btn admin-btn-ghost">↓</a>
							<button type="button" class="admin-btn admin-btn-ghost" onclick={() => (editingId = item.id)}>Edit</button>
							<form method="POST" action="?/delete" use:enhance style="display:inline;"><input type="hidden" name="id" value={item.id} /><button type="submit" class="admin-btn admin-btn-danger">Delete</button></form>
						</div>
					</div>
				{/if}
			{/each}
		</div>
	</div>
{:else}
	<p class="admin-msg admin-msg-error">Failed to load experiences.</p>
{/if}
