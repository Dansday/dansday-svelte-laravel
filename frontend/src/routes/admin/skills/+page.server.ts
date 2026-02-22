import { fail, redirect } from '@sveltejs/kit';
import { ADMIN_TOKEN_COOKIE } from '$lib/server/admin-auth';
import { api } from '$lib/server/api';
import type { Actions, PageServerLoad } from './$types';

export const load: PageServerLoad = async ({ parent, url }) => {
	const { token } = await parent();
	if (!token) return { skills: null };
	const orderUp = url.searchParams.get('orderUp');
	const orderDown = url.searchParams.get('orderDown');
	if (orderUp) {
		await api.getWithAuth(`/admin/skills/order-up/${orderUp}`, token);
		throw redirect(303, '/admin/skills');
	}
	if (orderDown) {
		await api.getWithAuth(`/admin/skills/order-down/${orderDown}`, token);
		throw redirect(303, '/admin/skills');
	}
	try {
		const skills = await api.getWithAuth<{ design_skills: { id: number; title: string; type: string; order: number }[]; dev_skills: { id: number; title: string; type: string; order: number }[] }>('/admin/skills', token);
		return { skills };
	} catch {
		return { skills: null };
	}
};

export const actions: Actions = {
	create: async ({ request, cookies }) => {
		const token = cookies.get(ADMIN_TOKEN_COOKIE) ?? null;
		if (!token) return fail(401, { message: 'Unauthorized' });
		const formData = await request.formData();
		const type = (formData.get('type') as string) || 'design';
		const body = {
			type,
			title: formData.get('title'),
			order_design: formData.get('order_design') ?? 0,
			order_dev: formData.get('order_dev') ?? 0
		};
		try {
			await api.post('/admin/skills', token, body);
			return { success: true };
		} catch (err: unknown) {
			const res = (err as { body?: { message?: string } })?.body;
			return fail(422, { message: res?.message ?? 'Create failed' });
		}
	},
	update: async ({ request, cookies }) => {
		const token = cookies.get(ADMIN_TOKEN_COOKIE) ?? null;
		if (!token) return fail(401, { message: 'Unauthorized' });
		const formData = await request.formData();
		const id = formData.get('id');
		if (!id) return fail(400, { message: 'Missing id' });
		const body = {
			title: formData.get('title'),
			type: formData.get('type'),
			order: Number(formData.get('order')) || 0
		};
		try {
			await api.put(`/admin/skills/${id}`, token, body);
			return { success: true };
		} catch (err: unknown) {
			const res = (err as { body?: { message?: string } })?.body;
			return fail(422, { message: res?.message ?? 'Update failed' });
		}
	},
	delete: async ({ request, cookies }) => {
		const token = cookies.get(ADMIN_TOKEN_COOKIE) ?? null;
		if (!token) return fail(401, { message: 'Unauthorized' });
		const formData = await request.formData();
		const id = formData.get('id');
		if (!id) return fail(400, { message: 'Missing id' });
		try {
			await api.delete(`/admin/skills/${id}`, token);
			return { success: true };
		} catch {
			return fail(500, { message: 'Delete failed' });
		}
	}
};
