<script lang="ts">
	import { enhance } from '$app/forms';

	let { form } = $props();
</script>

<svelte:head>
	<title>Admin Login</title>
</svelte:head>

<div class="admin-login">
	<h1>Admin Login</h1>
	<form method="POST" action="?/login" use:enhance>
		<label>
			Email
			<input type="email" name="email" required autocomplete="email" />
		</label>
		<label>
			Password
			<input type="password" name="password" required autocomplete="current-password" />
		</label>
		{#if form?.message}
			<p class="error">{form.message}</p>
		{/if}
		<button type="submit">Log in</button>
	</form>
	<p><a href="/admin/register">Register</a> (only when no users exist)</p>
</div>
