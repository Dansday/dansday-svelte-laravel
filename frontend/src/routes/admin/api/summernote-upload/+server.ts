import { env } from '$env/dynamic/private';
import { ADMIN_TOKEN_COOKIE } from '$lib/server/admin-auth';
import type { RequestHandler } from './$types';

function getBackendUrl(): string {
	const url = env.BACKEND_URL;
	if (!url || typeof url !== 'string' || !url.trim()) throw new Error('BACKEND_URL required');
	return url.trim().replace(/\/$/, '');
}

export const POST: RequestHandler = async ({ request, cookies }) => {
	const token = cookies.get(ADMIN_TOKEN_COOKIE) ?? null;
	if (!token) return new Response('Unauthorized', { status: 401 });
	const formData = await request.formData();
	const file = formData.get('file') ?? formData.get('image');
	if (!file || !(file instanceof File)) return new Response('Missing file', { status: 400 });
	const body = new FormData();
	body.append('file', file);
	body.append('folder', 'uploads/img/temp');
	body.append('code', (formData.get('code') as string) || 'img');
	const backendUrl = getBackendUrl();
	const res = await fetch(`${backendUrl}/api/admin/summernote/upload`, {
		method: 'POST',
		headers: { Authorization: `Bearer ${token}` },
		body
	});
	const text = await res.text();
	if (!res.ok) return new Response(text || 'Upload failed', { status: res.status });
	return new Response(text, { headers: { 'Content-Type': 'text/plain' } });
};
