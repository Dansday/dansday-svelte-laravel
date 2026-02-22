import { env } from '$env/dynamic/private';

function getBaseUrl(): string {
	const url = env.BACKEND_URL;
	if (!url || typeof url !== 'string' || !url.trim()) {
		throw new Error('BACKEND_URL env is required');
	}
	return url.trim().replace(/\/$/, '');
}

function buildUrl(path: string): string {
	const baseUrl = getBaseUrl();
	return `${baseUrl}/api${path.startsWith('/') ? path : `/${path}`}`;
}

async function request<T>(method: string, path: string, options?: { token?: string | null; body?: unknown }): Promise<T> {
	const url = buildUrl(path);
	const headers: Record<string, string> = { Accept: 'application/json' };
	if (options?.token) {
		headers['Authorization'] = `Bearer ${options.token}`;
	}
	if (options?.body !== undefined && options?.body !== null) {
		headers['Content-Type'] = 'application/json';
	}
	const res = await fetch(url, {
		method,
		headers,
		body: options?.body !== undefined && options?.body !== null ? JSON.stringify(options.body) : undefined
	});
	if (!res.ok) {
		const err = new Error(res.status === 404 ? 'Not found' : `API error: ${res.status} ${res.statusText}`);
		(err as Error & { status?: number }).status = res.status;
		throw err;
	}
	const text = await res.text();
	return (text ? JSON.parse(text) : null) as T;
}

/** Main site (public): GET with no auth */
async function get<T>(path: string): Promise<T> {
	return request<T>('GET', path);
}

/** Admin: GET with optional token */
function getWithAuth<T>(path: string, token: string | null): Promise<T> {
	return request<T>('GET', path, { token });
}

/** Admin: POST with token and optional body */
function post<T>(path: string, token: string | null, body?: unknown): Promise<T> {
	return request<T>('POST', path, { token, body });
}

/** Admin: PUT with token and optional body */
function put<T>(path: string, token: string | null, body?: unknown): Promise<T> {
	return request<T>('PUT', path, { token, body });
}

/** Admin: DELETE with token */
function del<T>(path: string, token: string | null): Promise<T> {
	return request<T>('DELETE', path, { token });
}

/** Admin: POST with FormData (e.g. file upload) */
async function postForm(path: string, token: string | null, formData: FormData): Promise<unknown> {
	const url = buildUrl(path);
	const headers: Record<string, string> = { Accept: 'application/json' };
	if (token) headers['Authorization'] = `Bearer ${token}`;
	const res = await fetch(url, { method: 'POST', headers, body: formData });
	if (!res.ok) {
		const text = await res.text();
		let err: Error & { status?: number; body?: unknown } = new Error(res.status === 404 ? 'Not found' : `API error: ${res.status}`);
		err.status = res.status;
		try {
			err.body = text ? JSON.parse(text) : null;
		} catch {
			err.body = { message: text };
		}
		throw err;
	}
	const text = await res.text();
	return text ? JSON.parse(text) : null;
}

/** Admin: PUT with FormData (e.g. file upload). Laravel method spoofing via _method=PUT */
async function putForm(path: string, token: string | null, formData: FormData): Promise<unknown> {
	const url = buildUrl(path);
	const body = new FormData();
	for (const [k, v] of formData.entries()) body.append(k, v);
	body.append('_method', 'PUT');
	const headers: Record<string, string> = { Accept: 'application/json' };
	if (token) headers['Authorization'] = `Bearer ${token}`;
	const res = await fetch(url, { method: 'POST', headers, body });
	if (!res.ok) {
		const text = await res.text();
		let err: Error & { status?: number; body?: unknown } = new Error(res.status === 404 ? 'Not found' : `API error: ${res.status}`);
		err.status = res.status;
		try {
			err.body = text ? JSON.parse(text) : null;
		} catch {
			err.body = { message: text };
		}
		throw err;
	}
	const text = await res.text();
	return text ? JSON.parse(text) : null;
}

export const api = {
	get,
	getWithAuth,
	post,
	postForm,
	put,
	putForm,
	delete: del,
	getBaseUrl,
	buildUrl
};
