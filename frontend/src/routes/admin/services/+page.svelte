<script lang="ts">
	import { enhance } from '$app/forms';

	let { data } = $props();
	const services = $derived(data.services ?? []);
	let editingId = $state<number | null>(null);
</script>

<svelte:head><title>Services</title></svelte:head>

<h1 class="admin-page-title">Services</h1>

{#if data.form?.message && !data.form?.success}
	<p class="admin-msg admin-msg-error">{data.form.message}</p>
{/if}
{#if data.form?.success}
	<p class="admin-msg admin-msg-success">Saved.</p>
{/if}

{#if services !== null}
	<div class="admin-card">
		<h2 class="admin-card-title">Add service</h2>
		<form method="POST" action="?/create" use:enhance style="display: grid; gap: 1rem; max-width: 28rem;">
			<div class="admin-form-group" style="margin-bottom: 0;"><label for="new_title">Title</label><input id="new_title" type="text" name="title" required maxlength="55" /></div>
			<div class="admin-form-group" style="margin-bottom: 0;"><label for="new_description">Description</label><textarea id="new_description" name="description" rows="2" required maxlength="255"></textarea></div>
			<div class="admin-form-group" style="margin-bottom: 0;"><label for="new_info">Info</label><textarea id="new_info" name="info" rows="2" required maxlength="510"></textarea></div>
			<input type="hidden" name="order" value={services.length + 1} />
			<button type="submit" class="admin-btn admin-btn-accent">Add</button>
		</form>
	</div>

	<div class="admin-list">
		{#each services as item (item.id)}
			{#if editingId === item.id}
				<form method="POST" action="?/update" use:enhance class="admin-list-item" style="flex-direction: column; align-items: stretch; gap: 0.75rem;">
					<input type="hidden" name="id" value={item.id} />
					<div class="admin-form-group" style="margin-bottom: 0;"><label>Title</label><input type="text" name="title" value={item.title} required maxlength="55" style="margin: 0;" /></div>
					<div class="admin-form-group" style="margin-bottom: 0;"><label>Description</label><textarea name="description" rows="2" maxlength="255" style="margin: 0;">{item.description}</textarea></div>
					<div class="admin-form-group" style="margin-bottom: 0;"><label>Info</label><textarea name="info" rows="2" maxlength="510" style="margin: 0;">{item.info}</textarea></div>
					<div class="admin-form-group" style="margin-bottom: 0;"><label>Order</label><input type="number" name="order" value={item.order} min="1" style="width: 4rem; margin: 0;" /></div>
					<div class="admin-form-actions" style="margin-top: 0;"><button type="submit" class="admin-btn admin-btn-accent">Save</button><button type="button" class="admin-btn admin-btn-ghost" onclick={() => (editingId = null)}>Cancel</button></div>
				</form>
			{:else}
				<div class="admin-list-item">
					<div class="admin-list-item-content"><strong>{item.title}</strong><br /><span style="color: var(--color-ash-400); font-size: 0.875rem;">{item.description}</span></div>
					<div class="admin-list-item-actions">
						<a href="/admin/services?orderUp={item.id}" class="admin-btn admin-btn-ghost">↑</a>
						<a href="/admin/services?orderDown={item.id}" class="admin-btn admin-btn-ghost">↓</a>
						<button type="button" class="admin-btn admin-btn-ghost" onclick={() => (editingId = item.id)}>Edit</button>
						<form method="POST" action="?/delete" use:enhance style="display:inline;"><input type="hidden" name="id" value={item.id} /><button type="submit" class="admin-btn admin-btn-danger">Delete</button></form>
					</div>
				</div>
			{/if}
		{/each}
	</div>
{:else}
	<p class="admin-msg admin-msg-error">Failed to load services.</p>
{/if}
