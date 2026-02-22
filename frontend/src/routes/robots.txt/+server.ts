import { env } from '$env/dynamic/private';
import type { RequestHandler } from './$types';

function getBaseUrl(): string {
	const url = env.BASE_URL;
	if (!url || typeof url !== 'string' || !url.trim()) {
		throw new Error('BASE_URL env is required');
	}
	return url.trim().replace(/\/$/, '');
}

export const GET: RequestHandler = async () => {
	const BASE_URL = getBaseUrl();
	return new Response(
		`User-Agent: *
Allow: /
Disallow: /admin

Host: ${BASE_URL}
Sitemap: ${BASE_URL}/sitemap.xml`,
		{
			headers: {
				'Content-Type': 'text/plain',
				'Cache-Control': 'max-age=3600'
			}
		}
	);
};
