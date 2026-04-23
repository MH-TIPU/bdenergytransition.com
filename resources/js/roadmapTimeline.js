function clamp(value, min, max) {
    return Math.min(max, Math.max(min, value));
}

function getScrollProgressWithinSection(sectionEl) {
    const rect = sectionEl.getBoundingClientRect();
    const scrollY = window.scrollY || window.pageYOffset;
    const top = rect.top + scrollY;
    const height = rect.height;

    const start = top - window.innerHeight * 0.65;
    const end = top + height - window.innerHeight * 0.35;

    if (end <= start) {
        return 1;
    }

    return clamp((scrollY - start) / (end - start), 0, 1);
}

function isCoarsePointer() {
    return window.matchMedia?.('(pointer: coarse)')?.matches ?? false;
}

function parseMilestonesFromJsonScript(scriptId) {
    const el = document.getElementById(scriptId);
    if (!el) return [];

    try {
        const parsed = JSON.parse(el.textContent || '[]');
        if (!Array.isArray(parsed)) return [];
        return parsed.filter((m) => m && m.date && m.globalTitle);
    } catch {
        return [];
    }
}

function buildFlagSvg({ colorClass, pending }) {
    if (pending) {
        return `
            <svg viewBox="0 0 34 26" class="w-12 h-10" aria-hidden="true" focusable="false">
                <path d="M6 3h17.5c1 0 1.8.8 1.8 1.8v.3l-3.9 4.2 3.9 4.2v.3c0 1-.8 1.8-1.8 1.8H6c-1 0-1.8-.8-1.8-1.8V4.8C4.2 3.8 5 3 6 3Z" fill="#ffffff" stroke="#e2e8f0" stroke-width="1.4" stroke-linejoin="round" />
                <path d="M18.2 8.4 21.8 5.2" stroke="#e2e8f0" stroke-width="1" stroke-linecap="round" opacity="0.65" />
            </svg>
        `.trim();
    }

    return `
        <svg viewBox="0 0 34 26" class="w-12 h-10" aria-hidden="true" focusable="false">
            <path class="${colorClass}" d="M6 3h17.5c1 0 1.8.8 1.8 1.8v.3l-3.9 4.2 3.9 4.2v.3c0 1-.8 1.8-1.8 1.8H6c-1 0-1.8-.8-1.8-1.8V4.8C4.2 3.8 5 3 6 3Z" fill="currentColor" />
            <path d="M6 3h17.5c1 0 1.8.8 1.8 1.8v.3l-3.9 4.2 3.9 4.2v.3c0 1-.8 1.8-1.8 1.8H6c-1 0-1.8-.8-1.8-1.8V4.8C4.2 3.8 5 3 6 3Z" fill="none" stroke="#ffffff" stroke-width="1" opacity="0.65" />
            <path d="M18.2 8.4 21.8 5.2" stroke="#ffffff" stroke-width="1" stroke-linecap="round" opacity="0.55" />
        </svg>
    `.trim();
}

function catmullRomToBezierPath(points) {
    if (!points || points.length < 2) return '';

    const result = [];
    const p = points;

    result.push(`M${p[0].x},${p[0].y}`);

    for (let i = 0; i < p.length - 1; i += 1) {
        const p0 = p[i - 1] ?? p[i];
        const p1 = p[i];
        const p2 = p[i + 1];
        const p3 = p[i + 2] ?? p2;

        const c1x = p1.x + (p2.x - p0.x) / 6;
        const c1y = p1.y + (p2.y - p0.y) / 6;
        const c2x = p2.x - (p3.x - p1.x) / 6;
        const c2y = p2.y - (p3.y - p1.y) / 6;

        result.push(`C${c1x},${c1y} ${c2x},${c2y} ${p2.x},${p2.y}`);
    }

    return result.join(' ');
}

