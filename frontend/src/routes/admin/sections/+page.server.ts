import { fail } from '@sveltejs/kit';
import { ADMIN_TOKEN_COOKIE } from '$lib/server/admin-auth';
import { api } from '$lib/server/api';
import type { Actions, PageServerLoad } from './$types';

export const load: PageServerLoad = async ({ parent }) => {
	const { token } = await parent();
	if (!token) return { section: null };
	try {
		const section = await api.getWithAuth<Record<string, unknown>>('/admin/sections', token);
		return { section };
	} catch {
		return { section: null };
	}
};

function toBool(v: FormDataEntryValue | null): boolean {
	if (v === null || v === undefined) return false;
	const s = String(v).toLowerCase();
	return s === '1' || s === 'true' || s === 'on' || s === 'yes';
}

export const actions: Actions = {
	update: async ({ request, cookies }) => {
		const token = cookies.get(ADMIN_TOKEN_COOKIE) ?? null;
		if (!token) return fail(401, { message: 'Unauthorized' });
		const formData = await request.formData();
		const body = {
			about_enable: toBool(formData.get('about_enable')),
			about_experience_order: Number(formData.get('about_experience_order')) || 0,
			about_services_order: Number(formData.get('about_services_order')) || 0,
			about_skills_order: Number(formData.get('about_skills_order')) || 0,
			about_testimonial_order: Number(formData.get('about_testimonial_order')) || 0,
			experience_enable: toBool(formData.get('experience_enable')),
			skills_enable: toBool(formData.get('skills_enable')),
			testimonial_enable: toBool(formData.get('testimonial_enable')),
			services_enable: toBool(formData.get('services_enable')),
			projects_enable: toBool(formData.get('projects_enable')),
			articles_enable: toBool(formData.get('articles_enable'))
		};
		try {
			await api.put('/admin/sections', token, body);
			return { success: true };
		} catch (err: unknown) {
			const res = (err as { body?: { message?: string } })?.body;
			return fail(422, { message: res?.message ?? 'Update failed' });
		}
	}
};
