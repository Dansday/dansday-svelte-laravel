import { fail, redirect } from '@sveltejs/kit';
import { ADMIN_TOKEN_COOKIE } from '$lib/server/admin-auth';
import { api } from '$lib/server/api';
import type { Actions, PageServerLoad } from './$types';

export const load: PageServerLoad = async ({ parent, url }) => {
	const { token } = await parent();
	if (!token) return { articles: null, categories: null };
	const orderUp = url.searchParams.get('orderUp');
	const orderDown = url.searchParams.get('orderDown');
	if (orderUp) {
		await api.getWithAuth(`/admin/articles/posts/order-up/${orderUp}`, token);
		throw redirect(303, '/admin/articles/posts');
	}
	if (orderDown) {
		await api.getWithAuth(`/admin/articles/posts/order-down/${orderDown}`, token);
		throw redirect(303, '/admin/articles/posts');
	}
	try {
		const data = await api.getWithAuth<{ articles: { id: number; title: string; slug: string; status: string; category_id: number; order: number }[]; categories: { id: number; name: string }[] }>('/admin/articles/posts', token);
		return { articles: data.articles, categories: data.categories };
	} catch {
		return { articles: null, categories: null };
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
			await api.delete(`/admin/articles/post/${id}`, token);
			return { success: true };
		} catch {
			return fail(500, { message: 'Delete failed' });
		}
	}
};
