import { fail, redirect } from '@sveltejs/kit';
import { env } from '$env/dynamic/private';
import { ADMIN_TOKEN_COOKIE } from '$lib/server/admin-auth';
import type { Actions } from './$types';

function getBackendUrl(): string {
	const url = env.BACKEND_URL;
	if (!url || typeof url !== 'string' || !url.trim()) {
		throw new Error('BACKEND_URL env is required');
	}
	return url.trim().replace(/\/$/, '');
}

export const actions: Actions = {
	login: async ({ request, cookies }) => {
		const data = await request.formData();
		const email = data.get('email')?.toString();
		const password = data.get('password')?.toString();
		if (!email || !password) {
			return fail(400, { message: 'Email and password required' });
		}
		const baseUrl = getBackendUrl();
		const res = await fetch(`${baseUrl}/api/login`, {
			method: 'POST',
			headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
			body: JSON.stringify({ email, password })
		});
		const json = await res.json().catch(() => ({}));
		if (!res.ok) {
			return fail(401, { message: (json as { message?: string }).message || 'Login failed' });
		}
		const token = (json as { token?: string }).token;
		if (!token) {
			return fail(500, { message: 'No token returned' });
		}
		cookies.set(ADMIN_TOKEN_COOKIE, token, {
			path: '/',
			httpOnly: true,
			secure: import.meta.env.PROD,
			sameSite: 'lax',
			maxAge: 60 * 60 * 24 * 7
		});
		throw redirect(302, '/admin/home');
	}
};
