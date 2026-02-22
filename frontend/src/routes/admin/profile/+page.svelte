<script lang="ts">
	import { enhance } from '$app/forms';

	let { data } = $props();
	const profile = $derived(data.profile ?? {});
	const inputClass = 'w-full max-w-md rounded border border-ash-600 bg-ash-900 px-3 py-2 text-ash-100 focus:border-cyan focus:outline-none';
</script>

<svelte:head><title>Profile</title></svelte:head>

<div class="mb-6 flex items-center justify-between">
	<h1 class="text-xl font-semibold text-ash-100">Profile</h1>
</div>

{#if data.form?.message && !data.form?.success}
	<div class="mb-4 rounded-lg border border-red-500/30 bg-red-500/10 px-4 py-2 text-sm text-red-400">{data.form.message}</div>
{/if}
{#if data.form?.success}
	<div class="mb-4 rounded-lg border border-green-500/30 bg-green-500/10 px-4 py-2 text-sm text-green-400">Saved.</div>
{/if}

{#if profile && profile.id}
	<div class="rounded-lg border border-ash-700 bg-ash-800 shadow">
		<div class="border-b border-ash-700 px-4 py-3">
			<h2 class="m-0 font-semibold text-ash-200">Account</h2>
		</div>
		<form method="POST" action="?/update" use:enhance class="p-4" enctype="multipart/form-data">
			{#if profile.image}
				<input type="hidden" name="image_profile_current" value={profile.image} />
			{/if}
			<div class="mb-4">
				<label for="name" class="mb-1 block text-sm text-ash-400">Name</label>
				<input id="name" type="text" name="name" value={profile.name ?? ''} maxlength="55" class={inputClass} />
			</div>
			<div class="mb-4">
				<label for="email" class="mb-1 block text-sm text-ash-400">Email</label>
				<input id="email" type="email" name="email" value={profile.email ?? ''} maxlength="55" class={inputClass} />
			</div>
			<div class="mb-4">
				<label for="image_profile" class="mb-1 block text-sm text-ash-400">Profile image (optional)</label>
				<input id="image_profile" type="file" name="image_profile" accept="image/*" class="text-ash-300" />
			</div>
			<div class="mt-6 border-t border-ash-700 pt-4">
				<h3 class="mb-3 font-semibold text-ash-200">Change password</h3>
				<div class="mb-4">
					<label for="pass_current" class="mb-1 block text-sm text-ash-400">Current password</label>
					<input id="pass_current" type="password" name="pass_current" autocomplete="current-password" class={inputClass} />
				</div>
				<div class="mb-4">
					<label for="pass_new_1" class="mb-1 block text-sm text-ash-400">New password</label>
					<input id="pass_new_1" type="password" name="pass_new_1" autocomplete="new-password" minlength="8" class={inputClass} />
				</div>
				<div class="mb-4">
					<label for="pass_new_2" class="mb-1 block text-sm text-ash-400">Confirm new password</label>
					<input id="pass_new_2" type="password" name="pass_new_2" autocomplete="new-password" minlength="8" class={inputClass} />
				</div>
				<p class="mb-4 text-sm text-ash-500">Leave password fields empty to keep current.</p>
			</div>
			<button type="submit" class="rounded bg-cyan px-4 py-2 font-medium text-ash-900 hover:opacity-90">Save</button>
		</form>
	</div>
{:else}
	<div class="rounded-lg border border-red-500/30 bg-red-500/10 px-4 py-2 text-sm text-red-400">Profile not found.</div>
{/if}