function buildDesktopSnakePoints({ totalMilestones, nodesPerRow }) {
    const vbW = 1000;
    const pad = 26;
    const xL = pad;
    const xR = vbW - pad;
    const dx = xR - xL;

    const rowGap = 150;
    const yStart = 140;
    const amp = 58;
    const rowCount = Math.max(1, Math.ceil(totalMilestones / nodesPerRow));

    const points = [];

    for (let row = 0; row < rowCount; row += 1) {
        const y = yStart + row * rowGap;
        const leftToRight = row % 2 === 0;

        const xs = [xL, xL + dx * 0.33, xL + dx * 0.66, xR];
        const xRow = leftToRight ? xs : xs.slice().reverse();
        const yRow = [
            y,
            y + (row % 2 === 0 ? -amp : amp),
            y + (row % 2 === 0 ? amp : -amp),
            y,
        ];

        for (let i = 0; i < xRow.length; i += 1) {
            const pt = { x: xRow[i], y: yRow[i] };
            const last = points[points.length - 1];
            if (!last || last.x !== pt.x || last.y !== pt.y) {
                points.push(pt);
            }
        }

        if (row < rowCount - 1) {
            const endX = leftToRight ? xR : xL;
            const yNext = yStart + (row + 1) * rowGap;
            const turnX = clamp(endX + (leftToRight ? -44 : 44), xL, xR);

            points.push({ x: endX, y: y + rowGap * 0.22 });
            points.push({ x: turnX, y: y + rowGap * 0.5 });
            points.push({ x: endX, y: yNext - rowGap * 0.22 });
            points.push({ x: endX, y: yNext });
        }
    }

    const vbH = yStart + (rowCount - 1) * rowGap + 150;

    return { points, viewBox: { w: vbW, h: vbH } };
}

function buildMobileVerticalSnakePoints({ totalMilestones }) {
    const vbW = 360;
    const padX = 56;
    const xMid = vbW / 2;
    const xL = padX;
    const xR = vbW - padX;
    const stepY = 125;
    const yStart = 62;
    const amp = 40;

    const points = [];
    const segmentCount = Math.max(2, Math.ceil(totalMilestones / 2) + 1);

    for (let i = 0; i < segmentCount; i += 1) {
        const y = yStart + i * stepY;
        const x = i % 2 === 0 ? xMid : i % 4 === 1 ? xR : xL;
        points.push({ x, y });
        const ctrlX = clamp(i % 2 === 0 ? x + amp : x - amp, xL, xR);
        points.push({ x: ctrlX, y: y + stepY * 0.45 });
    }

    const vbH = yStart + (segmentCount - 1) * stepY + 110;
    return { points, viewBox: { w: vbW, h: vbH } };
}

class TooltipCard {
    constructor(cardEl) {
        this.cardEl = cardEl;
        this.isOpen = false;
        this.activeMilestoneId = null;
    }

    close() {
        if (!this.cardEl) return;
        this.cardEl.classList.remove('is-open');
        this.isOpen = false;
        this.activeMilestoneId = null;
    }

