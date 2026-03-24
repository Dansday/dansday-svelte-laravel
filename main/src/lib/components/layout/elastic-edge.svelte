<script lang="ts">
	let { container }: { container: HTMLElement | null } = $props();

	const POINTS_PER_SIDE = 6;
	const VISCOSITY = 20;
	const MOUSE_DIST = 80;
	const DAMPING = 0.15;
	const BORDER_RADIUS_PX = 12;

	type EdgePoint = {
		pos: number;
		iPos: number;
		offset: number;
		vOffset: number;
	};

	let topPoints = $state<EdgePoint[]>([]);
	let rightPoints = $state<EdgePoint[]>([]);
	let bottomPoints = $state<EdgePoint[]>([]);
	let leftPoints = $state<EdgePoint[]>([]);

	let mouseX = 0;
	let mouseY = 0;
	let mouseSpeedX = 0;
	let mouseSpeedY = 0;
	let lastMouseX = 0;
	let lastMouseY = 0;
	let rafId: number | null = null;
	let cWidth = 0;
	let cHeight = 0;

	function createSidePoints(length: number): EdgePoint[] {
		const pts: EdgePoint[] = [];
		const r = BORDER_RADIUS_PX;
		for (let i = 0; i <= POINTS_PER_SIDE + 1; i++) {
			const t = i / (POINTS_PER_SIDE + 1);
			const p = r + t * (length - 2 * r);
			pts.push({ pos: p, iPos: p, offset: 0, vOffset: 0 });
		}
		return pts;
	}

	function initPoints() {
		if (!container) return;
		const rect = container.getBoundingClientRect();
		cWidth = rect.width;
		cHeight = rect.height;
		topPoints = createSidePoints(rect.width);
		rightPoints = createSidePoints(rect.height);
		bottomPoints = createSidePoints(rect.width);
		leftPoints = createSidePoints(rect.height);
	}

	$effect(() => {
		if (container) {
			requestAnimationFrame(() => initPoints());
		}
	});

	function moveSidePoints(pts: EdgePoint[], mouseAlongAxis: number, mouseCrossAxis: number, speed: number) {
		for (const p of pts) {
			p.vOffset += (0 - p.offset) / VISCOSITY;

			const dAlong = p.pos - mouseAlongAxis;

			if (Math.abs(dAlong) < MOUSE_DIST && Math.abs(mouseCrossAxis) < MOUSE_DIST) {
				const influence = 1 - Math.abs(dAlong) / MOUSE_DIST;
				p.vOffset += (speed / 6) * influence;
			}

			p.vOffset *= 1 - DAMPING;
			p.offset += p.vOffset;
		}
	}

	function animate() {
		rafId = requestAnimationFrame(animate);
		if (!container) return;

		const rect = container.getBoundingClientRect();
		cWidth = rect.width;
		cHeight = rect.height;

		const relX = mouseX - rect.x;
		const relY = mouseY - rect.y;

		moveSidePoints(topPoints, relX, relY, mouseSpeedY);
		moveSidePoints(bottomPoints, cWidth - relX, cHeight - relY, -mouseSpeedY);
		moveSidePoints(rightPoints, relY, cWidth - relX, -mouseSpeedX);
		moveSidePoints(leftPoints, cHeight - relY, relX, mouseSpeedX);

		topPoints = [...topPoints];
		rightPoints = [...rightPoints];
		bottomPoints = [...bottomPoints];
		leftPoints = [...leftPoints];
	}

	$effect(() => {
		if (!container) return;

		const controller = new AbortController();

		window.addEventListener('mousemove', (e: MouseEvent) => {
			mouseX = e.clientX;
			mouseY = e.clientY;
		}, { signal: controller.signal });

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

	function n(px: number, total: number): number {
		return total > 0 ? px / total : 0;
	}

	function buildPath(): string {
		const w = cWidth;
		const h = cHeight;
		if (!w || !h || !topPoints.length) return 'M0,0 H1 V1 H0 Z';

		const r = BORDER_RADIUS_PX;
		const rx = n(r, w);
		const ry = n(r, h);

		let d = `M${rx},0`;

		for (let i = 0; i < topPoints.length; i++) {
			const p = topPoints[i];
			const px = n(p.pos, w);
			const py = n(p.offset, h);
			if (i < topPoints.length - 1) {
				const np = topPoints[i + 1];
				const nx = (px + n(np.pos, w)) / 2;
				const ny = (py + n(np.offset, h)) / 2;
				d += ` Q${px},${py} ${nx},${ny}`;
			} else {
				d += ` L${1 - rx},0`;
			}
		}
		d += ` Q1,0 1,${ry}`;

		for (let i = 0; i < rightPoints.length; i++) {
			const p = rightPoints[i];
			const px = 1 + n(p.offset, w);
			const py = n(p.pos, h);
			if (i < rightPoints.length - 1) {
				const np = rightPoints[i + 1];
				const nx = (px + (1 + n(np.offset, w))) / 2;
				const ny = (py + n(np.pos, h)) / 2;
				d += ` Q${px},${py} ${nx},${ny}`;
			} else {
				d += ` L1,${1 - ry}`;
			}
		}
		d += ` Q1,1 ${1 - rx},1`;

		for (let i = 0; i < bottomPoints.length; i++) {
			const p = bottomPoints[i];
			const px = 1 - n(p.pos, w);
			const py = 1 + n(p.offset, h);
			if (i < bottomPoints.length - 1) {
				const np = bottomPoints[i + 1];
				const nx = (px + (1 - n(np.pos, w))) / 2;
				const ny = (py + (1 + n(np.offset, h))) / 2;
				d += ` Q${px},${py} ${nx},${ny}`;
			} else {
				d += ` L${rx},1`;
			}
		}
		d += ` Q0,1 0,${1 - ry}`;

		for (let i = 0; i < leftPoints.length; i++) {
			const p = leftPoints[i];
			const py = 1 - n(p.pos, h);
			const px = n(-p.offset, w);
			if (i < leftPoints.length - 1) {
				const np = leftPoints[i + 1];
				const ny = (py + (1 - n(np.pos, h))) / 2;
				const nx = (px + n(-np.offset, w)) / 2;
				d += ` Q${px},${py} ${nx},${ny}`;
			} else {
				d += ` L0,${ry}`;
			}
		}
		d += ` Q0,0 ${rx},0 Z`;

		return d;
	}

	let path = $derived(buildPath());
</script>

<svg class="pointer-events-none" aria-hidden="true" style="position: absolute; width: 0; height: 0;">
	<defs>
		<clipPath id="elastic-clip" clipPathUnits="objectBoundingBox">
			<path d={path} />
		</clipPath>
	</defs>
</svg>
