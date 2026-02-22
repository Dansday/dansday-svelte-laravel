import { fail, redirect } from '@sveltejs/kit';
import { ADMIN_TOKEN_COOKIE } from '$lib/server/admin-auth';
import { api } from '$lib/server/api';
import type { Actions, PageServerLoad } from './$types';

export const load: PageServerLoad = async ({ parent, url }) => {
	const { token } = await parent();
	if (!token) return { experiences: null };
	const orderUp = url.searchParams.get('orderUp');
	const orderDown = url.searchParams.get('orderDown');
	if (orderUp) {
		await api.getWithAuth(`/admin/experiences/order-up/${orderUp}`, token);
		throw redirect(303, '/admin/experiences');
	}
	if (orderDown) {
		await api.getWithAuth(`/admin/experiences/order-down/${orderDown}`, token);
		throw redirect(303, '/admin/experiences');
	}
	try {
		const experiences = await api.getWithAuth<{ edu_experiences: { id: number; title: string; period: string; description: string; type: string; order: number }[]; emp_experiences: { id: number; title: string; period: string; description: string; type: string; order: number }[] }>('/admin/experiences', token);
		return { experiences };
	} catch {
		return { experiences: null };
	}
};

export const actions: Actions = {
	create: async ({ request, cookies }) => {
		const token = cookies.get(ADMIN_TOKEN_COOKIE) ?? null;
		if (!token) return fail(401, { message: 'Unauthorized' });
		const formData = await request.formData();
		const type = (formData.get('type') as string) || 'education';
		const body = {
			type,
			title: formData.get('title'),
			period: formData.get('period'),
			description: formData.get('description'),
			order_edu: formData.get('order_edu') ?? 0,
			order_emp: formData.get('order_emp') ?? 0
		};
		try {
			await api.post('/admin/experiences', token, body);
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
			period: formData.get('period'),
			description: formData.get('description'),
			type: formData.get('type'),
			order: Number(formData.get('order')) || 0
		};
		try {
			await api.put(`/admin/experiences/${id}`, token, body);
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
			await api.delete(`/admin/experiences/${id}`, token);
			return { success: true };
		} catch {
			return fail(500, { message: 'Delete failed' });
		}
	}
};
