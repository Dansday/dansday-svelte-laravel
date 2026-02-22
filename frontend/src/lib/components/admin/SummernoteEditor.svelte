<script lang="ts">
	import { onMount, onDestroy } from 'svelte';

	interface Props {
		/** CSS selector for the textarea to turn into Summernote (must have name for form submit) */
		selector: string;
		uploadUrl?: string;
	}

	let { selector, uploadUrl = '/admin/api/summernote-upload' }: Props = $props();

	onMount(() => {
		const jq = (window as unknown as { jQuery?: (s: string | HTMLElement) => { summernote: (opts: unknown) => void } }).jQuery;
		if (!jq) return;
		const el = document.querySelector(selector);
		if (!el) return;
		jq(el).summernote({
			height: 280,
			toolbar: [
				['style', ['style']],
				['font', ['bold', 'italic', 'underline', 'clear']],
				['color', ['color']],
				['para', ['ul', 'ol', 'paragraph']],
				['table', ['table']],
				['insert', ['link', 'picture']],
				['view', ['fullscreen', 'codeview']]
			],
			callbacks: {
				onImageUpload(files: File[]) {
					const file = files[0];
					if (!file) return;
					const data = new FormData();
					data.append('file', file);
					data.append('folder', 'uploads/img/temp');
					data.append('code', 'img');
					fetch(uploadUrl, { method: 'POST', body: data, credentials: 'include' })
						.then((r) => r.text())
						.then((url) => {
							jq(el).summernote('insertImage', url);
						})
						.catch(() => {});
				}
			}
		});
	});

	onDestroy(() => {
		const jq = (window as unknown as { jQuery?: (s: string) => { summernote: (a: string) => void } }).jQuery;
		if (!jq) return;
		const el = document.querySelector(selector);
		if (el) jq(el).summernote('destroy');
	});
</script>

<!-- This component only runs Summernote init/destroy; the textarea is rendered by the parent form -->
<div class="contents"></div>
