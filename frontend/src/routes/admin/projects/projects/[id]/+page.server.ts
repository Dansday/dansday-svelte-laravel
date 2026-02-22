import { fail } from '@sveltejs/kit';
import { ADMIN_TOKEN_COOKIE } from '$lib/server/admin-auth';
import { api } from '$lib/server/api';
import type { Actions, PageServerLoad } from './$types';

export const load: PageServerLoad = async ({ parent, params }) => {
	const { token } = await parent();
	if (!token) return { project: null, categories: null };
	try {
		const data = await api.getWithAuth<{ project: Record<string, unknown>; categories: { id: number; name: string }[] }>(`/admin/projects/project/${params.id}`, token);
		return { project: data.project, categories: data.categories };
	} catch {
		return { project: null, categories: null };
	}
};

export const actions: Actions = {
	update: async ({ request, cookies, params }) => {
		const token = cookies.get(ADMIN_TOKEN_COOKIE) ?? null;
		if (!token) return fail(401, { message: 'Unauthorized' });
		const formData = await request.formData();
		try {
			await api.putForm(`/admin/projects/project/${params.id}`, token, formData);
			return { success: true };
		} catch (err: unknown) {
			const body = (err as { body?: { message?: string } })?.body;
			return fail(422, { message: body?.message ?? 'Update failed' });
		}
	}
};