    open({ milestone, anchorPoint, preferRight }) {
        if (!this.cardEl) return;

        if (milestone.isPending) {
            this.cardEl.innerHTML = `
                <div class="bg-white rounded-2xl border border-gray-200 shadow-lg p-5">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-xs font-bold text-gray-500 tracking-widest uppercase">Pending</p>
                            <h4 class="text-lg font-extrabold text-gray-900 leading-tight mt-1">More events will occers.</h4>
                        </div>
                        <button type="button" data-roadmap-close class="shrink-0 w-9 h-9 rounded-full border border-gray-200 bg-white text-gray-500 hover:text-gray-900 hover:border-gray-300">×</button>
                    </div>
                    <p class="text-sm text-gray-600 leading-relaxed mt-3">This spot is reserved for upcoming updates.</p>
                </div>
            `.trim();

            const containerRect = this.cardEl.parentElement?.getBoundingClientRect();
            if (containerRect) {
                const cardWidth = Math.min(560, containerRect.width - 32);
                const left = preferRight
                    ? clamp(anchorPoint.x + 18, 16, containerRect.width - cardWidth - 16)
                    : clamp(anchorPoint.x - cardWidth - 18, 16, containerRect.width - cardWidth - 16);

                this.cardEl.style.width = `${cardWidth}px`;
                const cardRect = this.cardEl.getBoundingClientRect();
                const maxTop = Math.max(12, containerRect.height - cardRect.height - 12);
                const top = clamp(anchorPoint.y - 40, 12, maxTop);
                this.cardEl.style.left = `${left}px`;
                this.cardEl.style.top = `${top}px`;
            }

            this.cardEl.classList.add('is-open');
            this.isOpen = true;
            this.activeMilestoneId = milestone.id;

            const closeBtn = this.cardEl.querySelector('[data-roadmap-close]');
            closeBtn?.addEventListener('click', () => this.close(), { once: true });
            return;
        }

        const globalTitleHtml = milestone.globalLink
            ? `<a href="${milestone.globalLink}" target="_blank" rel="noopener noreferrer" class="hover:text-purple-900 hover:underline">${milestone.globalTitle}</a>`
            : milestone.globalTitle;

        const impactTitleText = milestone.impactTitle || 'No specific impact.';
        const impactTitleHtml = milestone.impactLink
            ? `<a href="${milestone.impactLink}" target="_blank" rel="noopener noreferrer" class="hover:text-red-900 hover:underline">${impactTitleText}</a>`
            : impactTitleText;

        const globalExcerpt = milestone.globalExcerpt
            ? `<p class="text-sm text-gray-600 leading-relaxed mt-3">${milestone.globalExcerpt}</p>`
            : '';
        const impactExcerpt = milestone.impactExcerpt ? `<p class="text-sm text-gray-600 leading-relaxed mt-2">${milestone.impactExcerpt}</p>` : '';

        this.cardEl.innerHTML = `
            <div class="bg-white rounded-2xl border border-gray-200 shadow-lg p-5">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-xs font-bold text-gray-500 tracking-widest uppercase">${milestone.label ?? ''}</p>
                        <h4 class="text-xl md:text-2xl font-extrabold text-gray-900 leading-tight mt-2">${globalTitleHtml}</h4>
                        ${globalExcerpt}
                    </div>
                    <button type="button" data-roadmap-close class="shrink-0 w-9 h-9 rounded-full border border-gray-200 bg-white text-gray-500 hover:text-gray-900 hover:border-gray-300">×</button>
                </div>

                <div class="mt-4 grid grid-cols-1 gap-3">
                    <div class="bg-white rounded-xl border border-red-100 p-4">
                        <p class="text-[11px] font-extrabold text-red-700 uppercase tracking-wider">Bangladesh impact</p>
                        <p class="text-sm font-bold text-gray-900 leading-snug mt-2">${impactTitleHtml}</p>
                        ${impactExcerpt}
                    </div>
                </div>
            </div>
        `.trim();

        const containerRect = this.cardEl.parentElement?.getBoundingClientRect();
        if (containerRect) {
            const cardWidth = Math.min(560, containerRect.width - 32);
            const left = preferRight
                ? clamp(anchorPoint.x + 18, 16, containerRect.width - cardWidth - 16)
                : clamp(anchorPoint.x - cardWidth - 18, 16, containerRect.width - cardWidth - 16);

            this.cardEl.style.width = `${cardWidth}px`;
            const cardRect = this.cardEl.getBoundingClientRect();
            const maxTop = Math.max(12, containerRect.height - cardRect.height - 12);
            const top = clamp(anchorPoint.y - 40, 12, maxTop);

            this.cardEl.style.left = `${left}px`;
            this.cardEl.style.top = `${top}px`;
        }

        this.cardEl.classList.add('is-open');
        this.isOpen = true;
        this.activeMilestoneId = milestone.id;

        const closeBtn = this.cardEl.querySelector('[data-roadmap-close]');
        closeBtn?.addEventListener('click', () => this.close(), { once: true });
    }
}

