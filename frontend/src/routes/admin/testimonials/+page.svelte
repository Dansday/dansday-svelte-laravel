<script lang="ts">
	import { enhance } from '$app/forms';

	let { data } = $props();
	const testimonials = $derived(data.testimonials ?? []);
	let editingId = $state<number | null>(null);
</script>

<svelte:head><title>Testimonials</title></svelte:head>

<h1 class="admin-page-title">Testimonials</h1>

{#if data.form?.message && !data.form?.success}
	<p class="admin-msg admin-msg-error">{data.form.message}</p>
{/if}
{#if data.form?.success}
	<p class="admin-msg admin-msg-success">Saved.</p>
{/if}

{#if testimonials !== null}
	<div class="admin-card">
		<h2 class="admin-card-title">Add testimonial</h2>
		<form method="POST" action="?/create" use:enhance style="display: grid; gap: 1rem; max-width: 28rem;">
			<div class="admin-form-group" style="margin-bottom: 0;"><label for="new_name">Name</label><input id="new_name" type="text" name="name" required maxlength="55" /></div>
			<div class="admin-form-group" style="margin-bottom: 0;"><label for="new_company">Company</label><input id="new_company" type="text" name="company" required maxlength="55" /></div>
			<div class="admin-form-group" style="margin-bottom: 0;"><label for="new_text">Text</label><textarea id="new_text" name="text" rows="3" required maxlength="255"></textarea></div>
			<input type="hidden" name="order" value={testimonials.length + 1} />
			<button type="submit" class="admin-btn admin-btn-accent">Add</button>
		</form>
	</div>

	<div class="admin-list">
		{#each testimonials as item (item.id)}
			{#if editingId === item.id}
				<form method="POST" action="?/update" use:enhance class="admin-list-item" style="flex-direction: column; align-items: stretch; gap: 0.75rem;">
					<input type="hidden" name="id" value={item.id} />
					<div class="admin-form-group" style="margin-bottom: 0;"><label>Name</label><input type="text" name="name" value={item.name} required maxlength="55" style="margin: 0;" /></div>
					<div class="admin-form-group" style="margin-bottom: 0;"><label>Company</label><input type="text" name="company" value={item.company} required maxlength="55" style="margin: 0;" /></div>
					<div class="admin-form-group" style="margin-bottom: 0;"><label>Text</label><textarea name="text" rows="3" maxlength="255" style="margin: 0;">{item.text}</textarea></div>
					<div class="admin-form-group" style="margin-bottom: 0;"><label>Order</label><input type="number" name="order" value={item.order} min="1" style="width: 4rem; margin: 0;" /></div>
					<div class="admin-form-actions" style="margin-top: 0;">
						<button type="submit" class="admin-btn admin-btn-accent">Save</button>
						<button type="button" class="admin-btn admin-btn-ghost" onclick={() => (editingId = null)}>Cancel</button>
					</div>
				</form>
			{:else}
				<div class="admin-list-item">
					<div class="admin-list-item-content">
						<strong>{item.name}</strong> — {item.company}<br />
						<span style="color: var(--color-ash-400); font-size: 0.875rem;">"{item.text}"</span>
					</div>
					<div class="admin-list-item-actions">
						<a href="/admin/testimonials?orderUp={item.id}" class="admin-btn admin-btn-ghost">↑</a>
						<a href="/admin/testimonials?orderDown={item.id}" class="admin-btn admin-btn-ghost">↓</a>
						<button type="button" class="admin-btn admin-btn-ghost" onclick={() => (editingId = item.id)}>Edit</button>
						<form method="POST" action="?/delete" use:enhance style="display:inline;"><input type="hidden" name="id" value={item.id} /><button type="submit" class="admin-btn admin-btn-danger">Delete</button></form>
					</div>
				</div>
			{/if}
		{/each}
	</div>
{:else}
	<p class="admin-msg admin-msg-error">Failed to load testimonials.</p>
{/if}
