<script lang="ts">
	import { enhance } from '$app/forms';

	let { data } = $props();
	const profile = $derived(data.profile ?? {});
</script>

<svelte:head><title>Profile</title></svelte:head>

<h1 class="admin-page-title">Profile</h1>

{#if data.form?.message && !data.form?.success}
	<p class="admin-msg admin-msg-error">{data.form.message}</p>
{/if}
{#if data.form?.success}
	<p class="admin-msg admin-msg-success">Saved.</p>
{/if}

{#if profile && profile.id}
	<form method="POST" action="?/update" use:enhance class="admin-card" enctype="multipart/form-data">
		<h2 class="admin-card-title">Account</h2>
		{#if profile.image}
			<input type="hidden" name="image_profile_current" value={profile.image} />
		{/if}
		<div class="admin-form-group">
			<label for="name">Name</label>
			<input id="name" type="text" name="name" value={profile.name ?? ''} maxlength="55" />
		</div>
		<div class="admin-form-group">
			<label for="email">Email</label>
			<input id="email" type="email" name="email" value={profile.email ?? ''} maxlength="55" />
		</div>
		<div class="admin-form-group">
			<label for="image_profile">Profile image (optional)</label>
			<input id="image_profile" type="file" name="image_profile" accept="image/*" />
		</div>
		<h2 class="admin-card-title" style="margin-top: 1.25rem">Change password</h2>
		<div class="admin-form-group">
			<label for="pass_current">Current password</label>
			<input id="pass_current" type="password" name="pass_current" autocomplete="current-password" />
		</div>
		<div class="admin-form-group">
			<label for="pass_new_1">New password</label>
			<input id="pass_new_1" type="password" name="pass_new_1" autocomplete="new-password" minlength="8" />
		</div>
		<div class="admin-form-group">
			<label for="pass_new_2">Confirm new password</label>
			<input id="pass_new_2" type="password" name="pass_new_2" autocomplete="new-password" minlength="8" />
		</div>
		<p class="admin-subsection-title">Leave password fields empty to keep current.</p>
		<div class="admin-form-actions">
			<button type="submit" class="admin-btn admin-btn-accent">Save</button>
		</div>
	</form>
{:else}
	<p class="admin-msg admin-msg-error">Profile not found.</p>
{/if}
