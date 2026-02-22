import { fail } from '@sveltejs/kit';
import { ADMIN_TOKEN_COOKIE } from '$lib/server/admin-auth';
import { api } from '$lib/server/api';
import type { Actions, PageServerLoad } from './$types';

export const load: PageServerLoad = async ({ parent }) => {
	const { token } = await parent();
	if (!token) return { categories: null };
	try {
		const data = await api.getWithAuth<{ categories: { id: number; name: string }[] }>('/admin/articles/post', token);
		return { categories: data.categories };
	} catch {
		return { categories: null };
	}
};

export const actions: Actions = {
	create: async ({ request, cookies }) => {
		const token = cookies.get(ADMIN_TOKEN_COOKIE) ?? null;
		if (!token) return fail(401, { message: 'Unauthorized' });
		const formData = await request.formData();
		try {
			await api.postForm('/admin/articles/post', token, formData);
			return { success: true };
		} catch (err: unknown) {
			const body = (err as { body?: { message?: string } })?.body;
			return fail(422, { message: body?.message ?? 'Create failed' });
		}
	}
};
