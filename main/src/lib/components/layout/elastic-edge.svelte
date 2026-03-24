<script lang="ts">
	let { container }: { container: HTMLElement | null } = $props();

	const POINTS_PER_SIDE = 6;
	const VISCOSITY = 20;
	const MOUSE_DIST = 80;
	const DAMPING = 0.15;
	const BORDER_RADIUS = 12;

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
	let containerRect = { x: 0, y: 0, width: 0, height: 0 };

	function createSidePoints(length: number): EdgePoint[] {
		const pts: EdgePoint[] = [];
		const r = BORDER_RADIUS;
		for (let i = 0; i <= POINTS_PER_SIDE + 1; i++) {
			const t = i / (POINTS_PER_SIDE + 1);
			pts.push({ pos: r + t * (length - 2 * r), iPos: r + t * (length - 2 * r), offset: 0, vOffset: 0 });
		}
		return pts;
	}

	function initPoints() {
		if (!container) return;
		const rect = container.getBoundingClientRect();
		containerRect = { x: rect.x, y: rect.y, width: rect.width, height: rect.height };
		topPoints = createSidePoints(rect.width);
		rightPoints = createSidePoints(rect.height);
		bottomPoints = createSidePoints(rect.width);
		leftPoints = createSidePoints(rect.height);
	}

	$effect(() => {
		if (container) initPoints();
	});

	function moveSidePoints(pts: EdgePoint[], mouseAlongAxis: number, mouseCrossAxis: number, speed: number) {
		for (const p of pts) {
			p.vOffset += (0 - p.offset) / VISCOSITY;

			const dAlong = p.pos - mouseAlongAxis;
			const dCross = mouseCrossAxis;

			if (Math.abs(dAlong) < MOUSE_DIST && Math.abs(dCross) < MOUSE_DIST) {
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
		containerRect = { x: rect.x, y: rect.y, width: rect.width, height: rect.height };

		const relX = mouseX - rect.x;
		const relY = mouseY - rect.y;

		moveSidePoints(topPoints, relX, relY, mouseSpeedY);
		moveSidePoints(bottomPoints, rect.width - relX, rect.height - relY, -mouseSpeedY);
		moveSidePoints(rightPoints, relY, rect.width - relX, -mouseSpeedX);
		moveSidePoints(leftPoints, rect.height - relY, relX, mouseSpeedX);

		topPoints = [...topPoints];
		rightPoints = [...rightPoints];
		bottomPoints = [...bottomPoints];
		leftPoints = [...leftPoints];
	}

	$effect(() => {
		if (!container) return;

		const controller = new AbortController();

		const handleMouseMove = (e: MouseEvent) => {
			mouseX = e.clientX;
			mouseY = e.clientY;
		};

		window.addEventListener('mousemove', handleMouseMove, { signal: controller.signal });

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

	function buildPath(): string {
		const w = containerRect.width;
		const h = containerRect.height;
		if (!w || !h || !topPoints.length) return '';

		const r = BORDER_RADIUS;
		let d = `M${r},0`;

		for (let i = 0; i < topPoints.length; i++) {
			const p = topPoints[i];
			const py = p.offset;
			if (i < topPoints.length - 1) {
				const n = topPoints[i + 1];
				const nx = (p.pos + n.pos) / 2;
				const ny = (py + n.offset) / 2;
				d += ` Q${p.pos},${py} ${nx},${ny}`;
			} else {
				d += ` L${w - r},0`;
			}
		}
		d += ` Q${w},0 ${w},${r}`;

		for (let i = 0; i < rightPoints.length; i++) {
			const p = rightPoints[i];
			const px = w + p.offset;
			if (i < rightPoints.length - 1) {
				const n = rightPoints[i + 1];
				const ny = (p.pos + n.pos) / 2;
				const nx = (px + (w + n.offset)) / 2;
				d += ` Q${px},${p.pos} ${nx},${ny}`;
			} else {
				d += ` L${w},${h - r}`;
			}
		}
		d += ` Q${w},${h} ${w - r},${h}`;

		for (let i = 0; i < bottomPoints.length; i++) {
			const p = bottomPoints[i];
			const px = w - p.pos;
			const py = h + p.offset;
			if (i < bottomPoints.length - 1) {
				const n = bottomPoints[i + 1];
				const nx = (px + (w - n.pos)) / 2;
				const ny = (py + (h + n.offset)) / 2;
				d += ` Q${px},${py} ${nx},${ny}`;
			} else {
				d += ` L${r},${h}`;
			}
		}
		d += ` Q0,${h} 0,${h - r}`;

		for (let i = 0; i < leftPoints.length; i++) {
			const p = leftPoints[i];
			const py = h - p.pos;
			const px = -p.offset;
			if (i < leftPoints.length - 1) {
				const n = leftPoints[i + 1];
				const ny = (py + (h - n.pos)) / 2;
				const nx = (px + -n.offset) / 2;
				d += ` Q${px},${py} ${nx},${ny}`;
			} else {
				d += ` L0,${r}`;
			}
		}
		d += ` Q0,0 ${r},0 Z`;

		return d;
	}

	let path = $derived(buildPath());
</script>

<svg class="pointer-events-none absolute top-0 left-0 h-0 w-0" aria-hidden="true" style="position: absolute; width: 0; height: 0;">
	<defs>
		<clipPath id="elastic-clip" clipPathUnits="userSpaceOnUse">
			<path d={path} />
		</clipPath>
	</defs>
</svg>