function parseDateParts(dateStr) {
    if (!dateStr || typeof dateStr !== 'string') return null;
    const m = dateStr.match(/^(\d{4})-(\d{2})-(\d{2})$/);
    if (!m) return null;
    return {
        year: Number(m[1]),
        month: Number(m[2]),
        day: Number(m[3]),
        monthKey: `${m[1]}-${m[2]}`,
    };
}

function toUtcDate(dateStr) {
    const parts = parseDateParts(dateStr);
    if (!parts) return null;
    return new Date(Date.UTC(parts.year, parts.month - 1, parts.day));
}

function diffDays(aDateStr, bDateStr) {
    const a = toUtcDate(aDateStr);
    const b = toUtcDate(bDateStr);
    if (!a || !b) return 0;
    return Math.round((a.getTime() - b.getTime()) / 86400000);
}

function formatDateLong(dateStr) {
    const parts = parseDateParts(dateStr);
    if (!parts) return dateStr ?? '';

    // Avoid timezone issues by forcing local midnight
    const d = new Date(`${dateStr}T00:00:00`);
    return new Intl.DateTimeFormat(undefined, { month: 'short', day: '2-digit', year: 'numeric' }).format(d);
}

function buildRoadmapNodesFromEvents(events) {
    const sorted = events
        .map((e) => ({ ...e }))
        .filter((e) => e && e.date)
        .sort((a, b) => (a.date > b.date ? 1 : a.date < b.date ? -1 : 0));

    const groups = new Map();
    for (const ev of sorted) {
        const parts = parseDateParts(ev.date);
        if (!parts) continue;
        const monthKey = parts.monthKey;
        if (!groups.has(monthKey)) {
            groups.set(monthKey, []);
        }
        groups.get(monthKey).push({ ev, parts });
    }

    const monthKeys = Array.from(groups.keys()).sort();

    const flagNodes = monthKeys.map((monthKey) => {
        const firstEventInMonth = sorted.find((e) => e.date?.startsWith(monthKey));
        return {
            id: `month-${monthKey}`,
            kind: 'flag',
            interactive: false,
            monthKey,
            dateAnchor: `${monthKey}-01`,
            label: firstEventInMonth?.label ?? monthKey,
            globalTitle: firstEventInMonth?.label ?? monthKey,
        };
    });

    const bubbleNodes = [];
    for (const monthKey of monthKeys) {
        const items = groups.get(monthKey) ?? [];
        for (const { ev, parts } of items) {
            bubbleNodes.push({
                id: `event-${ev.id}`,
                kind: 'bubble',
                interactive: true,
                monthKey,
                dateAnchor: ev.date,
                dayLabel: String(parts.day),
                date: ev.date,
                label: formatDateLong(ev.date),
                globalTitle: ev.globalTitle,
                globalExcerpt: ev.globalExcerpt,
                globalLink: ev.globalLink,
                impactTitle: ev.impactTitle,
                impactExcerpt: ev.impactExcerpt,
                impactLink: ev.impactLink,
            });
        }
    }

    if (bubbleNodes.length === 0) {
        return [...flagNodes];
    }

    // Map node positions along the path based on actual day differences.
    const startT = 0.06;
    const endT = 0.96;

    const minDate = flagNodes[0]?.dateAnchor ?? bubbleNodes[0].dateAnchor;
    const maxDate = bubbleNodes.reduce(
        (acc, n) => (n.dateAnchor && (!acc || n.dateAnchor > acc) ? n.dateAnchor : acc),
        bubbleNodes[0].dateAnchor
    );
    const rangeDays = Math.max(1, diffDays(maxDate, minDate));

    const temporalNodes = [...flagNodes, ...bubbleNodes]
        .filter((n) => n.dateAnchor)
        .sort((a, b) => (a.dateAnchor > b.dateAnchor ? 1 : a.dateAnchor < b.dateAnchor ? -1 : 0) || (a.kind === 'flag' ? -1 : 1));

    // Compute raw t by ratio, then enforce a small minimum separation so nodes don't overlap.
    const minSep = 0.012;
    let lastT = -Infinity;
    for (const n of temporalNodes) {
        const daysFromStart = diffDays(n.dateAnchor, minDate);
        const ratio = clamp(daysFromStart / rangeDays, 0, 1);
        let t = startT + (endT - startT) * ratio;

        if (t <= lastT + minSep) {
            t = lastT + minSep;
        }

        n.t = clamp(t, 0, endT);
        lastT = n.t;
    }

    // Ensure each month flag appears before the first bubble in that month.
    for (const monthKey of monthKeys) {
        const flag = flagNodes.find((f) => f.monthKey === monthKey);
        const firstBubble = bubbleNodes.find((b) => b.monthKey === monthKey);
        if (!flag || !firstBubble) continue;
        if ((flag.t ?? 0) >= (firstBubble.t ?? 0)) {
            flag.t = clamp((firstBubble.t ?? 0) - minSep, 0, endT);
        }
    }

    // One trailing pending flag
    flagNodes.push({
        id: 'pending-1',
        kind: 'flag',
        interactive: false,
        isPending: true,
        monthKey: 'pending',
        label: 'More events will occers.',
        t: 1,
        globalTitle: 'More events will occers.',
    });

    return [...flagNodes, ...bubbleNodes].sort((a, b) => {
        const ta = a.t ?? 0;
        const tb = b.t ?? 0;
        if (ta !== tb) return ta - tb;
        if (a.id === 'pending-1') return 1;
        if (b.id === 'pending-1') return -1;
        if (a.kind !== b.kind) return a.kind === 'flag' ? -1 : 1;
        return String(a.id).localeCompare(String(b.id));
    });
}

