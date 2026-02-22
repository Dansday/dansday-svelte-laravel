import { api } from '$lib/server/api';
import type { PageServerLoad } from './$types';

export const load: PageServerLoad = async ({ parent }) => {
	const { token } = await parent();
	if (!token) return { testimonials: null };
	try {
		const testimonials = await api.getWithAuth('/admin/testimonials', token);
		return { testimonials };
	} catch {
		return { testimonials: null };
	}
};
