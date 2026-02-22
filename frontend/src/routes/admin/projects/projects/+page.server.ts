import { fail, redirect } from '@sveltejs/kit';
import { ADMIN_TOKEN_COOKIE } from '$lib/server/admin-auth';
import { api } from '$lib/server/api';
import type { Actions, PageServerLoad } from './$types';

export const load: PageServerLoad = async ({ parent, url }) => {
	const { token } = await parent();
	if (!token) return { projects: null, categories: null };
	const orderUp = url.searchParams.get('orderUp');
	const orderDown = url.searchParams.get('orderDown');
	if (orderUp) {
		await api.getWithAuth(`/admin/projects/projects/order-up/${orderUp}`, token);
		throw redirect(303, '/admin/projects/projects');
	}
	if (orderDown) {
		await api.getWithAuth(`/admin/projects/projects/order-down/${orderDown}`, token);
		throw redirect(303, '/admin/projects/projects');
	}
	try {
		const data = await api.getWithAuth<{ projects: { id: number; title: string; category_id: number; order: number; enable: number }[]; categories: { id: number; name: string }[] }>('/admin/projects/projects', token);
		return { projects: data.projects, categories: data.categories };
	} catch {
		return { projects: null, categories: null };
	}
};

export const actions: Actions = {
	delete: async ({ request, cookies }) => {
		const token = cookies.get(ADMIN_TOKEN_COOKIE) ?? null;
		if (!token) return fail(401, { message: 'Unauthorized' });
		const formData = await request.formData();
		const id = formData.get('id');
		if (!id) return fail(400, { message: 'Missing id' });
		try {
			await api.delete(`/admin/projects/project/${id}`, token);
			return { success: true };
		} catch {
			return fail(500, { message: 'Delete failed' });
		}
	}
};
