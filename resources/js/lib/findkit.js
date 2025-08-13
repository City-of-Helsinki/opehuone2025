import { FindkitUI, html, css, } from "@findkit/ui";


const findkitUI = new FindkitUI({
    publicToken: 'pLZGwMPvn:eu-north-1',
    container: '.findkit-overlay-container',
    infiniteScroll: false,
    header: false,
    groups: [
        {
            title: "Sivut",
            previewSize: 3,
            params: {
                tagQuery: [["wp_post_type/page"]],
            },
        },
        {
            title: "Uutiset",
            previewSize: 3,
            params: {
                tagQuery: [["wp_post_type/post"]],
            },
        },
        {
            title: "Koulutukset",
            previewSize: 3,
            params: {
                tagQuery: [["wp_post_type/training"]],
            },
        },
    ],
    slots: {
        Group(props) {
            return html`
                <div>
                    <h2 class="findkit--group-title">${props.title}</h2>
                    <p class="findkit-total-results-count">${props.total} hakutulosta</p>
                    <${props.parts.Hits} />
                    ${props.total > 0 ? html`
                        <${props.parts.ShowAllLink} ...${props}>
                            <div class="findkit--link-text">
                                Katso kaikki
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="23" height="18" viewBox="0 0 23 18" fill="none">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M13.6666 0.332031L11.6666 2.33203L16.9999 7.66536H0.333252V10.332H16.9999L11.6666 15.6654L13.6666 17.6654L22.3333 8.9987L13.6666 0.332031Z" fill="black"/>
                            </svg>
                        </${props.parts.ShowAllLink}>
                    ` : 'Ei hakutuloksia'}
                </div>
            `;
        },
        Hit(props) {
            return html`
                <div>
                    <h2 class="findkit-result-header">
                        <a class="findkit-result-link" href=${props.hit.url}>${props.hit.title}</a>
                    </h2>
                    <${props.parts.Highlight} />
                </div>
            `;
        },
        Results(props) {
            return html`
                <${props.parts.BackLink} ...${props}>
                    <svg xmlns="http://www.w3.org/2000/svg" width="23" height="18" viewBox="0 0 23 18" fill="none">
                        <g transform="scale(-1, 1) translate(-23, 0)"><path fill-rule="evenodd" clip-rule="evenodd" d="M13.6666 0.332031L11.6666 2.33203L16.9999 7.66536H0.333252V10.332H16.9999L11.6666 15.6654L13.6666 17.6654L22.3333 8.9987L13.6666 0.332031Z" fill="black"/></g>
                    </svg>

                    <div class="findkit--link-text">
                        Takaisin
                    </div>
                </${props.parts.BackLink}>
                <h2 class="findkit--group-title">${props.title}</h2>
                <p class="findkit-total-results-count">${props.total} hakutulosta</p>
                <${props.parts.Hits} />
                <${props.parts.Footer} />
            `;
        },
    },
    css: css`
        :host {
            --findkit--brand-color: #1A1A1A;
            --findkit--background-color: #0000FF;
        }

        .findkit--message {
            display: none;
        }

        .findkit--modal {
            background-color: var(--findkit--background-color);
        }

        .findkit--hit {
            background-color: #FFFFFF;
            margin-bottom: 1.25rem;
            padding: 1rem 1.25rem 1.25rem 1.25rem;
        }

        .findkit--highlight {
            padding: 0;
        }

        .findkit--em {
            background-color: #EFEFF0;
            color: #000000;
        }

        .findkit--hit h2 {
            margin-top: 0;
            margin-bottom: 20px;
            font-size: 1rem;
            font-weight: 700;
            color: #1A1A1A;
        }

        .findkit--hit h2 > a {
            color: #1A1A1A;
            text-decoration: none;
        }

        .findkit--hit h2 > a:hover {
            text-decoration: underline;
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
        
        .findkit--back-link {
            border-bottom: none;
            margin-left: 0;
            margin-bottom: 2rem;
            padding-top: 2rem;
            padding-left: 0;
            
            .findkit--link-text {
                font-weight: 500;
                margin-right: 0;
                margin-left: 1rem;
            }
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
    `,
})

findkitUI.bindInput('#header-search-input');


const searchToggle = document.getElementById('header-search-toggle');
const mobilePanelToggle = document.getElementById('mobile-panel-toggle');
const searchInput = document.getElementById('header-search-input');
const clearBtn = document.getElementById('clear-search');
const closeSearchWindowButton = document.querySelector('.search-header__close-button');

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
})

/**
 * If we are on mobile and use the search, then open the mobile menu, we need to make sure main
 * content is visible, as it is hidden when the search page is opened. Otherwise main content will be
 * hidden after we close the mobile menu.
 */
mobilePanelToggle?.addEventListener('click', (e) => {
    const main = document.getElementById('main');
    main.style.display = 'block';
})

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
    const isExpanded = searchToggle.getAttribute('aria-expanded') === 'true';
    const main = document.getElementById('main');
    const menu = document.getElementById('main-menu-nav');
    const footerSvg = document.querySelector('footer .hds-koros' );
    const searchInput = document.getElementById('header-search-input')

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




export { findkitUI }
