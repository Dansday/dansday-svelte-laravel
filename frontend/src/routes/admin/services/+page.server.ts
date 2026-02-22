import { fail, redirect } from '@sveltejs/kit';
import { ADMIN_TOKEN_COOKIE } from '$lib/server/admin-auth';
import { api } from '$lib/server/api';
import type { Actions, PageServerLoad } from './$types';

export const load: PageServerLoad = async ({ parent, url }) => {
	const { token } = await parent();
	if (!token) return { services: null };
	const orderUp = url.searchParams.get('orderUp');
	const orderDown = url.searchParams.get('orderDown');
	if (orderUp) {
		await api.getWithAuth(`/admin/services/order-up/${orderUp}`, token);
		throw redirect(303, '/admin/services');
	}
	if (orderDown) {
		await api.getWithAuth(`/admin/services/order-down/${orderDown}`, token);
		throw redirect(303, '/admin/services');
	}
	try {
		const services = await api.getWithAuth<{ id: number; title: string; description: string; info: string; order: number }[]>('/admin/services', token);
		return { services };
	} catch {
		return { services: null };
	}
};

export const actions: Actions = {
	create: async ({ request, cookies }) => {
		const token = cookies.get(ADMIN_TOKEN_COOKIE) ?? null;
		if (!token) return fail(401, { message: 'Unauthorized' });
		const formData = await request.formData();
		const body = {
			title: formData.get('title'),
			description: formData.get('description'),
			info: formData.get('info'),
			order: formData.get('order') ?? 0
		};
		try {
			await api.post('/admin/services', token, body);
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
			description: formData.get('description'),
			info: formData.get('info'),
			order: Number(formData.get('order')) || 0
		};
		try {
			await api.put(`/admin/services/${id}`, token, body);
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
			await api.delete(`/admin/services/${id}`, token);
			return { success: true };
		} catch {
			return fail(500, { message: 'Delete failed' });
		}
	}
};
