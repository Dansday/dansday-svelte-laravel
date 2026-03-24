<script lang="ts">
	let { container }: { container: HTMLElement | null } = $props();

	const POINTS_PER_SIDE = 8;
	const VISCOSITY = 20;
	const MOUSE_DIST = 100;
	const DAMPING = 0.15;
	const R = 12;

	type EdgePoint = {
		pos: number;
		offset: number;
		vOffset: number;
	};

	let canvasEl = $state<HTMLCanvasElement | null>(null);
	let topPoints: EdgePoint[] = [];
	let rightPoints: EdgePoint[] = [];
	let bottomPoints: EdgePoint[] = [];
	let leftPoints: EdgePoint[] = [];

	let mouseX = 0;
	let mouseY = 0;
	let mouseSpeedX = 0;
	let mouseSpeedY = 0;
	let lastMouseX = 0;
	let lastMouseY = 0;
	let rafId: number | null = null;

	function createSidePoints(length: number): EdgePoint[] {
		const pts: EdgePoint[] = [];
		const usable = length - 2 * R;
		for (let i = 0; i <= POINTS_PER_SIDE + 1; i++) {
			const t = i / (POINTS_PER_SIDE + 1);
			pts.push({ pos: R + t * usable, offset: 0, vOffset: 0 });
		}
		return pts;
	}

	function moveSidePoints(pts: EdgePoint[], mouseAlong: number, mouseCross: number, speed: number) {
		for (const p of pts) {
			p.vOffset += (0 - p.offset) / VISCOSITY;

			const dAlong = p.pos - mouseAlong;

			if (Math.abs(dAlong) < MOUSE_DIST && Math.abs(mouseCross) < MOUSE_DIST) {
				const influence = (1 - Math.abs(dAlong) / MOUSE_DIST) * (1 - Math.abs(mouseCross) / MOUSE_DIST);
				p.vOffset += (speed / 4) * influence;
			}

			p.vOffset *= 1 - DAMPING;
			p.offset += p.vOffset;
		}
	}

	function animate() {
		rafId = requestAnimationFrame(animate);
		if (!container || !canvasEl) return;

		const rect = container.getBoundingClientRect();
		const pad = 50;

		canvasEl.width = rect.width + pad * 2;
		canvasEl.height = rect.height + pad * 2;
		canvasEl.style.left = `${rect.left - pad}px`;
		canvasEl.style.top = `${rect.top - pad}px`;

		const w = rect.width;
		const h = rect.height;

		if (!topPoints.length || Math.abs(topPoints[topPoints.length - 1].pos - (w - R)) > 10) {
			topPoints = createSidePoints(w);
			rightPoints = createSidePoints(h);
			bottomPoints = createSidePoints(w);
			leftPoints = createSidePoints(h);
		}

		const relX = mouseX - rect.left;
		const relY = mouseY - rect.top;

		moveSidePoints(topPoints, relX, relY, mouseSpeedY);
		moveSidePoints(rightPoints, relY, w - relX, mouseSpeedX);
		moveSidePoints(bottomPoints, relX, h - relY, -mouseSpeedY);
		moveSidePoints(leftPoints, relY, relX, -mouseSpeedX);

		const ctx = canvasEl.getContext('2d');
		if (!ctx) return;

		ctx.clearRect(0, 0, canvasEl.width, canvasEl.height);

		const ox = pad;
		const oy = pad;

		ctx.beginPath();

		ctx.moveTo(ox + R, oy);

		for (let i = 0; i < topPoints.length; i++) {
			const p = topPoints[i];
			const px = ox + p.pos;
			const py = oy - p.offset;
			if (i < topPoints.length - 1) {
				const n = topPoints[i + 1];
				const mx = ox + (p.pos + n.pos) / 2;
				const my = oy - (p.offset + n.offset) / 2;
				ctx.quadraticCurveTo(px, py, mx, my);
			} else {
				ctx.lineTo(ox + w - R, oy);
			}
		}

		ctx.quadraticCurveTo(ox + w, oy, ox + w, oy + R);

		for (let i = 0; i < rightPoints.length; i++) {
			const p = rightPoints[i];
			const px = ox + w + p.offset;
			const py = oy + p.pos;
			if (i < rightPoints.length - 1) {
				const n = rightPoints[i + 1];
				const mx = ox + w + (p.offset + n.offset) / 2;
				const my = oy + (p.pos + n.pos) / 2;
				ctx.quadraticCurveTo(px, py, mx, my);
			} else {
				ctx.lineTo(ox + w, oy + h - R);
			}
		}

		ctx.quadraticCurveTo(ox + w, oy + h, ox + w - R, oy + h);

		for (let i = 0; i < bottomPoints.length; i++) {
			const p = bottomPoints[i];
			const px = ox + w - p.pos;
			const py = oy + h + p.offset;
			if (i < bottomPoints.length - 1) {
				const n = bottomPoints[i + 1];
				const mx = ox + w - (p.pos + n.pos) / 2;
				const my = oy + h + (p.offset + n.offset) / 2;
				ctx.quadraticCurveTo(px, py, mx, my);
			} else {
				ctx.lineTo(ox + R, oy + h);
			}
		}

		ctx.quadraticCurveTo(ox, oy + h, ox, oy + h - R);

		for (let i = 0; i < leftPoints.length; i++) {
			const p = leftPoints[i];
			const px = ox - p.offset;
			const py = oy + h - p.pos;
			if (i < leftPoints.length - 1) {
				const n = leftPoints[i + 1];
				const mx = ox - (p.offset + n.offset) / 2;
				const my = oy + h - (p.pos + n.pos) / 2;
				ctx.quadraticCurveTo(px, py, mx, my);
			} else {
				ctx.lineTo(ox, oy + R);
			}
		}

		ctx.quadraticCurveTo(ox, oy, ox + R, oy);
		ctx.closePath();

		const gradient = ctx.createLinearGradient(ox, oy, ox + w, oy + h);
		gradient.addColorStop(0, 'rgba(168, 208, 230, 0.08)');
		gradient.addColorStop(1, 'rgba(168, 208, 230, 0.03)');
		ctx.fillStyle = gradient;
		ctx.fill();

		ctx.strokeStyle = 'rgba(168, 208, 230, 0.15)';
		ctx.lineWidth = 1.5;
		ctx.stroke();
	}

	$effect(() => {
		if (!container) return;

		const controller = new AbortController();

		window.addEventListener(
			'mousemove',
			(e: MouseEvent) => {
				mouseX = e.clientX;
				mouseY = e.clientY;
			},
			{ signal: controller.signal }
		);

		const speedInterval = setInterval(() => {
			mouseSpeedX = mouseX - lastMouseX;
			mouseSpeedY = mouseY - lastMouseY;
			lastMouseX = mouseX;
			lastMouseY = mouseY;
		}, 50);

		rafId = requestAnimationFrame(animate);

		return () => {
			controller.abort();
			clearInterval(speedInterval);
			if (rafId) cancelAnimationFrame(rafId);
		};
	});
</script>

<canvas bind:this={canvasEl} class="pointer-events-none fixed z-20" aria-hidden="true"></canvas>
