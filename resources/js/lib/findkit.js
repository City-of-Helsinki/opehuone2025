import { FindkitUI, html, css } from '@findkit/ui';

const findkitUI = new FindkitUI({
	publicToken: 'pLZGwMPvn:eu-north-1',
	container: '.findkit-overlay-container',
	infiniteScroll: false,
	header: false,
	router: 'memory',
	groups: [
		{
			id: 'pages-tab',
			title: 'Sisältösivut',
			params: {
				tagQuery: [['wp_post_type/page']],
				size: 10,
			},
		},
		{
			id: 'posts-tab',
			title: 'Uutiset',
			params: {
				tagQuery: [['wp_post_type/post']],
				size: 10,
			},
		},
		{
			id: 'trainings-tab',
			title: 'Koulutukset',
			params: {
				tagQuery: [['wp_post_type/training']],
				size: 10,
			},
		},
	],
	slots: {
		Group(props) {
			return html`
				<div>
					<h2 class="findkit--group-title">${props.title}</h2>
					<p class="findkit-total-results-count">
						${props.total} hakutulosta
					</p>
					<${props.parts.Hits} />
					${props.total > 0
						? html`
                        <${props.parts.ShowAllLink} ...${props}>
                            <div class="findkit--link-text">
                                Katso kaikki
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="23" height="18" viewBox="0 0 23 18" fill="none">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M13.6666 0.332031L11.6666 2.33203L16.9999 7.66536H0.333252V10.332H16.9999L11.6666 15.6654L13.6666 17.6654L22.3333 8.9987L13.6666 0.332031Z" fill="black"/>
                            </svg>
                        </${props.parts.ShowAllLink}>
                    `
						: 'Ei hakutuloksia'}
				</div>
			`;
		},
		Hit(props) {
			const tags = props.hit.tags
				.filter((tag) => tag.startsWith('opehuone-search-label/'))
				.map((tag) => tag.replace('opehuone-search-label/', ''));
			return html`
				<div>
					${tags.length > 0 &&
					tags.map(
						(tag) =>
							html`<div className="findkit-result-tag">
								<span>${tag}</span>
							</div>`
					)}
					<h2 class="findkit-result-header">
						<a class="findkit-result-link" href=${props.hit.url}
							>${props.hit.title}</a
						>
					</h2>
					<${props.parts.Highlight} />
				</div>
			`;
		},
		Results(props) {
			return html`
				<h2 class="findkit--group-title">${props.title}</h2>
				<p
					class="findkit-total-results-count"
					data-results-count="${props.total}"
				>
					${props.total} hakutulosta
				</p>
				<${props.parts.Hits} />
				<${props.parts.Footer} />
			`;
		},
	},
	css: css`
		:host {
			--findkit--brand-color: #1a1a1a;
			--findkit--background-color: #0000ff;
		}

		.findkit--message {
			display: none;
		}

		.findkit--modal {
			background-color: var(--findkit--background-color);
		}

		.findkit--hit {
			background-color: #ffffff;
			margin-bottom: 1.25rem;
			padding: 1rem 1.25rem 1.25rem 1.25rem;
		}

		.findkit--highlight {
			padding: 0;
		}

		.findkit--em {
			background-color: #efeff0;
			color: #000000;
		}

		.findkit--hit h2 {
			margin-top: 0;
			margin-bottom: 0.65rem;
			color: #1a1a1a;
			font-size: 20px;
			font-style: normal;
			font-weight: 700;
			line-height: 150%;
		}

		.findkit--hit h2 > a {
			color: #1a1a1a;
			text-decoration: underline;
			text-decoration-thickness: 1px;
		}

		.findkit--hit h2 > a:hover {
			text-decoration-thickness: 2px;
		}

		.findkit--group-title {
			font-size: 2rem;
			font-weight: 400;
			margin-left: 0;
			margin-bottom: 0;
			border-bottom-style: none;
		}

		.findkit--single-group-link {
			border-bottom: none;
			padding-left: 0;
			margin-left: 0;
		}

		.findkit--link-text {
			margin-right: 1rem;
			font-weight: 500;
		}

		.findkit-total-results-count {
			margin-top: 0;
			font-weight: 400;
			font-size: 1.3rem;
			margin-bottom: 2.5rem;
		}

		.findkit--content {
			margin-bottom: 2rem;
			padding-top: 1rem;
		}

		.findkit--group-header-footer-spacing {
			//margin-top: 0;
			padding-top: 0;
		}

		.findkit--hover-bg {
			display: none;
		}

		.findkit--footer {
			padding-left: 0px;
		}

		.findkit--load-more-button {
			display: inline-flex;
			justify-content: center;
			align-items: center;
			gap: 10px;
			font-weight: 500;
			color: #fff;
			background-color: #000;
			text-align: center;
			text-decoration: none;
			white-space: normal;
			vertical-align: middle;
			user-select: none;
			border: 2px solid #000;
			padding: 10px 32px;
			font-size: 1rem;
			border-radius: 0;
			line-height: 1.5;
			min-height: 56px;
			cursor: pointer;
		}

		.findkit--load-more-button:hover {
			color: #000;
			background-color: transparent;
			text-decoration: none;
		}

		.findkit-result-tag {
			color: black;
			display: inline-block;
			background-color: #e6e6e6;
			font-size: 10px;
			line-height: 24px;
			border-radius: 24px;
			padding: 0px 12px;
			margin-bottom: 10px;
			font-weight: 500;
			margin-right: 12px;
		}
	`,
});

