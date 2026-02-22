import { fail } from '@sveltejs/kit';
import { ADMIN_TOKEN_COOKIE } from '$lib/server/admin-auth';
import { api } from '$lib/server/api';
import type { Actions, PageServerLoad } from './$types';

export const load: PageServerLoad = async ({ parent }) => {
	const { token } = await parent();
	if (!token) return { general: null };
	try {
		const general = await api.getWithAuth<Record<string, unknown>>('/admin/general', token);
		return { general };
	} catch {
		return { general: null };
	}
};

export const actions: Actions = {
	update: async ({ request, cookies }) => {
		const token = cookies.get(ADMIN_TOKEN_COOKIE) ?? null;
		if (!token) return fail(401, { message: 'Unauthorized' });
		const formData = await request.formData();
		try {
			await api.putForm('/admin/general', token, formData);
			return { success: true };
		} catch (err: unknown) {
			const body = (err as { body?: { message?: string; errors?: Record<string, string[]> } })?.body;
			const message = body?.message ?? (body?.errors ? 'Validation failed' : 'Update failed');
			return fail(body && 'errors' in body ? 422 : 500, { message });
		}
	}
};
