import { api } from './api';

type Row = Record<string, unknown>;

function formatDate(d: Date | string | null | undefined): string | null {
	if (d == null) return null;
	const date = typeof d === 'string' ? new Date(d) : d;
	if (Number.isNaN(date.getTime())) return null;
	return date.toLocaleDateString('en-US', {
		month: 'short',
		day: 'numeric',
		year: 'numeric'
	});
}

export async function fetchGeneral(): Promise<Record<string, unknown>> {
	const row = await api.get<Row | Record<string, never>>('/general');
	return row ?? {};
}

export async function fetchHome(): Promise<Record<string, unknown>> {
	const row = await api.get<Row>('/home');
	if (!row || Object.keys(row).length === 0) return { title: null, description: null };
	return row;
}

export async function fetchSection(): Promise<Record<string, unknown>> {
	const row = await api.get<Row | Record<string, never>>('/section');
	return row ?? {};
}

export async function fetchAbouts(): Promise<{
	design_skills: Row[];
	dev_skills: Row[];
	edu_experiences: Row[];
	emp_experiences: Row[];
	testimonials: Row[];
	services: Row[];
}> {
	const data = await api.get<{
		design_skills: Row[];
		dev_skills: Row[];
		edu_experiences: Row[];
		emp_experiences: Row[];
		testimonials: Row[];
		services: Row[];
	}>('/abouts');
	return {
		design_skills: data.design_skills ?? [],
		dev_skills: data.dev_skills ?? [],
		edu_experiences: data.edu_experiences ?? [],
		emp_experiences: data.emp_experiences ?? [],
		testimonials: data.testimonials ?? [],
		services: data.services ?? []
	};
}

export async function fetchArticles(): Promise<Row[]> {
	const rows = await api.get<Row[]>('/articles');
	return Array.isArray(rows) ? rows : [];
}

export async function fetchArticle(slug: string): Promise<Record<string, unknown>> {
	const post = await api.get<Row>(`/articles/${encodeURIComponent(slug)}`);
	if (!post) throw new Error('Not found');
	const data: Record<string, unknown> = { ...post };
	data.date_formated = formatDate(post.created_at as string | Date) ?? data.date_formated ?? null;
	return data;
}

export async function fetchProjects(): Promise<{
	projects: Row[];
	projects_categories: Row[];
}> {
	const data = await api.get<{ projects: Row[]; projects_categories: Row[] }>('/projects');
	return {
		projects: data.projects ?? [],
		projects_categories: data.projects_categories ?? []
	};
}

export async function fetchProject(id: number): Promise<Record<string, unknown>> {
	const project = await api.get<Row>(`/projects/${id}`);
	if (!project) throw new Error('Not found');
	return project;
}
