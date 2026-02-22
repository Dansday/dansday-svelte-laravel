import { redirect } from '@sveltejs/kit';
import { ADMIN_TOKEN_COOKIE } from '$lib/server/admin-auth';
import { api } from '$lib/server/api';
import type { LayoutServerLoad } from './$types';

export const load: LayoutServerLoad = async ({ cookies, url }) => {
	const token = cookies.get(ADMIN_TOKEN_COOKIE) ?? null;
	if (url.pathname === '/admin/login' || url.pathname === '/admin/register') {
		if (token) {
			try {
				await api.getWithAuth('/user', token);
				throw redirect(302, '/admin/home');
			} catch {
				cookies.delete(ADMIN_TOKEN_COOKIE, { path: '/' });
			}
		}
		return { user: null, token: null };
	}
	if (!token) {
		throw redirect(302, '/admin/login');
	}
	try {
		const user = await api.getWithAuth<{ id: number; name: string; email: string }>('/user', token);
		return { user, token };
	} catch {
		cookies.delete(ADMIN_TOKEN_COOKIE, { path: '/' });
		throw redirect(302, '/admin/login');
	}
};
