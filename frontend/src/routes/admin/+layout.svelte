<script lang="ts">
	import { page } from '$app/state';
	import { browser } from '$app/environment';
	import './admin.css';

	let { data, children } = $props();

	function clickOutside(node: HTMLElement, onClose: () => void) {
		function handleClick(e: MouseEvent) {
			if (node && !node.contains(e.target as Node)) onClose();
		}
		document.addEventListener('click', handleClick);
		return {
			destroy() {
				document.removeEventListener('click', handleClick);
			}
		};
	}

	const path = $derived(page.url.pathname);
	const isHomeSection = $derived(
		path.startsWith('/admin/home') ||
			path.startsWith('/admin/skills') ||
			path.startsWith('/admin/experiences') ||
			path.startsWith('/admin/testimonials') ||
			path.startsWith('/admin/services')
	);
	const isWorkSection = $derived(path.startsWith('/admin/projects'));
	const isBlogSection = $derived(path.startsWith('/admin/articles'));
	const isSettingsSection = $derived(
		path.startsWith('/admin/general') || path.startsWith('/admin/sections')
	);

	let sectionOverrides = $state<Record<string, boolean>>({});
	const sidebarCollapse = $derived({
		home: sectionOverrides.home ?? !isHomeSection,
		work: sectionOverrides.work ?? !isWorkSection,
		blog: sectionOverrides.blog ?? !isBlogSection,
		settings: sectionOverrides.settings ?? !isSettingsSection
	});
	let userDropdownOpen = $state(false);

	function toggle(id: string) {
		sectionOverrides[id] = !sidebarCollapse[id];
		sectionOverrides = { ...sectionOverrides };
	}
</script>

