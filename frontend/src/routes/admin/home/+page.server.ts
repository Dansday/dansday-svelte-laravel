import { fail } from '@sveltejs/kit';
import { ADMIN_TOKEN_COOKIE } from '$lib/server/admin-auth';
import { api } from '$lib/server/api';
import type { Actions, PageServerLoad } from './$types';

export const load: PageServerLoad = async ({ parent }) => {
	const { token } = await parent();
	if (!token) return { home: null };
	try {
		const home = await api.getWithAuth<{ title: string | null; description: string | null }>('/admin/home', token);
		return { home };
	} catch {
		return { home: null };
	}
};

export const actions: Actions = {
	update: async ({ request, cookies }) => {
		const token = cookies.get(ADMIN_TOKEN_COOKIE) ?? null;
		if (!token) return fail(401, { message: 'Unauthorized' });
		const formData = await request.formData();
		const body = {
			title: formData.get('title') ?? '',
			description: formData.get('description') ?? ''
		};
		try {
			await api.put('/admin/home', token, body);
			return { success: true };
		} catch (err: unknown) {
			const res = (err as { body?: { message?: string } })?.body;
			return fail(422, { message: res?.message ?? 'Update failed' });
		}
	}
};
