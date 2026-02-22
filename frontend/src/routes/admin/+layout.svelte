<script lang="ts">
	import { page } from '$app/state';
	import './admin.css';

	let { data, children } = $props();

	const navLinks = [
		{ href: '/admin/home', label: 'Home' },
		{ href: '/admin/general', label: 'General' },
		{ href: '/admin/sections', label: 'Sections' },
		{ href: '/admin/skills', label: 'Skills' },
		{ href: '/admin/experiences', label: 'Experiences' },
		{ href: '/admin/services', label: 'Services' },
		{ href: '/admin/testimonials', label: 'Testimonials' },
		{ href: '/admin/articles/posts', label: 'Articles' },
		{ href: '/admin/projects/projects', label: 'Projects' },
		{ href: '/admin/profile', label: 'Profile' }
	];
</script>

{#if page.url.pathname === '/admin/login' || page.url.pathname === '/admin/register'}
	<div class="admin-auth-wrap">
		{@render children()}
	</div>
{:else}
	<div class="admin-shell">
		<aside class="admin-sidebar" aria-label="Admin navigation">
			<a href="/admin/home" class="admin-sidebar-logo">Admin</a>
			<nav class="admin-sidebar-nav">
				{#each navLinks as { href, label }}
					<a
						href={href}
						class="admin-sidebar-link"
						class:active={page.url.pathname === href || (href !== '/admin/home' && page.url.pathname.startsWith(href + '/'))}
					>
						{label}
					</a>
				{/each}
			</nav>
			<div class="admin-sidebar-footer">
				{#if data.user}
					<span class="admin-sidebar-user">{data.user.name}</span>
					<form method="POST" action="/admin/logout" style="display:inline">
						<button type="submit" class="admin-sidebar-logout">Log out</button>
					</form>
				{/if}
			</div>
		</aside>
		<main class="admin-content">
			{@render children()}
		</main>
	</div>
{/if}