<svelte:head>
	{#if browser}
		<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet" />
		<script src="https://code.jquery.com/jquery-3.6.0.min.js" defer></script>
		<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote.min.js" defer></script>
	{/if}
</svelte:head>

{#if page.url.pathname === '/admin/login' || page.url.pathname === '/admin/register'}
	<div class="min-h-dvh flex items-center justify-center bg-ash-900 p-4">
		{@render children()}
	</div>
{:else}
	<div class="flex min-h-dvh bg-ash-900">
		<!-- Sidebar (reference: sb-admin-2 style, ash/cyan) -->
		<aside
			class="fixed top-0 left-0 z-30 h-full w-64 shrink-0 border-r border-ash-700 bg-ash-800 transition-transform md:translate-x-0"
			aria-label="Admin navigation"
		>
			<div class="flex h-full flex-col">
				<a
					href="/admin/home"
					class="flex items-center justify-center gap-2 border-b border-ash-700 py-4 text-ash-100 no-underline"
				>
					<span class="font-semibold">Admin</span>
				</a>

				<nav class="flex-1 overflow-y-auto py-4">
					<!-- Dashboard -->
					<div class="px-3 pb-2">
						<a
							href="/admin/home"
							class="block rounded-lg px-3 py-2 text-sm transition-colors {path === '/admin/home'
								? 'bg-ash-700 text-cyan'
								: 'text-ash-400 hover:bg-ash-700 hover:text-ash-200'}"
						>
							Dashboard
						</a>
					</div>

					<div class="my-2 border-t border-ash-700"></div>
					<div class="px-3 pb-1 pt-2 text-xs font-semibold uppercase tracking-wider text-ash-500">Pages</div>

					<!-- Home collapse -->
					<div class="px-2">
						<button
							type="button"
							class="flex w-full items-center justify-between rounded-lg px-3 py-2 text-sm text-ash-300 hover:bg-ash-700 hover:text-ash-100"
							onclick={() => toggle('home')}
						>
							<span>Home</span>
							<span class="text-ash-500 transition-transform {sidebarCollapse.home ? 'rotate-180' : ''}">▼</span>
						</button>
						{#if sidebarCollapse.home}
							<div class="space-y-0.5 py-1 pl-2">
								<a
									href="/admin/home"
									class="block rounded px-3 py-1.5 text-sm {path === '/admin/home' ? 'text-cyan' : 'text-ash-400 hover:text-ash-200'}"
								>Home</a
								>
								<a
									href="/admin/skills"
									class="block rounded px-3 py-1.5 text-sm {path.startsWith('/admin/skills') ? 'text-cyan' : 'text-ash-400 hover:text-ash-200'}"
								>Skills</a
								>
								<a
									href="/admin/experiences"
									class="block rounded px-3 py-1.5 text-sm {path.startsWith('/admin/experiences') ? 'text-cyan' : 'text-ash-400 hover:text-ash-200'}"
								>Experiences</a
								>
								<a
									href="/admin/testimonials"
									class="block rounded px-3 py-1.5 text-sm {path.startsWith('/admin/testimonials') ? 'text-cyan' : 'text-ash-400 hover:text-ash-200'}"
								>Testimonials</a
								>
								<a
									href="/admin/services"
									class="block rounded px-3 py-1.5 text-sm {path.startsWith('/admin/services') ? 'text-cyan' : 'text-ash-400 hover:text-ash-200'}"
								>Services</a
								>
							</div>
						{/if}
					</div>

					<!-- Work (Projects) -->
					<div class="px-2">
						<button
							type="button"
							class="flex w-full items-center justify-between rounded-lg px-3 py-2 text-sm text-ash-300 hover:bg-ash-700 hover:text-ash-100"
							onclick={() => toggle('work')}
						>
							<span>Work</span>
							<span class="text-ash-500 transition-transform {sidebarCollapse.work ? 'rotate-180' : ''}">▼</span>
						</button>
						{#if sidebarCollapse.work}
							<div class="space-y-0.5 py-1 pl-2">
								<a
									href="/admin/projects/projects"
									class="block rounded px-3 py-1.5 text-sm {path.startsWith('/admin/projects') ? 'text-cyan' : 'text-ash-400 hover:text-ash-200'}"
								>Projects</a
								>
							</div>
						{/if}
					</div>

					<!-- Blog (Articles) -->
					<div class="px-2">
						<button
							type="button"
							class="flex w-full items-center justify-between rounded-lg px-3 py-2 text-sm text-ash-300 hover:bg-ash-700 hover:text-ash-100"
							onclick={() => toggle('blog')}
						>
							<span>Blog</span>
							<span class="text-ash-500 transition-transform {sidebarCollapse.blog ? 'rotate-180' : ''}">▼</span>
						</button>
						{#if sidebarCollapse.blog}
							<div class="space-y-0.5 py-1 pl-2">
								<a
									href="/admin/articles/posts"
									class="block rounded px-3 py-1.5 text-sm {path.startsWith('/admin/articles') ? 'text-cyan' : 'text-ash-400 hover:text-ash-200'}"
								>Posts</a
								>
							</div>
						{/if}
					</div>

					<div class="my-2 border-t border-ash-700"></div>
					<div class="px-3 pb-1 pt-2 text-xs font-semibold uppercase tracking-wider text-ash-500">Settings</div>

					<!-- Settings collapse -->
					<div class="px-2">
						<button
							type="button"
							class="flex w-full items-center justify-between rounded-lg px-3 py-2 text-sm text-ash-300 hover:bg-ash-700 hover:text-ash-100"
							onclick={() => toggle('settings')}
						>
							<span>Settings</span>
							<span class="text-ash-500 transition-transform {sidebarCollapse.settings ? 'rotate-180' : ''}">▼</span>
						</button>
						{#if sidebarCollapse.settings}
							<div class="space-y-0.5 py-1 pl-2">
								<a
									href="/admin/general"
									class="block rounded px-3 py-1.5 text-sm {path.startsWith('/admin/general') ? 'text-cyan' : 'text-ash-400 hover:text-ash-200'}"
								>General</a
								>
								<a
									href="/admin/sections"
									class="block rounded px-3 py-1.5 text-sm {path.startsWith('/admin/sections') ? 'text-cyan' : 'text-ash-400 hover:text-ash-200'}"
								>Sections</a
								>
							</div>
						{/if}
					</div>

					<a
						href="/admin/profile"
						class="mx-2 mt-2 block rounded-lg px-3 py-2 text-sm {path.startsWith('/admin/profile') ? 'text-cyan' : 'text-ash-400 hover:bg-ash-700 hover:text-ash-200'}"
					>
						Profile
					</a>
				</nav>
			</div>
		</aside>

		<!-- Content wrapper -->
		<div class="flex min-w-0 flex-1 flex-col pl-64">
			<!-- Topbar (reference: navbar light, shadow) -->
			<header class="sticky top-0 z-20 flex items-center justify-end gap-4 border-b border-ash-700 bg-ash-100 px-4 py-3 shadow">
				<a
					href="/"
					target="_blank"
					rel="noopener"
					class="text-sm text-ash-600 hover:text-ash-800"
				>
					Visit website →
				</a>
				<div class="relative" use:clickOutside={() => (userDropdownOpen = false)}>
					<button
						type="button"
						class="flex items-center gap-2 rounded-lg border border-ash-300 bg-white px-3 py-2 text-sm text-ash-700 hover:bg-ash-50"
						onclick={() => (userDropdownOpen = !userDropdownOpen)}
						onkeydown={(e) => e.key === 'Escape' && (userDropdownOpen = false)}
					>
						{#if data.user}
							<span class="hidden sm:inline">{data.user.name}</span>
							<span class="text-ash-500">▼</span>
						{/if}
					</button>
					{#if userDropdownOpen}
						<div
							class="absolute right-0 top-full z-10 mt-1 w-48 rounded-lg border border-ash-200 bg-white py-2 shadow-lg"
							role="menu"
						>
							<a
								href="/admin/profile"
								class="block px-4 py-2 text-sm text-ash-700 hover:bg-ash-50"
								role="menuitem"
							>Profile</a
							>
							<form method="POST" action="/admin/logout" class="block">
								<button type="submit" class="w-full px-4 py-2 text-left text-sm text-ash-700 hover:bg-ash-50" role="menuitem">
									Log out
								</button>
							</form>
						</div>
					{/if}
				</div>
			</header>

			<main class="flex-1 p-4 md:p-6">
				{@render children()}
			</main>
		</div>
	</div>
{/if}
