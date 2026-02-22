<script lang="ts">
	import { enhance } from '$app/forms';

	let { data } = $props();
	const section = $derived(data.section ?? {});
	const inputClass = 'w-20 rounded border border-ash-600 bg-ash-900 px-2 py-1.5 text-ash-100 focus:border-cyan focus:outline-none';
</script>

<svelte:head><title>Sections</title></svelte:head>

<div class="mb-6 flex items-center justify-between">
	<h1 class="text-xl font-semibold text-ash-100">Sections</h1>
</div>

{#if data.form?.message && !data.form?.success}
	<div class="mb-4 rounded-lg border border-red-500/30 bg-red-500/10 px-4 py-2 text-sm text-red-400">{data.form.message}</div>
{/if}
{#if data.form?.success}
	<div class="mb-4 rounded-lg border border-green-500/30 bg-green-500/10 px-4 py-2 text-sm text-green-400">Saved.</div>
{/if}

{#if section && Object.keys(section).length > 0}
	<div class="rounded-lg border border-ash-700 bg-ash-800 shadow">
		<div class="border-b border-ash-700 px-4 py-3">
			<h2 class="m-0 font-semibold text-ash-200">Visibility and order</h2>
		</div>
		<form method="POST" action="?/update" use:enhance class="p-4">
			<div class="mb-4 space-y-2">
				<label class="flex items-center gap-2 text-sm text-ash-300"><input type="hidden" name="about_enable" value="0" /><input type="checkbox" name="about_enable" value="1" checked={section.about_enable} class="rounded border-ash-600" /> About section</label>
				<label class="flex items-center gap-2 text-sm text-ash-300"><input type="hidden" name="experience_enable" value="0" /><input type="checkbox" name="experience_enable" value="1" checked={section.experience_enable} class="rounded border-ash-600" /> Experience</label>
				<label class="flex items-center gap-2 text-sm text-ash-300"><input type="hidden" name="skills_enable" value="0" /><input type="checkbox" name="skills_enable" value="1" checked={section.skills_enable} class="rounded border-ash-600" /> Skills</label>
				<label class="flex items-center gap-2 text-sm text-ash-300"><input type="hidden" name="services_enable" value="0" /><input type="checkbox" name="services_enable" value="1" checked={section.services_enable} class="rounded border-ash-600" /> Services</label>
				<label class="flex items-center gap-2 text-sm text-ash-300"><input type="hidden" name="testimonial_enable" value="0" /><input type="checkbox" name="testimonial_enable" value="1" checked={section.testimonial_enable} class="rounded border-ash-600" /> Testimonials</label>
				<label class="flex items-center gap-2 text-sm text-ash-300"><input type="hidden" name="projects_enable" value="0" /><input type="checkbox" name="projects_enable" value="1" checked={section.projects_enable} class="rounded border-ash-600" /> Projects</label>
				<label class="flex items-center gap-2 text-sm text-ash-300"><input type="hidden" name="articles_enable" value="0" /><input type="checkbox" name="articles_enable" value="1" checked={section.articles_enable} class="rounded border-ash-600" /> Articles</label>
			</div>
			<p class="mb-2 text-sm font-medium text-ash-400">About block order (0 = first)</p>
			<div class="mb-4 grid gap-3 sm:grid-cols-2 md:grid-cols-4">
				<div>
					<label for="about_experience_order" class="mb-1 block text-xs text-ash-500">Experience order</label>
					<input id="about_experience_order" type="number" name="about_experience_order" value={section.about_experience_order ?? 0} min="0" class={inputClass} />
				</div>
				<div>
					<label for="about_services_order" class="mb-1 block text-xs text-ash-500">Services order</label>
					<input id="about_services_order" type="number" name="about_services_order" value={section.about_services_order ?? 0} min="0" class={inputClass} />
				</div>
				<div>
					<label for="about_skills_order" class="mb-1 block text-xs text-ash-500">Skills order</label>
					<input id="about_skills_order" type="number" name="about_skills_order" value={section.about_skills_order ?? 0} min="0" class={inputClass} />
				</div>
				<div>
					<label for="about_testimonial_order" class="mb-1 block text-xs text-ash-500">Testimonial order</label>
					<input id="about_testimonial_order" type="number" name="about_testimonial_order" value={section.about_testimonial_order ?? 0} min="0" class={inputClass} />
				</div>
			</div>
			<button type="submit" class="rounded bg-cyan px-4 py-2 font-medium text-ash-900 hover:opacity-90">Save</button>
		</form>
	</div>
{:else}
	<div class="rounded-lg border border-red-500/30 bg-red-500/10 px-4 py-2 text-sm text-red-400">Section data not found.</div>
{/if}
