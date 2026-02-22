<script lang="ts">
	import { enhance } from '$app/forms';

	let { data } = $props();
	const section = $derived(data.section ?? {});
</script>

<svelte:head><title>Sections</title></svelte:head>

<h1 class="admin-page-title">Sections</h1>

{#if data.form?.message && !data.form?.success}
	<p class="admin-msg admin-msg-error">{data.form.message}</p>
{/if}
{#if data.form?.success}
	<p class="admin-msg admin-msg-success">Saved.</p>
{/if}

{#if section && Object.keys(section).length > 0}
	<form method="POST" action="?/update" use:enhance class="admin-card">
		<h2 class="admin-card-title">Visibility & order</h2>
		<div class="admin-form-group">
			<label><input type="hidden" name="about_enable" value="0" /><input type="checkbox" name="about_enable" value="1" checked={section.about_enable} /> About section</label>
		</div>
		<div class="admin-form-group">
			<label><input type="hidden" name="experience_enable" value="0" /><input type="checkbox" name="experience_enable" value="1" checked={section.experience_enable} /> Experience</label>
		</div>
		<div class="admin-form-group">
			<label><input type="hidden" name="skills_enable" value="0" /><input type="checkbox" name="skills_enable" value="1" checked={section.skills_enable} /> Skills</label>
		</div>
		<div class="admin-form-group">
			<label><input type="hidden" name="services_enable" value="0" /><input type="checkbox" name="services_enable" value="1" checked={section.services_enable} /> Services</label>
		</div>
		<div class="admin-form-group">
			<label><input type="hidden" name="testimonial_enable" value="0" /><input type="checkbox" name="testimonial_enable" value="1" checked={section.testimonial_enable} /> Testimonials</label>
		</div>
		<div class="admin-form-group">
			<label><input type="hidden" name="projects_enable" value="0" /><input type="checkbox" name="projects_enable" value="1" checked={section.projects_enable} /> Projects</label>
		</div>
		<div class="admin-form-group">
			<label><input type="hidden" name="articles_enable" value="0" /><input type="checkbox" name="articles_enable" value="1" checked={section.articles_enable} /> Articles</label>
		</div>
		<p class="admin-subsection-title">About block order (0 = first)</p>
		<div class="admin-form-group">
			<label for="about_experience_order">Experience order</label>
			<input id="about_experience_order" type="number" name="about_experience_order" value={section.about_experience_order ?? 0} min="0" />
		</div>
		<div class="admin-form-group">
			<label for="about_services_order">Services order</label>
			<input id="about_services_order" type="number" name="about_services_order" value={section.about_services_order ?? 0} min="0" />
		</div>
		<div class="admin-form-group">
			<label for="about_skills_order">Skills order</label>
			<input id="about_skills_order" type="number" name="about_skills_order" value={section.about_skills_order ?? 0} min="0" />
		</div>
		<div class="admin-form-group">
			<label for="about_testimonial_order">Testimonial order</label>
			<input id="about_testimonial_order" type="number" name="about_testimonial_order" value={section.about_testimonial_order ?? 0} min="0" />
		</div>
		<div class="admin-form-actions">
			<button type="submit" class="admin-btn admin-btn-accent">Save</button>
		</div>
	</form>
{:else}
	<p class="admin-msg admin-msg-error">Section data not found.</p>
{/if}