class MilestoneNode {
    constructor({ node, index, nodesMountEl, onInteract }) {
        this.milestone = node;
        this.index = index;
        this.onInteract = onInteract;

        const isButton = node.interactive === true;
        this.el = document.createElement(isButton ? 'button' : 'div');
        if (isButton) {
            this.el.type = 'button';
        }
        this.el.className = 'roadmap-node';
        this.el.setAttribute('aria-label', node.globalTitle ?? node.label ?? 'Timeline item');

        const palette = [
            { color: 'text-purple-700' },
            { color: 'text-sky-600' },
            { color: 'text-fuchsia-600' },
            { color: 'text-red-600' },
            { color: 'text-emerald-600' },
            { color: 'text-amber-500' },
        ];

        if (node.kind === 'bubble') {
            this.el.classList.add('text-purple-700');
            if (node.globalTitle) {
                this.el.title = node.globalTitle;
            }
            const bubbleTitle = node.globalTitle ? ` title="${String(node.globalTitle).replace(/\"/g, '&quot;')}"` : '';
            this.el.innerHTML = `<span class="roadmap-bubble" aria-hidden="true"${bubbleTitle}>${node.dayLabel ?? ''}</span>`;
        } else {
            const style = palette[index % palette.length];
            const flagSvg = buildFlagSvg({ colorClass: style.color, pending: node.isPending === true });
            this.el.innerHTML = `
                <span class="roadmap-label">
                    <span class="inline-flex items-center justify-center text-xs font-extrabold text-gray-600 bg-white/90 px-2 py-1 rounded-md border border-gray-200 shadow-sm">${node.label ?? ''}</span>
                </span>
                <span class="roadmap-flag"><span class="block drop-shadow-sm">${flagSvg}</span></span>
                <span class="roadmap-stem"></span>
            `.trim();
        }

        nodesMountEl.appendChild(this.el);

        if (isButton) {
            this.el.addEventListener('click', (e) => {
                e.stopPropagation();
                this.onInteract({ type: 'click', milestone: this });
            });
        }

        this.visible = false;
        this.active = false;
        this.point = { x: 0, y: 0 };
    }

    setPosition({ x, y }) {
        this.point = { x, y };
        this.el.style.left = `${x}px`;
        this.el.style.top = `${y}px`;
    }

