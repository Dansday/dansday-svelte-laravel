<script lang="ts">
	import { enhance } from '$app/forms';

	let { form } = $props();
</script>

<svelte:head>
	<title>Admin Register</title>
</svelte:head>

<div class="admin-register">
	<h1>Register</h1>
	<form method="POST" action="?/register" use:enhance>
		<label>
			Name
			<input type="text" name="name" required autocomplete="name" />
		</label>
		<label>
			Email
			<input type="email" name="email" required autocomplete="email" />
		</label>
		<label>
			Password
			<input type="password" name="password" required autocomplete="new-password" />
		</label>
		<label>
			Confirm password
			<input type="password" name="password_confirmation" required autocomplete="new-password" />
		</label>
		{#if form?.message}
			<p class="error">{form.message}</p>
		{/if}
		<button type="submit">Register</button>
	</form>
	<p><a href="/admin/login">Log in</a></p>
</div>
