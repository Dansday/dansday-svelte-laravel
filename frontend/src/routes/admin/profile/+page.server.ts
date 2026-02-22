import { fail } from '@sveltejs/kit';
import { ADMIN_TOKEN_COOKIE } from '$lib/server/admin-auth';
import { api } from '$lib/server/api';
import type { Actions, PageServerLoad } from './$types';

export const load: PageServerLoad = async ({ parent }) => {
	const { token } = await parent();
	if (!token) return { profile: null };
	try {
		const profile = await api.getWithAuth<{ id: number; name: string; email: string; image?: string }>('/admin/profile', token);
		return { profile };
	} catch {
		return { profile: null };
	}
};

export const actions: Actions = {
	update: async ({ request, cookies }) => {
		const token = cookies.get(ADMIN_TOKEN_COOKIE) ?? null;
		if (!token) return fail(401, { message: 'Unauthorized' });
		const formData = await request.formData();
		try {
			await api.putForm('/admin/profile', token, formData);
			return { success: true };
		} catch (err: unknown) {
			const body = (err as { body?: { message?: string } })?.body;
			return fail(422, { message: body?.message ?? 'Update failed' });
		}
	}
};