    setVisible(isVisible) {
        if (this.visible === isVisible) return;
        this.visible = isVisible;
        this.el.classList.toggle('is-visible', isVisible);
    }

    setActive(isActive) {
        if (this.active === isActive) return;
        this.active = isActive;
        this.el.classList.toggle('is-active', isActive);
    }
}

class RoadmapTimeline {
    constructor({ sectionEl, containerEl, nodesMountEl, cardEl, basePathEl, progressPathEl, nodes }) {
        this.sectionEl = sectionEl;
        this.containerEl = containerEl;
        this.nodesMountEl = nodesMountEl;
        this.basePathEl = basePathEl;
        this.progressPathEl = progressPathEl;

        this.tooltip = new TooltipCard(cardEl);
        this.nodeViews = [];
        this.nodes = nodes;
        this.lastProgress = -1;

        this.onScroll = this.onScroll.bind(this);
        this.onResize = this.onResize.bind(this);
        this.onDocumentClick = this.onDocumentClick.bind(this);

        this.init();
    }

    init() {
        this.buildNodes();
        this.layout();
        this.revealAll();
        this.onScroll();

        window.addEventListener('scroll', this.onScroll, { passive: true });
        window.addEventListener('resize', this.onResize);
        document.addEventListener('click', this.onDocumentClick);
    }

    buildNodes() {
        this.nodesMountEl.innerHTML = '';
        this.nodeViews = this.nodes.map(
            (node, index) =>
                new MilestoneNode({
                    node,
                    index,
                    nodesMountEl: this.nodesMountEl,
                    onInteract: (payload) => this.handleNodeInteract(payload),
                })
        );
    }

    revealAll() {
        this.nodeViews.forEach((n, idx) => {
            window.setTimeout(() => n.setVisible(true), 60 + idx * 24);
        });
    }

    setSvgPathForViewport() {
        const width = this.containerEl.clientWidth;
        const mobile = width < 768;
        const total = this.nodeViews.length;

        if (mobile) {
            const { points, viewBox } = buildMobileVerticalSnakePoints({ totalMilestones: total });
            const d = catmullRomToBezierPath(points);
            this.basePathEl.closest('svg')?.setAttribute('viewBox', `0 0 ${viewBox.w} ${viewBox.h}`);
            this.basePathEl.setAttribute('d', d);
            this.progressPathEl.setAttribute('d', d);
            this.containerEl.style.height = `${Math.round((viewBox.h / viewBox.w) * this.containerEl.clientWidth)}px`;
            return;
        }

        const minGap = 120;
        const nodesPerRow = clamp(Math.floor(width / minGap), 6, 8);
        const { points, viewBox } = buildDesktopSnakePoints({ totalMilestones: total, nodesPerRow });
        const d = catmullRomToBezierPath(points);
        this.basePathEl.closest('svg')?.setAttribute('viewBox', `0 0 ${viewBox.w} ${viewBox.h}`);
        this.basePathEl.setAttribute('d', d);
        this.progressPathEl.setAttribute('d', d);

        const desiredPx = Math.round((viewBox.h / viewBox.w) * this.containerEl.clientWidth);
        this.containerEl.style.height = `${clamp(desiredPx, 420, 1800)}px`;
    }

    layout() {
        this.setSvgPathForViewport();

        const svg = this.basePathEl.closest('svg');
        if (!svg) return;

        const svgRect = svg.getBoundingClientRect();
        const pathLength = this.basePathEl.getTotalLength();

        this.progressPathEl.style.strokeDasharray = `${pathLength}`;
        this.progressPathEl.style.strokeDashoffset = `${pathLength}`;

        const svgViewBox = svg.viewBox.baseVal;
        const scaleX = svgRect.width / svgViewBox.width;
        const scaleY = svgRect.height / svgViewBox.height;

        this.nodeViews.forEach((nodeView) => {
            const t = typeof nodeView.milestone.t === 'number' ? clamp(nodeView.milestone.t, 0, 1) : 0;
            const point = this.basePathEl.getPointAtLength(pathLength * t);

            const x = (point.x - svgViewBox.x) * scaleX;
            const y = (point.y - svgViewBox.y) * scaleY;

            if (nodeView.milestone.kind === 'flag') {
                nodeView.el.style.setProperty('--lift', this.containerEl.clientWidth < 768 ? '42px' : '54px');
            } else {
                nodeView.el.style.setProperty('--lift', '0px');
            }

            nodeView.setPosition({ x, y });
        });

        this.tooltip.close();
    }

