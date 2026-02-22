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
	register: async ({ request, cookies }) => {
		const data = await request.formData();
		const name = data.get('name')?.toString();
		const email = data.get('email')?.toString();
		const password = data.get('password')?.toString();
		const password_confirmation = data.get('password_confirmation')?.toString();
		if (!name || !email || !password || !password_confirmation) {
			return fail(400, { message: 'All fields required' });
		}
		if (password !== password_confirmation) {
			return fail(400, { message: 'Passwords do not match' });
		}
		const baseUrl = getBackendUrl();
		const res = await fetch(`${baseUrl}/api/register`, {
			method: 'POST',
			headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
			body: JSON.stringify({ name, email, password, password_confirmation })
		});
		const json = await res.json().catch(() => ({}));
		if (!res.ok) {
			return fail(res.status === 403 ? 403 : 422, {
				message: (json as { message?: string }).message || 'Registration failed'
			});
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