findkitUI.bindInput('#header-search-input');

// "Hidden" instance, which is only used to get the counts for each group. Counts are shown in the tabs buttons for each group.
const countsUI = new FindkitUI({
	instanceId: 'fdk-counts',
	publicToken: 'pLZGwMPvn:eu-north-1',
	container: '#findkit-counts-hidden',
	header: false,
	router: 'memory',
	groups: [
		{
			id: 'pages-tab',
			params: {
				tagQuery: [['wp_post_type/page']],
				size: 1,
			},
		},
		{
			id: 'posts-tab',
			params: {
				tagQuery: [['wp_post_type/post']],
				size: 1,
			},
		},
		{
			id: 'trainings-tab',
			params: {
				tagQuery: [['wp_post_type/training']],
				size: 1,
			},
		},
	],
	slots: {
		Group(props) {
			const btn = document.querySelector(
				`.fdk-tabs button[data-group="${props.id}"]`
			);
			const countEl = btn?.querySelector('.results-count');
			if (countEl) {
				const terms = countsUI.terms?.trim();

				if (!terms) {
					countEl.textContent = '0 kpl';
				} else {
					countEl.textContent = props.total + ' kpl';
				}
			}
			return null;
		},
	},
	css: css`
		#findkit-counts-hidden {
			position: absolute;
			width: 0;
			height: 0;
			overflow: hidden;
			opacity: 0;
			pointer-events: none;
		}
	`,
});

countsUI.bindInput('#header-search-input');

const searchToggle = document.getElementById('header-search-toggle');
const mobilePanelToggle = document.getElementById('mobile-panel-toggle');
const searchInput = document.getElementById('header-search-input');
const clearBtn = document.getElementById('clear-search');
const closeSearchWindowButton = document.querySelector(
	'.search-header__close-button'
);

/**
 * Hide the main content area when the search is expanded.
 * This makes sure we only see the header, search area and the footer when expanded.
 */
searchToggle?.addEventListener('click', (e) => {
	handleSearchWindowVisibility();
});

closeSearchWindowButton?.addEventListener('click', () => {
	searchToggle.setAttribute('aria-expanded', 'false');
	const searchWindow = document.getElementById('header-search');

	searchWindow?.classList.remove('active');
	searchWindow?.setAttribute('hidden', 'true');

	handleSearchWindowVisibility();
});

/**
 * If we are on mobile and use the search, then open the mobile menu, we need to make sure main
 * content is visible, as it is hidden when the search page is opened. Otherwise main content will be
 * hidden after we close the mobile menu.
 */
mobilePanelToggle?.addEventListener('click', (e) => {
	const main = document.getElementById('main');
	main.style.display = 'block';
});

/**
 * Clear the Findkit search input on button click
 */
clearBtn?.addEventListener('click', () => {
	if (!searchInput) {
		return;
	}

	searchInput.value = '';
	searchInput.focus();

	// Trigger Findkit to update (empty query)
	const event = new Event('input', { bubbles: true });
	searchInput.dispatchEvent(event);
});

function handleSearchWindowVisibility() {
	document.body.style.position = 'initial'; // body is fixed by default when opened and it causes problems on mobile

	const isExpanded = searchToggle.getAttribute('aria-expanded') === 'true';
	const main = document.getElementById('main');
	const menu = document.getElementById('main-menu-nav');
	const footerSvg = document.querySelector('footer .hds-koros');
	const searchInput = document.getElementById('header-search-input');

	if (isExpanded) {
		searchInput?.focus(); // Focus the input element when the search window is opened
		menu.style.display = 'none';
		main.style.display = 'none';
		footerSvg.style.backgroundColor = '#EFEFF0';
	} else {
		menu.style.display = 'block';
		main.style.display = 'block';
		footerSvg.style.backgroundColor = '#FFFFFF';
	}
}

/**
 * Search results tabs functionality.
 */
const tabsButtons = document.querySelectorAll('.fdk-tabs button');

function setActiveTab(group) {
	tabsButtons.forEach((b) => {
		const isActive = b.dataset.group === group;
		b.classList.toggle('active', isActive);
		b.setAttribute('aria-selected', isActive ? 'true' : 'false');
		b.setAttribute('tabindex', isActive ? '0' : '-1');
	});
}

tabsButtons.forEach((btn, index) => {
	btn.addEventListener('click', () => {
		findkitUI.activateGroup(btn.dataset.group);
		setActiveTab(btn.dataset.group);
	});

	btn.addEventListener('keydown', (e) => {
		let newIndex = null;
		if (e.key === 'ArrowRight') newIndex = (index + 1) % tabsButtons.length;
		if (e.key === 'ArrowLeft')
			newIndex = (index - 1 + tabsButtons.length) % tabsButtons.length;

		if (newIndex !== null) {
			e.preventDefault();
			const newBtn = tabsButtons[newIndex];
			newBtn.focus();
			findkitUI.activateGroup(newBtn.dataset.group);
			setActiveTab(newBtn.dataset.group);
		}
	});
});

/**
 * "Activate" first tab (pages) as default
 */
findkitUI.on('open', () => {
	findkitUI.activateGroup('pages-tab');
});

export { findkitUI };