    onResize() {
        this.layout();
        this.onScroll();
    }

    onScroll() {
        if (!this.sectionEl) return;
        const progress = getScrollProgressWithinSection(this.sectionEl);
        if (Math.abs(progress - this.lastProgress) < 0.001) return;
        this.lastProgress = progress;

        const pathLength = this.basePathEl.getTotalLength();
        this.progressPathEl.style.strokeDashoffset = `${pathLength * (1 - progress)}`;

        const bubbles = this.nodeViews.filter((n) => n.milestone.kind === 'bubble');
        if (bubbles.length === 0) return;

        let activeBubble = bubbles[0];
        for (const b of bubbles) {
            const t = typeof b.milestone.t === 'number' ? b.milestone.t : 0;
            if (progress >= t) {
                activeBubble = b;
            }
        }

        const activeMonthKey = activeBubble.milestone.monthKey;
        this.nodeViews.forEach((n) => {
            if (n.milestone.kind === 'bubble') {
                n.setActive(n === activeBubble);
            } else if (n.milestone.kind === 'flag') {
                n.setActive(activeMonthKey && n.milestone.monthKey === activeMonthKey);
            }
        });
    }

    handleNodeInteract({ type, milestone }) {
        if (type !== 'click' || !milestone) return;

        const targetNode = milestone.milestone;
        if (targetNode.kind !== 'bubble') {
            return;
        }

        const containerRect = this.containerEl.getBoundingClientRect();
        const anchorPoint = { x: milestone.point.x, y: milestone.point.y };
        const preferRight = anchorPoint.x < containerRect.width / 2;

        if (this.tooltip.isOpen && this.tooltip.activeMilestoneId === targetNode.id) {
            this.tooltip.close();
            return;
        }

        this.tooltip.open({ milestone: targetNode, anchorPoint, preferRight });

        // Active highlight follows click immediately
        const activeMonthKey = targetNode.monthKey;
        this.nodeViews.forEach((n) => {
            if (n.milestone.kind === 'bubble') {
                n.setActive(n === milestone);
            } else if (n.milestone.kind === 'flag') {
                n.setActive(activeMonthKey && n.milestone.monthKey === activeMonthKey);
            }
        });
    }

    onDocumentClick(e) {
        if (!this.tooltip.isOpen) return;
        const target = e.target;
        if (!(target instanceof HTMLElement)) return;
        if (this.tooltip.cardEl?.contains(target)) return;
        this.tooltip.close();
    }
}

export function initRoadmapTimeline() {
    const sectionEl = document.getElementById('timeline');
    if (!sectionEl) return;

    const containerEl = sectionEl.querySelector('[data-roadmap]');
    const nodesMountEl = sectionEl.querySelector('[data-roadmap-nodes]');
    const cardEl = sectionEl.querySelector('[data-roadmap-card]');
    const basePathEl = document.getElementById('roadmapPathBase');
    const progressPathEl = document.getElementById('roadmapPathProgress');

    if (!containerEl || !nodesMountEl || !cardEl || !basePathEl || !progressPathEl) {
        return;
    }

    const events = parseMilestonesFromJsonScript('timeline-milestones-json');
    if (events.length === 0) return;

    const nodes = buildRoadmapNodesFromEvents(events);
    if (nodes.length === 0) return;

    // eslint-disable-next-line no-new
    new RoadmapTimeline({
        sectionEl,
        containerEl,
        nodesMountEl,
        cardEl,
        basePathEl,
        progressPathEl,
        nodes,
    });
}
