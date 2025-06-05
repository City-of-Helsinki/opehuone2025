/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./resources/js/lib/loadMoreHelpers.js":
/*!*********************************************!*\
  !*** ./resources/js/lib/loadMoreHelpers.js ***!
  \*********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   loadMorePosts: () => (/* binding */ loadMorePosts),
/* harmony export */   setLoadmoreButtonAttributes: () => (/* binding */ setLoadmoreButtonAttributes)
/* harmony export */ });
/* harmony import */ var _translations__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./translations */ "./resources/js/lib/translations.js");

const T = (0,_translations__WEBPACK_IMPORTED_MODULE_0__.getTranslations)('opehuone-variables');
const loadMoreButton = document.querySelector('.posts-archive__load-more-btn');

/**
 * Convert array to comma separated string
 *
 * @param {Array} array - The array to be converted.
 * @returns {string} - Comma-separated string or an empty string if input is not an array.
 */
const convertArrayToString = array => {
  return Array.isArray(array) ? array.join(',') : '';
};
const setLoadMoreButtonOffSet = currentOffSet => {
  const totalPosts = parseInt(loadMoreButton.getAttribute('data-total-posts'));
  const newOffset = 15 + currentOffSet;
  loadMoreButton.setAttribute('data-posts-offset', newOffset);
  if (newOffset < totalPosts) {
    loadMoreButton.classList.remove('is-disabled');
  }
};
const setLoadmoreButtonAttributes = (totalPosts, cornerlabels, categories, postTags) => {
  loadMoreButton.classList.remove('is-disabled');
  loadMoreButton.setAttribute('data-total-posts', totalPosts);
  loadMoreButton.setAttribute('data-posts-offset', 15);
  loadMoreButton.setAttribute('data-cornerlabels', convertArrayToString(cornerlabels));
  loadMoreButton.setAttribute('data-categories', convertArrayToString(categories));
  loadMoreButton.setAttribute('data-post-tags', convertArrayToString(postTags));
  if (totalPosts <= 15) {
    loadMoreButton.classList.add('is-disabled');
  }
};
const loadMorePosts = (ajaxAction, container) => {
  loadMoreButton.addEventListener('click', event => {
    event.preventDefault();
    loadMoreButton.classList.add('is-disabled');
    const currentOffSet = parseInt(loadMoreButton.getAttribute('data-posts-offset'));
    const cornerlabels = loadMoreButton.getAttribute('data-cornerlabels');
    const categories = loadMoreButton.getAttribute('data-categories');
    const postTags = loadMoreButton.getAttribute('data-post-tags');
    fetch(T.ajaxUrl, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      body: new URLSearchParams({
        action: ajaxAction,
        cornerLabels: cornerlabels,
        categories: categories,
        postTags: postTags,
        userId: T.userId,
        offset: currentOffSet
      })
    }).then(response => {
      if (!response.ok) {
        throw new Error(`HTTP error! Status: ${response.status}`);
      }
      return response.json();
    }) // Assuming the response is JSON
    .then(response => {
      if (container) {
        container.insertAdjacentHTML('beforeend', response.data.output);
      }

      // Set load more button properties
      setLoadMoreButtonOffSet(currentOffSet);
    }).catch(error => console.error('AJAX Error:', error));
  });
};

/***/ }),

/***/ "./resources/js/lib/postsArchiveFiltering.js":
/*!***************************************************!*\
  !*** ./resources/js/lib/postsArchiveFiltering.js ***!
  \***************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   postsArchiveFiltering: () => (/* binding */ postsArchiveFiltering)
/* harmony export */ });
/* harmony import */ var _translations__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./translations */ "./resources/js/lib/translations.js");
/* harmony import */ var _loadMoreHelpers__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./loadMoreHelpers */ "./resources/js/lib/loadMoreHelpers.js");


const T = (0,_translations__WEBPACK_IMPORTED_MODULE_0__.getTranslations)('opehuone-variables');
const numberOfPostsSpan = document.querySelector('#posts-archive-number-of-posts');
const postsContainer = document.querySelector('#posts-archive-results');
const getCheckedValues = (form, name) => {
  // need to escape the square brackets with double backslashes (\\) in the selector string
  const escapedName = name.replace(/([\[\]])/g, '\\$1');
  const checkboxes = form.querySelectorAll(`input[type="checkbox"][name="${escapedName}"]:checked`);
  return Array.from(checkboxes).map(input => input.value);
};
const doFiltering = (event, form) => {
  event.preventDefault(); // Prevent the default form submission

  const cornerlabels = getCheckedValues(form, 'cornerlabels[]');
  const categories = getCheckedValues(form, 'category[]');
  const postTags = getCheckedValues(form, 'post_tag[]');
  fetch(T.ajaxUrl, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded'
    },
    body: new URLSearchParams({
      action: `update_posts_archive_results`,
      cornerLabels: cornerlabels,
      categories: categories,
      postTags: postTags,
      userId: T.userId
    })
  }).then(response => {
    if (!response.ok) {
      throw new Error(`HTTP error! Status: ${response.status}`);
    }
    return response.json();
  }) // Assuming the response is JSON
  .then(response => {
    if (postsContainer) {
      postsContainer.innerHTML = response.data.output;
    }
    numberOfPostsSpan.innerHTML = response.data.totalPosts;

    // Set load more button properties
    (0,_loadMoreHelpers__WEBPACK_IMPORTED_MODULE_1__.setLoadmoreButtonAttributes)(response.data.totalPosts, cornerlabels, categories, postTags);
  }).catch(error => console.error('AJAX Error:', error));
};
const toggleDropdown = () => {
  const filterButtons = document.querySelectorAll('.checkbox-filter__filter-btn');
  filterButtons.forEach(button => {
    const originalLabel = button.getAttribute('aria-label') || 'Näytä valinnat'; // Fallback label

    button.addEventListener('click', event => {
      event.preventDefault();
      const isExpanded = button.getAttribute('aria-expanded') === 'true';

      // Toggle aria-expanded
      button.setAttribute('aria-expanded', (!isExpanded).toString());

      // Toggle aria-label
      button.setAttribute('aria-label', isExpanded ? originalLabel : 'Piilota valinnat');
    });

    // Ensure that pressing "Enter" also toggles the dropdown
    button.addEventListener('keydown', event => {
      if (event.key === 'Enter' || event.key === ' ') {
        event.preventDefault(); // Prevent scrolling or default button behavior
        button.click(); // Simulate the click to toggle dropdown
      }
    });
  });

  // Enable checkboxes to toggle with Enter and Space keys
  const checkboxes = document.querySelectorAll('.checkbox-filter__checkbox-input');
  checkboxes.forEach(checkbox => {
    checkbox.addEventListener('keydown', event => {
      if (event.key === 'Enter' || event.key === ' ') {
        event.preventDefault(); // Prevent triggering other elements like buttons
        checkbox.checked = !checkbox.checked; // Toggle checkbox state manually
        checkbox.dispatchEvent(new Event('change')); // Trigger change event if needed
      }
    });
  });
};
const initializeResetButtons = () => {
  const resetButtons = document.querySelectorAll('.checkbox-filter__checkboxes-reset-btn');
  resetButtons.forEach(button => {
    button.addEventListener('click', event => {
      event.preventDefault();
      // Find the closest filter wrapper to scope the reset action
      const filterWrapper = button.closest('.posts-archive__select-filter-wrapper');
      if (filterWrapper) {
        // Select all checked checkboxes within this filter group
        const checkboxes = filterWrapper.querySelectorAll('.checkbox-filter__checkbox-input:checked');
        checkboxes.forEach(checkbox => {
          checkbox.checked = false;
          checkbox.dispatchEvent(new Event('change')); // Dispatch change event if needed
        });
      }
    });
  });
};
const postsArchiveFiltering = () => {
  const form = document.querySelector('.posts-archive-filtering');
  if (form) {
    form.addEventListener('submit', event => doFiltering(event, form));
  }

  // toggle dropdowns with button
  toggleDropdown();

  // reset buttons
  initializeResetButtons();

  // Load more
  (0,_loadMoreHelpers__WEBPACK_IMPORTED_MODULE_1__.loadMorePosts)('load_more_posts_archive_results', postsContainer);
};

/***/ }),

/***/ "./resources/js/lib/postsFiltering.js":
/*!********************************************!*\
  !*** ./resources/js/lib/postsFiltering.js ***!
  \********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   postsFiltering: () => (/* binding */ postsFiltering)
/* harmony export */ });
/* harmony import */ var _translations__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./translations */ "./resources/js/lib/translations.js");

const T = (0,_translations__WEBPACK_IMPORTED_MODULE_0__.getTranslations)('opehuone-variables');

/**
 * Look for changes in form .front-page-posts-filter__posts-form
 * ==> so basically when any of the checkboxes change, output cornerlabels[] to console log
 */
const detectCheckboxChange = form => {
  if (!form) return;
  const toTarget = form.getAttribute('data-target');
  form.addEventListener('change', event => {
    if (event.target.classList.contains('front-page-posts-filter__checkbox-input')) {
      const checkedValues = Array.from(form.querySelectorAll('.front-page-posts-filter__checkbox-input:checked')).map(input => input.value);
      fetch(T.ajaxUrl, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams({
          action: `update_front_page_${toTarget}`,
          cornerLabels: checkedValues,
          userId: T.userId
        })
      }).then(response => {
        if (!response.ok) {
          throw new Error(`HTTP error! Status: ${response.status}`);
        }
        return response.json();
      }) // Assuming the response is JSON
      .then(response => {
        const targetClass = toTarget === 'posts' ? '.b-posts-row' : '.b-training-row';
        const postsContainer = document.querySelector(targetClass);
        if (postsContainer) {
          postsContainer.innerHTML = response.data.output;
        }
      }).catch(error => console.error('AJAX Error:', error));
    }
  });
};
const postsFiltering = () => {
  // Checkbox changes in front page
  detectCheckboxChange(document.querySelector('#front-page-filter-posts'));
  detectCheckboxChange(document.querySelector('#front-page-filter-training'));
};

/***/ }),

/***/ "./resources/js/lib/profileOpener.js":
/*!*******************************************!*\
  !*** ./resources/js/lib/profileOpener.js ***!
  \*******************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   profileOpener: () => (/* binding */ profileOpener)
/* harmony export */ });
const profileOpenerButton = document.querySelector('.profile-opener');
const profileOpenerDropdown = document.querySelector('.profile-opener-dropdown');
/**
 * Function to handle toggling the dropdown, when pressin profileOpenerButton
 * When button is clicked, look for parent class .profile-opener-wrapper--dropdown-open and toggle class .profile-opener-wrapper--dropdown-open for it
 *
 * Also change button aria-expanded to true, when dropdown is open, false when closed
 *
 * Also change aria-label for button to "Sulje profiilivalinnat", when aria-expanded is true
 */
const profileOpener = () => {
  if (!profileOpenerButton || !profileOpenerDropdown) return;
  profileOpenerButton.addEventListener('click', () => {
    const wrapper = profileOpenerButton.closest('.profile-opener-wrapper');
    if (wrapper) {
      const isOpen = wrapper.classList.toggle('profile-opener-wrapper--dropdown-open');
      profileOpenerButton.setAttribute('aria-expanded', isOpen);
      profileOpenerButton.setAttribute('aria-label', isOpen ? 'Sulje profiilivalinnat' : 'Avaa profiilivalinnat');
    }
  });
};

/***/ }),

/***/ "./resources/js/lib/scrollTo.js":
/*!**************************************!*\
  !*** ./resources/js/lib/scrollTo.js ***!
  \**************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   hashTagFunctions: () => (/* binding */ hashTagFunctions)
/* harmony export */ });
/* eslint-disable no-console,no-unused-vars */

const scrollTo = selector => {
  const mainNav = document.querySelector('.main-nav');
  const mainNavHeight = mainNav ? mainNav.offsetHeight : 0;
  const element = typeof selector === 'string' ? document.querySelector(selector) : selector;
  if (element) {
    window.scrollTo({
      top: element.offsetTop - mainNavHeight,
      behavior: 'smooth'
    });
  }
};
const scrollingLink = () => {
  const contentLinks = document.querySelectorAll('.wp-block-post-content a');
  contentLinks.forEach(link => {
    link.addEventListener('click', e => {
      const href = link.getAttribute('href');
      if (href && href.indexOf('#') !== -1) {
        e.preventDefault();
        // There is a hash
        const hash = '#' + href.split('#')[1];
        const targetElement = document.querySelector(hash);
        if (targetElement) {
          scrollTo(targetElement);
        }
      }
    });
  });
};
const detectHashInUrl = () => {
  const hash = window.location.hash;
  if (hash) {
    const targetElement = document.querySelector(hash);
    if (targetElement) {
      scrollTo(targetElement);
    }
  }
};
const hashTagFunctions = () => {
  detectHashInUrl();
  scrollingLink();
};

/***/ }),

/***/ "./resources/js/lib/serviceFailure.js":
/*!********************************************!*\
  !*** ./resources/js/lib/serviceFailure.js ***!
  \********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   serviceFailure: () => (/* binding */ serviceFailure)
/* harmony export */ });
/**
 * Function to toggle read more div visibility
 *
 * Looks for click event for button .service-failure__toggler
 * When clicked, look for parent .service-failure and toggle class .service-failure--read-more-open for that element
 *
 */
const toggleReadMore = () => {
  document.addEventListener('click', event => {
    const toggler = event.target.closest('.service-failure__toggler');
    if (!toggler) return;
    const serviceFailureElement = toggler.closest('.service-failure');
    if (serviceFailureElement) {
      serviceFailureElement.classList.toggle('service-failure--read-more-open');
    }
  });
};
const serviceFailure = () => {
  toggleReadMore();
};

/***/ }),

/***/ "./resources/js/lib/sideLinksList.js":
/*!*******************************************!*\
  !*** ./resources/js/lib/sideLinksList.js ***!
  \*******************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   sideLinksList: () => (/* binding */ sideLinksList)
/* harmony export */ });
/* harmony import */ var _translations__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./translations */ "./resources/js/lib/translations.js");

const ownLinkItem = (url, urlName) => {
  const li = document.createElement('li');
  li.classList.add('side-links-list__item');
  li.innerHTML = `
        <a href="${url}"
           class="side-links-list__link"
           target="_blank">
            ${urlName}
        </a>
        <button class="side-links-list__remove-btn side-links-list__remove-btn--custom"
                aria-label="Poista tämä linkki"
                data-custom-link-name="${urlName}"
                data-custom-link-url="${url}">
            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <g fill="none" fill-rule="evenodd">
                <rect width="24" height="24"></rect>
                <path fill="currentColor" d="M12,3 C7.02943725,3 3,7.02943725 3,12 C3,16.9705627 7.02943725,21 12,21 C16.9705627,21 21,16.9705627 21,12 C21,7.02943725 16.9705627,3 12,3 Z M15,7.5 L16.5,9 L13.5,12 L16.5,15 L15,16.5 L12,13.5 L9,16.5 L7.5,15 L10.5,12 L7.5,9 L9,7.5 L12,10.5 L15,7.5 Z"></path>
              </g>
            </svg>
        </button>
    `;
  return li; // Return a DOM element, not a string
};
const mainModifyButton = document.querySelector('.side-links-list__edit-link');
const sideLinksBox = document.querySelector('.side-links-list-box');
const modifyButtonText = mainModifyButton?.querySelector('span');
const resetButtonStage1 = document.querySelector('.side-links-list__reset-btn');
const resetButtonStage2 = document.querySelector('.side-links-list__reset-btn--final');
const submitBtn = document.querySelector('.own-links__submit-btn');
const urlNameInput = document.querySelector('#own-link-name');
const urlInput = document.querySelector('#own-link-url');
const notificationsWrapper = document.querySelector('.own-links__add-new-form-notifications');
const addNewForm = document.querySelector('#own-links__add-new-form');
const customList = document.querySelector('.side-links-list');
const T = (0,_translations__WEBPACK_IMPORTED_MODULE_0__.getTranslations)('opehuone-variables');
const isValidUrl = string => {
  let url;
  try {
    url = new URL(string);
  } catch (_) {
    return false;
  }
  return url.protocol === 'http:' || url.protocol === 'https:';
};
const addNewCustomLink = () => {
  if (!addNewForm) return;
  addNewForm.addEventListener('submit', event => {
    event.preventDefault(); // Prevent the page from refreshing
    let isValidForm = true;
    let urlName = urlNameInput.value.trim();
    const url = urlInput.value.trim();
    if (!urlName) {
      isValidForm = false;
    }
    if (!isValidUrl(url)) {
      isValidForm = false;
    }
    if (urlName.length > 50) {
      urlName = urlName.slice(0, 50);
    }
    notificationsWrapper.style.display = 'block';
    if (isValidForm) {
      notificationsWrapper.textContent = 'Uutta linkkiä lisätään...';
      submitBtn.classList.add('is-disabled');
      fetch(T.ajaxUrl, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams({
          action: 'add_new_own_link',
          userId: T.userId,
          urlName,
          url,
          nonce: T.opehuoneNonce
        })
      }).then(response => {
        if (!response.ok) {
          throw new Error(`HTTP error! Status: ${response.status}`);
        }
        return response.json();
      }) // Assuming the response is JSON
      .then(() => {
        submitBtn.classList.remove('is-disabled');
        urlNameInput.value = '';
        urlInput.value = '';
        customList.appendChild(ownLinkItem(url, urlName));
        setTimeout(() => {
          notificationsWrapper.innerHTML = 'Linkki lisätty.';
          setTimeout(() => {
            notificationsWrapper.style.display = 'none';
          }, 300);
        }, 100);
      }).catch(error => console.error('AJAX Error:', error));
    } else {
      notificationsWrapper.textContent = 'Linkin lisääminen ei onnistunut. Annoithan linkille nimen ja osoitteen. Huomaathan, että linkin pitää alkaa joko http:// tai https://.';
    }
  });
};
const toggleResetStage2 = () => {
  if (!resetButtonStage1) return;
  resetButtonStage1.addEventListener('click', () => {
    resetButtonStage2.classList.remove('side-links-list__reset-btn--final--hidden');
  });
};
const toggleModifyVisibility = () => {
  if (!mainModifyButton || !sideLinksBox || !modifyButtonText) return;
  const originalText = modifyButtonText.textContent;
  const toggleText = 'Poistu muokkaustilasta';
  mainModifyButton.addEventListener('click', () => {
    sideLinksBox.classList.toggle('side-links-list-box--modification-ongoing');

    // Toggle the button text
    modifyButtonText.textContent = modifyButtonText.textContent === originalText ? toggleText : originalText;
  });
};
const customLinkRemoval = () => {
  document.addEventListener('click', e => {
    const target = e.target.closest('.side-links-list__remove-btn--custom');
    if (!target) return;
    e.preventDefault();
    const url = target.getAttribute('data-custom-link-url');
    const urlName = target.getAttribute('data-custom-link-name');

    // Remove the closest `.front-side__links-list-item`
    const listItem = target.closest('.side-links-list__item');
    if (listItem) {
      listItem.remove();
    }

    // Send AJAX request using fetch()
    fetch(T.ajaxUrl, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      body: new URLSearchParams({
        action: 'remove_custom_link',
        userId: T.userId,
        url,
        urlName,
        nonce: T.opehuoneNonce
      })
    }).then(response => {
      if (!response.ok) {
        throw new Error(`HTTP error! Status: ${response.status}`);
      }
      return response.json();
    }) // Assuming the response is JSON
    .then(() => {
      // eslint-disable-next-line no-alert
      alert('Linkki poistettu.');
    }).catch(error => console.error('AJAX Error:', error));
  });
};
const defaultLinkRemoval = () => {
  document.addEventListener('click', e => {
    const target = e.target.closest('.side-links-list__remove-btn--default');
    if (!target) return;
    e.preventDefault();
    const url = target.getAttribute('data-link-url');

    // Remove the closest `.front-side__links-list-item`
    const listItem = target.closest('.side-links-list__item');
    if (listItem) {
      listItem.remove();
    }

    // Send AJAX request using fetch()
    fetch(T.ajaxUrl, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      body: new URLSearchParams({
        action: 'remove_default_link',
        userId: T.userId,
        url,
        nonce: T.opehuoneNonce
      })
    }).then(response => {
      if (!response.ok) {
        throw new Error(`HTTP error! Status: ${response.status}`);
      }
      return response.json();
    }) // Assuming the response is JSON
    .then(() => {
      // eslint-disable-next-line no-alert
      alert('Linkki poistettu.');
    }).catch(error => console.error('AJAX Error:', error));
  });
};
const resetAllLinks = () => {
  if (!resetButtonStage2) return;
  resetButtonStage2.addEventListener('click', e => {
    e.preventDefault();
    fetch(T.ajaxUrl, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      body: new URLSearchParams({
        action: 'reset_own_links',
        user_id: T.userId,
        nonce: T.opehuoneNonce
      })
    }).then(response => {
      if (!response.ok) {
        throw new Error(`HTTP error! Status: ${response.status}`);
      }
      return response.json();
    }) // Assuming the response is JSON
    .then(() => {
      location.reload(); // Reload the page after success
    }).catch(error => console.error('AJAX Error:', error));
  });
};
const sideLinksList = () => {
  toggleModifyVisibility();
  toggleResetStage2();
  addNewCustomLink();
  customLinkRemoval();
  defaultLinkRemoval();
  resetAllLinks();
};

/***/ }),

/***/ "./resources/js/lib/sidemenuToggler.js":
/*!*********************************************!*\
  !*** ./resources/js/lib/sidemenuToggler.js ***!
  \*********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   sidemenuToggler: () => (/* binding */ sidemenuToggler)
/* harmony export */ });
/* harmony import */ var _toggleStates__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./toggleStates */ "./resources/js/lib/toggleStates.js");


/**
 * Helper to set sub-menu-opened class to all ancestors
 */
const setCurrentsOpen = () => {
  const ancestors = document.querySelectorAll('#sidebar-nav .sidemenu-current-page-ancestor, #sidebar-nav .sidemenu-current-page');
  ancestors.forEach(el => {
    el.classList.add('sidemenu-page-item--opened');
    const button = el.querySelector('button[data-page-nav-toggle="sub-menu"]');
    if (button) {
      (0,_toggleStates__WEBPACK_IMPORTED_MODULE_0__.setAriaExpanded)(button);
    }
  });
};
const sidemenuToggler = () => {
  setCurrentsOpen();
  const buttons = document.querySelectorAll('.sidemenu-toggle');
  buttons.forEach(button => {
    button.addEventListener('click', e => {
      e.preventDefault();
      const target = e.currentTarget;
      const closestLi = target.closest('li.sidemenu-page-item');
      if (closestLi) {
        closestLi.classList.toggle('sidemenu-page-item--opened');
      }
      (0,_toggleStates__WEBPACK_IMPORTED_MODULE_0__.setAriaExpanded)(target);
    });
  });
};

/***/ }),

/***/ "./resources/js/lib/toggleStates.js":
/*!******************************************!*\
  !*** ./resources/js/lib/toggleStates.js ***!
  \******************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   setAriaExpanded: () => (/* binding */ setAriaExpanded),
/* harmony export */   toggleAria: () => (/* binding */ toggleAria),
/* harmony export */   toggleTabIndex: () => (/* binding */ toggleTabIndex)
/* harmony export */ });
const toggleAria = (el, ariaLabel) => {
  const currentAttr = el.getAttribute(ariaLabel);
  el.setAttribute(ariaLabel, currentAttr === 'true' ? 'false' : 'true');
};
const toggleTabIndex = el => {
  const currentAttr = el.getAttribute('tabindex');
  el.setAttribute('tabindex', currentAttr === '-1' ? '0' : '-1');
};
const setAriaExpanded = element => {
  const currentAttr = element.getAttribute('aria-expanded');
  const newAttr = currentAttr === 'true' ? 'false' : 'true';
  element.setAttribute('aria-expanded', newAttr);
};

/***/ }),

/***/ "./resources/js/lib/trainingFiltering.js":
/*!***********************************************!*\
  !*** ./resources/js/lib/trainingFiltering.js ***!
  \***********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   trainingFiltering: () => (/* binding */ trainingFiltering)
/* harmony export */ });
/* harmony import */ var _translations__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./translations */ "./resources/js/lib/translations.js");

const T = (0,_translations__WEBPACK_IMPORTED_MODULE_0__.getTranslations)('opehuone-variables');
const numberOfPostsSpan = document.querySelector('#training-archive-number-of-posts');
const doFiltering = event => {
  if (event) {
    event.preventDefault(); // Prevent the default form submission
  }
  const cornerLabels = document.querySelector('#training-archive-cornerlabels').value;
  const trainingTheme = document.querySelector('#training-archive-training_theme').value;
  fetch(T.ajaxUrl, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded'
    },
    body: new URLSearchParams({
      action: `update_training_archive_results`,
      cornerLabel: cornerLabels,
      trainingTheme: trainingTheme
    })
  }).then(response => {
    if (!response.ok) {
      throw new Error(`HTTP error! Status: ${response.status}`);
    }
    return response.json();
  }) // Assuming the response is JSON
  .then(response => {
    const postsContainer = document.querySelector('#training-archive-results');
    if (postsContainer) {
      postsContainer.innerHTML = response.data.output;
    }
    numberOfPostsSpan.innerHTML = response.data.totalPosts;
  }).catch(error => console.error('AJAX Error:', error));
};

/**
 * This function fetches the query parameters from URL and checks if it matches
 * from the filter key array. If they match, filter the results and scroll into view.
 * @param form
 */
const triggerFormUpdateOnPageLoad = form => {
  const urlParams = new URLSearchParams(window.location.search);
  let shouldTrigger = false;

  // Define which query keys you're using (matching your PHP form logic)
  const filterKeys = ['filter_cornerlabels', 'filter_training_theme'];
  filterKeys.forEach(key => {
    if (urlParams.has(key) && urlParams.get(key) !== '') {
      shouldTrigger = true;
    }
  });
  if (!shouldTrigger) {
    return;
  }
  doFiltering();
  form.scrollIntoView({
    behavior: 'smooth'
  });

  // Clean up the query string in the browser address bar
  const url = new URL(window.location);
  url.search = ''; // Remove all query params
  window.history.replaceState({}, document.title, url);
};
const trainingFiltering = () => {
  const form = document.querySelector('.training-archive-filtering');
  if (!form) {
    return;
  }

  // Add event listener for the form submit button
  form.addEventListener('submit', doFiltering);

  // If a query parameter is found (from a redirect), filter the results and scroll into view
  triggerFormUpdateOnPageLoad(form);
};

/***/ }),

/***/ "./resources/js/lib/translations.js":
/*!******************************************!*\
  !*** ./resources/js/lib/translations.js ***!
  \******************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   getTranslations: () => (/* binding */ getTranslations)
/* harmony export */ });
const getTranslations = elementId => {
  const el = document.getElementById(elementId);
  if (!el) {
    throw Error('Cannot find ' + elementId);
  }
  return JSON.parse(el.innerHTML);
};

/***/ }),

/***/ "./resources/js/lib/userFavs.js":
/*!**************************************!*\
  !*** ./resources/js/lib/userFavs.js ***!
  \**************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   userFavs: () => (/* binding */ userFavs)
/* harmony export */ });
/* harmony import */ var _translations__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./translations */ "./resources/js/lib/translations.js");

const T = (0,_translations__WEBPACK_IMPORTED_MODULE_0__.getTranslations)('opehuone-variables');
const pinSvg = `<svg width="42" height="46" viewBox="0 0 42 46" fill="none" xmlns="http://www.w3.org/2000/svg">
  <circle cx="21" cy="21.3828" r="21" fill="white" fill-opacity="0.8"></circle>
  <path d="M28 31.3828V11.3828H14V31.3828L21 26.8828L28 31.3828Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
</svg>`;
const pinnedSvg = `<svg width="42" height="42" viewBox="0 0 42 42" fill="none" xmlns="http://www.w3.org/2000/svg">
  <circle cx="21" cy="21" r="21" fill="white" fill-opacity="0.85"></circle>
  <path d="M28 31V11H14V31L21 26.5L28 31Z" fill="#008741" stroke="#008741" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
</svg>`;
const buttonAriaPinned = 'Poista sivu kirjanmerkeistä';
const buttonAriaPin = 'Lisää sivu kirjanmerkkeihin';
const addToFavs = () => {
  document.addEventListener('click', event => {
    const pinnerButton = event.target.closest('.b-post__pinner');
    if (!pinnerButton) return; // Ignore clicks outside .b-post__pinner buttons

    const action = pinnerButton.getAttribute('data-action');
    const postId = pinnerButton.getAttribute('data-post-id');
    if (!action || !postId) {
      console.error('Missing data attributes: action or postId.');
      return;
    }
    fetch(T.ajaxUrl, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      body: new URLSearchParams({
        action,
        userId: T.userId,
        postId,
        nonce: T.opehuoneNonce
      })
    }).then(response => {
      if (!response.ok) {
        throw new Error(`HTTP error! Status: ${response.status}`);
      }
      return response.json();
    }).then(() => {
      // Toggle button content and data-action attribute
      if (action === 'favs_add') {
        pinnerButton.innerHTML = pinnedSvg;
        pinnerButton.setAttribute('data-action', 'favs_remove');
        pinnerButton.setAttribute('aria-label', buttonAriaPinned);
      } else {
        pinnerButton.innerHTML = pinSvg;
        pinnerButton.setAttribute('data-action', 'favs_add');
        pinnerButton.setAttribute('aria-label', buttonAriaPin);
      }
    }).catch(error => console.error('AJAX Error:', error));
  });
};
const userFavs = () => {
  addToFavs();
};

/***/ }),

/***/ "./resources/js/routes/Common.js":
/*!***************************************!*\
  !*** ./resources/js/routes/Common.js ***!
  \***************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _lib_scrollTo__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../lib/scrollTo */ "./resources/js/lib/scrollTo.js");
/* harmony import */ var _lib_sideLinksList__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../lib/sideLinksList */ "./resources/js/lib/sideLinksList.js");
/* harmony import */ var _lib_serviceFailure__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../lib/serviceFailure */ "./resources/js/lib/serviceFailure.js");
/* harmony import */ var _lib_userFavs__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../lib/userFavs */ "./resources/js/lib/userFavs.js");
/* harmony import */ var _lib_postsFiltering__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../lib/postsFiltering */ "./resources/js/lib/postsFiltering.js");
/* harmony import */ var _lib_profileOpener__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../lib/profileOpener */ "./resources/js/lib/profileOpener.js");






/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  init() {
    // Hashtag smooth scrolls
    (0,_lib_scrollTo__WEBPACK_IMPORTED_MODULE_0__.hashTagFunctions)();
    // Side links list functions
    (0,_lib_sideLinksList__WEBPACK_IMPORTED_MODULE_1__.sideLinksList)();
    // Service failure
    (0,_lib_serviceFailure__WEBPACK_IMPORTED_MODULE_2__.serviceFailure)();
    // User favs functions
    (0,_lib_userFavs__WEBPACK_IMPORTED_MODULE_3__.userFavs)();
    // Posts filtering
    (0,_lib_postsFiltering__WEBPACK_IMPORTED_MODULE_4__.postsFiltering)();
    // Profile opener
    (0,_lib_profileOpener__WEBPACK_IMPORTED_MODULE_5__.profileOpener)();
  },
  finalize() {
    // JavaScript to be fired on all pages, after page specific JS is fired
  }
});

/***/ }),

/***/ "./resources/js/routes/blog.js":
/*!*************************************!*\
  !*** ./resources/js/routes/blog.js ***!
  \*************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _lib_postsArchiveFiltering__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../lib/postsArchiveFiltering */ "./resources/js/lib/postsArchiveFiltering.js");

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  init() {
    (0,_lib_postsArchiveFiltering__WEBPACK_IMPORTED_MODULE_0__.postsArchiveFiltering)();
  },
  finalize() {
    // JavaScript to be fired after the init JS
  }
});

/***/ }),

/***/ "./resources/js/routes/pageTemplateUserSettings.js":
/*!*********************************************************!*\
  !*** ./resources/js/routes/pageTemplateUserSettings.js ***!
  \*********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _lib_translations__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../lib/translations */ "./resources/js/lib/translations.js");

const submitButton = document.querySelector('.user-settings-form__submit-button');
const T = (0,_lib_translations__WEBPACK_IMPORTED_MODULE_0__.getTranslations)('opehuone-variables');
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  init() {
    const form = document.getElementById('user-settings');
    if (!form) return;
    form.addEventListener('submit', function (event) {
      event.preventDefault();
      submitButton.classList.add('is-disabled');
      const originalButtonText = submitButton.textContent;
      submitButton.textContent = 'Asetuksia päivitetään';
      const formData = new FormData(form);
      const serializedData = {};
      formData.forEach((value, key) => {
        if (key === 'cornerlabels[]') {
          serializedData[key] = serializedData[key] || [];
          serializedData[key].push(value);
        } else {
          serializedData[key] = value;
        }
      });
      fetch(T.ajaxUrl, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams({
          action: 'update_user_settings',
          userId: T.userId,
          cornerLabels: serializedData['cornerlabels[]'],
          nonce: T.opehuoneNonce
        })
      }).then(response => {
        if (!response.ok) {
          throw new Error(`HTTP error! Status: ${response.status}`);
        }
        return response.json();
      }) // Assuming the response is JSON
      .then(() => {
        submitButton.textContent = 'Asetukset päivitetty';
        setTimeout(() => {
          submitButton.classList.remove('is-disabled');
          submitButton.textContent = originalButtonText;
        }, 3000);
      }).catch(error => console.error('AJAX Error:', error));
    });
  },
  finalize() {
    // JavaScript to be fired after the init JS
  }
});

/***/ }),

/***/ "./resources/js/routes/pageTemplateWithSidemenu.js":
/*!*********************************************************!*\
  !*** ./resources/js/routes/pageTemplateWithSidemenu.js ***!
  \*********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _lib_sidemenuToggler__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../lib/sidemenuToggler */ "./resources/js/lib/sidemenuToggler.js");

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  init() {
    (0,_lib_sidemenuToggler__WEBPACK_IMPORTED_MODULE_0__.sidemenuToggler)();
  },
  finalize() {
    // JavaScript to be fired after the init JS
  }
});

/***/ }),

/***/ "./resources/js/routes/postTypeArchiveTraining.js":
/*!********************************************************!*\
  !*** ./resources/js/routes/postTypeArchiveTraining.js ***!
  \********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _lib_trainingFiltering__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../lib/trainingFiltering */ "./resources/js/lib/trainingFiltering.js");

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  init() {
    (0,_lib_trainingFiltering__WEBPACK_IMPORTED_MODULE_0__.trainingFiltering)();
  },
  finalize() {
    // JavaScript to be fired after the init JS
  }
});

/***/ }),

/***/ "./resources/js/util/router.js":
/*!*************************************!*\
  !*** ./resources/js/util/router.js ***!
  \*************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ Router)
/* harmony export */ });
/* harmony import */ var lodash_camelCase__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! lodash/camelCase */ "./node_modules/lodash/camelCase.js");
/* harmony import */ var lodash_camelCase__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(lodash_camelCase__WEBPACK_IMPORTED_MODULE_0__);
/* ========================================================================
 * DOM-based Routing
 * Based on http://goo.gl/EUTi53 by Paul Irish
 *
 * Only fires on body classes that match. If a body class contains a dash,
 * replace the dash with an underscore when adding it to the object below.
 * ======================================================================== */



// The routing fires all common scripts, followed by the page specific scripts.
// Add additional events for more control over timing e.g. a finalize event
class Router {
  constructor(routes) {
    this.routes = routes;
  }
  fire(route, fn = 'init', args) {
    const fire = route !== '' && this.routes[route] && typeof this.routes[route][fn] === 'function';
    if (fire) {
      this.routes[route][fn](args);
    }
  }
  loadEvents() {
    // Fire common init JS
    this.fire('common');

    // Fire page-specific init JS, and then finalize JS
    document.body.className.toLowerCase().replace(/-/g, '_').split(/\s+/).map((lodash_camelCase__WEBPACK_IMPORTED_MODULE_0___default())).forEach(className => {
      this.fire(className);
      this.fire(className, 'finalize');
    });

    // Fire common finalize JS
    this.fire('common', 'finalize');
  }
}

/***/ }),

/***/ "./node_modules/lodash/_Symbol.js":
/*!****************************************!*\
  !*** ./node_modules/lodash/_Symbol.js ***!
  \****************************************/
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var root = __webpack_require__(/*! ./_root */ "./node_modules/lodash/_root.js");

/** Built-in value references. */
var Symbol = root.Symbol;

module.exports = Symbol;


/***/ }),

/***/ "./node_modules/lodash/_arrayMap.js":
/*!******************************************!*\
  !*** ./node_modules/lodash/_arrayMap.js ***!
  \******************************************/
/***/ ((module) => {

/**
 * A specialized version of `_.map` for arrays without support for iteratee
 * shorthands.
 *
 * @private
 * @param {Array} [array] The array to iterate over.
 * @param {Function} iteratee The function invoked per iteration.
 * @returns {Array} Returns the new mapped array.
 */
function arrayMap(array, iteratee) {
  var index = -1,
      length = array == null ? 0 : array.length,
      result = Array(length);

  while (++index < length) {
    result[index] = iteratee(array[index], index, array);
  }
  return result;
}

module.exports = arrayMap;


/***/ }),

/***/ "./node_modules/lodash/_arrayReduce.js":
/*!*********************************************!*\
  !*** ./node_modules/lodash/_arrayReduce.js ***!
  \*********************************************/
/***/ ((module) => {

/**
 * A specialized version of `_.reduce` for arrays without support for
 * iteratee shorthands.
 *
 * @private
 * @param {Array} [array] The array to iterate over.
 * @param {Function} iteratee The function invoked per iteration.
 * @param {*} [accumulator] The initial value.
 * @param {boolean} [initAccum] Specify using the first element of `array` as
 *  the initial value.
 * @returns {*} Returns the accumulated value.
 */
function arrayReduce(array, iteratee, accumulator, initAccum) {
  var index = -1,
      length = array == null ? 0 : array.length;

  if (initAccum && length) {
    accumulator = array[++index];
  }
  while (++index < length) {
    accumulator = iteratee(accumulator, array[index], index, array);
  }
  return accumulator;
}

module.exports = arrayReduce;


/***/ }),

/***/ "./node_modules/lodash/_asciiToArray.js":
/*!**********************************************!*\
  !*** ./node_modules/lodash/_asciiToArray.js ***!
  \**********************************************/
/***/ ((module) => {

/**
 * Converts an ASCII `string` to an array.
 *
 * @private
 * @param {string} string The string to convert.
 * @returns {Array} Returns the converted array.
 */
function asciiToArray(string) {
  return string.split('');
}

module.exports = asciiToArray;


/***/ }),

/***/ "./node_modules/lodash/_asciiWords.js":
/*!********************************************!*\
  !*** ./node_modules/lodash/_asciiWords.js ***!
  \********************************************/
/***/ ((module) => {

/** Used to match words composed of alphanumeric characters. */
var reAsciiWord = /[^\x00-\x2f\x3a-\x40\x5b-\x60\x7b-\x7f]+/g;

/**
 * Splits an ASCII `string` into an array of its words.
 *
 * @private
 * @param {string} The string to inspect.
 * @returns {Array} Returns the words of `string`.
 */
function asciiWords(string) {
  return string.match(reAsciiWord) || [];
}

module.exports = asciiWords;


/***/ }),

/***/ "./node_modules/lodash/_baseGetTag.js":
/*!********************************************!*\
  !*** ./node_modules/lodash/_baseGetTag.js ***!
  \********************************************/
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var Symbol = __webpack_require__(/*! ./_Symbol */ "./node_modules/lodash/_Symbol.js"),
    getRawTag = __webpack_require__(/*! ./_getRawTag */ "./node_modules/lodash/_getRawTag.js"),
    objectToString = __webpack_require__(/*! ./_objectToString */ "./node_modules/lodash/_objectToString.js");

/** `Object#toString` result references. */
var nullTag = '[object Null]',
    undefinedTag = '[object Undefined]';

/** Built-in value references. */
var symToStringTag = Symbol ? Symbol.toStringTag : undefined;

/**
 * The base implementation of `getTag` without fallbacks for buggy environments.
 *
 * @private
 * @param {*} value The value to query.
 * @returns {string} Returns the `toStringTag`.
 */
function baseGetTag(value) {
  if (value == null) {
    return value === undefined ? undefinedTag : nullTag;
  }
  return (symToStringTag && symToStringTag in Object(value))
    ? getRawTag(value)
    : objectToString(value);
}

module.exports = baseGetTag;


/***/ }),

/***/ "./node_modules/lodash/_basePropertyOf.js":
/*!************************************************!*\
  !*** ./node_modules/lodash/_basePropertyOf.js ***!
  \************************************************/
/***/ ((module) => {

/**
 * The base implementation of `_.propertyOf` without support for deep paths.
 *
 * @private
 * @param {Object} object The object to query.
 * @returns {Function} Returns the new accessor function.
 */
function basePropertyOf(object) {
  return function(key) {
    return object == null ? undefined : object[key];
  };
}

module.exports = basePropertyOf;


/***/ }),

/***/ "./node_modules/lodash/_baseSlice.js":
/*!*******************************************!*\
  !*** ./node_modules/lodash/_baseSlice.js ***!
  \*******************************************/
/***/ ((module) => {

/**
 * The base implementation of `_.slice` without an iteratee call guard.
 *
 * @private
 * @param {Array} array The array to slice.
 * @param {number} [start=0] The start position.
 * @param {number} [end=array.length] The end position.
 * @returns {Array} Returns the slice of `array`.
 */
function baseSlice(array, start, end) {
  var index = -1,
      length = array.length;

  if (start < 0) {
    start = -start > length ? 0 : (length + start);
  }
  end = end > length ? length : end;
  if (end < 0) {
    end += length;
  }
  length = start > end ? 0 : ((end - start) >>> 0);
  start >>>= 0;

  var result = Array(length);
  while (++index < length) {
    result[index] = array[index + start];
  }
  return result;
}

module.exports = baseSlice;


/***/ }),

/***/ "./node_modules/lodash/_baseToString.js":
/*!**********************************************!*\
  !*** ./node_modules/lodash/_baseToString.js ***!
  \**********************************************/
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var Symbol = __webpack_require__(/*! ./_Symbol */ "./node_modules/lodash/_Symbol.js"),
    arrayMap = __webpack_require__(/*! ./_arrayMap */ "./node_modules/lodash/_arrayMap.js"),
    isArray = __webpack_require__(/*! ./isArray */ "./node_modules/lodash/isArray.js"),
    isSymbol = __webpack_require__(/*! ./isSymbol */ "./node_modules/lodash/isSymbol.js");

/** Used as references for various `Number` constants. */
var INFINITY = 1 / 0;

/** Used to convert symbols to primitives and strings. */
var symbolProto = Symbol ? Symbol.prototype : undefined,
    symbolToString = symbolProto ? symbolProto.toString : undefined;

/**
 * The base implementation of `_.toString` which doesn't convert nullish
 * values to empty strings.
 *
 * @private
 * @param {*} value The value to process.
 * @returns {string} Returns the string.
 */
function baseToString(value) {
  // Exit early for strings to avoid a performance hit in some environments.
  if (typeof value == 'string') {
    return value;
  }
  if (isArray(value)) {
    // Recursively convert values (susceptible to call stack limits).
    return arrayMap(value, baseToString) + '';
  }
  if (isSymbol(value)) {
    return symbolToString ? symbolToString.call(value) : '';
  }
  var result = (value + '');
  return (result == '0' && (1 / value) == -INFINITY) ? '-0' : result;
}

module.exports = baseToString;


/***/ }),

/***/ "./node_modules/lodash/_castSlice.js":
/*!*******************************************!*\
  !*** ./node_modules/lodash/_castSlice.js ***!
  \*******************************************/
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var baseSlice = __webpack_require__(/*! ./_baseSlice */ "./node_modules/lodash/_baseSlice.js");

/**
 * Casts `array` to a slice if it's needed.
 *
 * @private
 * @param {Array} array The array to inspect.
 * @param {number} start The start position.
 * @param {number} [end=array.length] The end position.
 * @returns {Array} Returns the cast slice.
 */
function castSlice(array, start, end) {
  var length = array.length;
  end = end === undefined ? length : end;
  return (!start && end >= length) ? array : baseSlice(array, start, end);
}

module.exports = castSlice;


/***/ }),

/***/ "./node_modules/lodash/_createCaseFirst.js":
/*!*************************************************!*\
  !*** ./node_modules/lodash/_createCaseFirst.js ***!
  \*************************************************/
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var castSlice = __webpack_require__(/*! ./_castSlice */ "./node_modules/lodash/_castSlice.js"),
    hasUnicode = __webpack_require__(/*! ./_hasUnicode */ "./node_modules/lodash/_hasUnicode.js"),
    stringToArray = __webpack_require__(/*! ./_stringToArray */ "./node_modules/lodash/_stringToArray.js"),
    toString = __webpack_require__(/*! ./toString */ "./node_modules/lodash/toString.js");

/**
 * Creates a function like `_.lowerFirst`.
 *
 * @private
 * @param {string} methodName The name of the `String` case method to use.
 * @returns {Function} Returns the new case function.
 */
function createCaseFirst(methodName) {
  return function(string) {
    string = toString(string);

    var strSymbols = hasUnicode(string)
      ? stringToArray(string)
      : undefined;

    var chr = strSymbols
      ? strSymbols[0]
      : string.charAt(0);

    var trailing = strSymbols
      ? castSlice(strSymbols, 1).join('')
      : string.slice(1);

    return chr[methodName]() + trailing;
  };
}

module.exports = createCaseFirst;


/***/ }),

/***/ "./node_modules/lodash/_createCompounder.js":
/*!**************************************************!*\
  !*** ./node_modules/lodash/_createCompounder.js ***!
  \**************************************************/
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var arrayReduce = __webpack_require__(/*! ./_arrayReduce */ "./node_modules/lodash/_arrayReduce.js"),
    deburr = __webpack_require__(/*! ./deburr */ "./node_modules/lodash/deburr.js"),
    words = __webpack_require__(/*! ./words */ "./node_modules/lodash/words.js");

/** Used to compose unicode capture groups. */
var rsApos = "['\u2019]";

/** Used to match apostrophes. */
var reApos = RegExp(rsApos, 'g');

/**
 * Creates a function like `_.camelCase`.
 *
 * @private
 * @param {Function} callback The function to combine each word.
 * @returns {Function} Returns the new compounder function.
 */
function createCompounder(callback) {
  return function(string) {
    return arrayReduce(words(deburr(string).replace(reApos, '')), callback, '');
  };
}

module.exports = createCompounder;


/***/ }),

/***/ "./node_modules/lodash/_deburrLetter.js":
/*!**********************************************!*\
  !*** ./node_modules/lodash/_deburrLetter.js ***!
  \**********************************************/
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var basePropertyOf = __webpack_require__(/*! ./_basePropertyOf */ "./node_modules/lodash/_basePropertyOf.js");

/** Used to map Latin Unicode letters to basic Latin letters. */
var deburredLetters = {
  // Latin-1 Supplement block.
  '\xc0': 'A',  '\xc1': 'A', '\xc2': 'A', '\xc3': 'A', '\xc4': 'A', '\xc5': 'A',
  '\xe0': 'a',  '\xe1': 'a', '\xe2': 'a', '\xe3': 'a', '\xe4': 'a', '\xe5': 'a',
  '\xc7': 'C',  '\xe7': 'c',
  '\xd0': 'D',  '\xf0': 'd',
  '\xc8': 'E',  '\xc9': 'E', '\xca': 'E', '\xcb': 'E',
  '\xe8': 'e',  '\xe9': 'e', '\xea': 'e', '\xeb': 'e',
  '\xcc': 'I',  '\xcd': 'I', '\xce': 'I', '\xcf': 'I',
  '\xec': 'i',  '\xed': 'i', '\xee': 'i', '\xef': 'i',
  '\xd1': 'N',  '\xf1': 'n',
  '\xd2': 'O',  '\xd3': 'O', '\xd4': 'O', '\xd5': 'O', '\xd6': 'O', '\xd8': 'O',
  '\xf2': 'o',  '\xf3': 'o', '\xf4': 'o', '\xf5': 'o', '\xf6': 'o', '\xf8': 'o',
  '\xd9': 'U',  '\xda': 'U', '\xdb': 'U', '\xdc': 'U',
  '\xf9': 'u',  '\xfa': 'u', '\xfb': 'u', '\xfc': 'u',
  '\xdd': 'Y',  '\xfd': 'y', '\xff': 'y',
  '\xc6': 'Ae', '\xe6': 'ae',
  '\xde': 'Th', '\xfe': 'th',
  '\xdf': 'ss',
  // Latin Extended-A block.
  '\u0100': 'A',  '\u0102': 'A', '\u0104': 'A',
  '\u0101': 'a',  '\u0103': 'a', '\u0105': 'a',
  '\u0106': 'C',  '\u0108': 'C', '\u010a': 'C', '\u010c': 'C',
  '\u0107': 'c',  '\u0109': 'c', '\u010b': 'c', '\u010d': 'c',
  '\u010e': 'D',  '\u0110': 'D', '\u010f': 'd', '\u0111': 'd',
  '\u0112': 'E',  '\u0114': 'E', '\u0116': 'E', '\u0118': 'E', '\u011a': 'E',
  '\u0113': 'e',  '\u0115': 'e', '\u0117': 'e', '\u0119': 'e', '\u011b': 'e',
  '\u011c': 'G',  '\u011e': 'G', '\u0120': 'G', '\u0122': 'G',
  '\u011d': 'g',  '\u011f': 'g', '\u0121': 'g', '\u0123': 'g',
  '\u0124': 'H',  '\u0126': 'H', '\u0125': 'h', '\u0127': 'h',
  '\u0128': 'I',  '\u012a': 'I', '\u012c': 'I', '\u012e': 'I', '\u0130': 'I',
  '\u0129': 'i',  '\u012b': 'i', '\u012d': 'i', '\u012f': 'i', '\u0131': 'i',
  '\u0134': 'J',  '\u0135': 'j',
  '\u0136': 'K',  '\u0137': 'k', '\u0138': 'k',
  '\u0139': 'L',  '\u013b': 'L', '\u013d': 'L', '\u013f': 'L', '\u0141': 'L',
  '\u013a': 'l',  '\u013c': 'l', '\u013e': 'l', '\u0140': 'l', '\u0142': 'l',
  '\u0143': 'N',  '\u0145': 'N', '\u0147': 'N', '\u014a': 'N',
  '\u0144': 'n',  '\u0146': 'n', '\u0148': 'n', '\u014b': 'n',
  '\u014c': 'O',  '\u014e': 'O', '\u0150': 'O',
  '\u014d': 'o',  '\u014f': 'o', '\u0151': 'o',
  '\u0154': 'R',  '\u0156': 'R', '\u0158': 'R',
  '\u0155': 'r',  '\u0157': 'r', '\u0159': 'r',
  '\u015a': 'S',  '\u015c': 'S', '\u015e': 'S', '\u0160': 'S',
  '\u015b': 's',  '\u015d': 's', '\u015f': 's', '\u0161': 's',
  '\u0162': 'T',  '\u0164': 'T', '\u0166': 'T',
  '\u0163': 't',  '\u0165': 't', '\u0167': 't',
  '\u0168': 'U',  '\u016a': 'U', '\u016c': 'U', '\u016e': 'U', '\u0170': 'U', '\u0172': 'U',
  '\u0169': 'u',  '\u016b': 'u', '\u016d': 'u', '\u016f': 'u', '\u0171': 'u', '\u0173': 'u',
  '\u0174': 'W',  '\u0175': 'w',
  '\u0176': 'Y',  '\u0177': 'y', '\u0178': 'Y',
  '\u0179': 'Z',  '\u017b': 'Z', '\u017d': 'Z',
  '\u017a': 'z',  '\u017c': 'z', '\u017e': 'z',
  '\u0132': 'IJ', '\u0133': 'ij',
  '\u0152': 'Oe', '\u0153': 'oe',
  '\u0149': "'n", '\u017f': 's'
};

/**
 * Used by `_.deburr` to convert Latin-1 Supplement and Latin Extended-A
 * letters to basic Latin letters.
 *
 * @private
 * @param {string} letter The matched letter to deburr.
 * @returns {string} Returns the deburred letter.
 */
var deburrLetter = basePropertyOf(deburredLetters);

module.exports = deburrLetter;


/***/ }),

/***/ "./node_modules/lodash/_freeGlobal.js":
/*!********************************************!*\
  !*** ./node_modules/lodash/_freeGlobal.js ***!
  \********************************************/
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

/** Detect free variable `global` from Node.js. */
var freeGlobal = typeof __webpack_require__.g == 'object' && __webpack_require__.g && __webpack_require__.g.Object === Object && __webpack_require__.g;

module.exports = freeGlobal;


/***/ }),

/***/ "./node_modules/lodash/_getRawTag.js":
/*!*******************************************!*\
  !*** ./node_modules/lodash/_getRawTag.js ***!
  \*******************************************/
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var Symbol = __webpack_require__(/*! ./_Symbol */ "./node_modules/lodash/_Symbol.js");

/** Used for built-in method references. */
var objectProto = Object.prototype;

/** Used to check objects for own properties. */
var hasOwnProperty = objectProto.hasOwnProperty;

/**
 * Used to resolve the
 * [`toStringTag`](http://ecma-international.org/ecma-262/7.0/#sec-object.prototype.tostring)
 * of values.
 */
var nativeObjectToString = objectProto.toString;

/** Built-in value references. */
var symToStringTag = Symbol ? Symbol.toStringTag : undefined;

/**
 * A specialized version of `baseGetTag` which ignores `Symbol.toStringTag` values.
 *
 * @private
 * @param {*} value The value to query.
 * @returns {string} Returns the raw `toStringTag`.
 */
function getRawTag(value) {
  var isOwn = hasOwnProperty.call(value, symToStringTag),
      tag = value[symToStringTag];

  try {
    value[symToStringTag] = undefined;
    var unmasked = true;
  } catch (e) {}

  var result = nativeObjectToString.call(value);
  if (unmasked) {
    if (isOwn) {
      value[symToStringTag] = tag;
    } else {
      delete value[symToStringTag];
    }
  }
  return result;
}

module.exports = getRawTag;


/***/ }),

/***/ "./node_modules/lodash/_hasUnicode.js":
/*!********************************************!*\
  !*** ./node_modules/lodash/_hasUnicode.js ***!
  \********************************************/
/***/ ((module) => {

/** Used to compose unicode character classes. */
var rsAstralRange = '\\ud800-\\udfff',
    rsComboMarksRange = '\\u0300-\\u036f',
    reComboHalfMarksRange = '\\ufe20-\\ufe2f',
    rsComboSymbolsRange = '\\u20d0-\\u20ff',
    rsComboRange = rsComboMarksRange + reComboHalfMarksRange + rsComboSymbolsRange,
    rsVarRange = '\\ufe0e\\ufe0f';

/** Used to compose unicode capture groups. */
var rsZWJ = '\\u200d';

/** Used to detect strings with [zero-width joiners or code points from the astral planes](http://eev.ee/blog/2015/09/12/dark-corners-of-unicode/). */
var reHasUnicode = RegExp('[' + rsZWJ + rsAstralRange  + rsComboRange + rsVarRange + ']');

/**
 * Checks if `string` contains Unicode symbols.
 *
 * @private
 * @param {string} string The string to inspect.
 * @returns {boolean} Returns `true` if a symbol is found, else `false`.
 */
function hasUnicode(string) {
  return reHasUnicode.test(string);
}

module.exports = hasUnicode;


/***/ }),

/***/ "./node_modules/lodash/_hasUnicodeWord.js":
/*!************************************************!*\
  !*** ./node_modules/lodash/_hasUnicodeWord.js ***!
  \************************************************/
/***/ ((module) => {

/** Used to detect strings that need a more robust regexp to match words. */
var reHasUnicodeWord = /[a-z][A-Z]|[A-Z]{2}[a-z]|[0-9][a-zA-Z]|[a-zA-Z][0-9]|[^a-zA-Z0-9 ]/;

/**
 * Checks if `string` contains a word composed of Unicode symbols.
 *
 * @private
 * @param {string} string The string to inspect.
 * @returns {boolean} Returns `true` if a word is found, else `false`.
 */
function hasUnicodeWord(string) {
  return reHasUnicodeWord.test(string);
}

module.exports = hasUnicodeWord;


/***/ }),

/***/ "./node_modules/lodash/_objectToString.js":
/*!************************************************!*\
  !*** ./node_modules/lodash/_objectToString.js ***!
  \************************************************/
/***/ ((module) => {

/** Used for built-in method references. */
var objectProto = Object.prototype;

/**
 * Used to resolve the
 * [`toStringTag`](http://ecma-international.org/ecma-262/7.0/#sec-object.prototype.tostring)
 * of values.
 */
var nativeObjectToString = objectProto.toString;

/**
 * Converts `value` to a string using `Object.prototype.toString`.
 *
 * @private
 * @param {*} value The value to convert.
 * @returns {string} Returns the converted string.
 */
function objectToString(value) {
  return nativeObjectToString.call(value);
}

module.exports = objectToString;


/***/ }),

/***/ "./node_modules/lodash/_root.js":
/*!**************************************!*\
  !*** ./node_modules/lodash/_root.js ***!
  \**************************************/
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var freeGlobal = __webpack_require__(/*! ./_freeGlobal */ "./node_modules/lodash/_freeGlobal.js");

/** Detect free variable `self`. */
var freeSelf = typeof self == 'object' && self && self.Object === Object && self;

/** Used as a reference to the global object. */
var root = freeGlobal || freeSelf || Function('return this')();

module.exports = root;


/***/ }),

/***/ "./node_modules/lodash/_stringToArray.js":
/*!***********************************************!*\
  !*** ./node_modules/lodash/_stringToArray.js ***!
  \***********************************************/
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var asciiToArray = __webpack_require__(/*! ./_asciiToArray */ "./node_modules/lodash/_asciiToArray.js"),
    hasUnicode = __webpack_require__(/*! ./_hasUnicode */ "./node_modules/lodash/_hasUnicode.js"),
    unicodeToArray = __webpack_require__(/*! ./_unicodeToArray */ "./node_modules/lodash/_unicodeToArray.js");

/**
 * Converts `string` to an array.
 *
 * @private
 * @param {string} string The string to convert.
 * @returns {Array} Returns the converted array.
 */
function stringToArray(string) {
  return hasUnicode(string)
    ? unicodeToArray(string)
    : asciiToArray(string);
}

module.exports = stringToArray;


/***/ }),

/***/ "./node_modules/lodash/_unicodeToArray.js":
/*!************************************************!*\
  !*** ./node_modules/lodash/_unicodeToArray.js ***!
  \************************************************/
/***/ ((module) => {

/** Used to compose unicode character classes. */
var rsAstralRange = '\\ud800-\\udfff',
    rsComboMarksRange = '\\u0300-\\u036f',
    reComboHalfMarksRange = '\\ufe20-\\ufe2f',
    rsComboSymbolsRange = '\\u20d0-\\u20ff',
    rsComboRange = rsComboMarksRange + reComboHalfMarksRange + rsComboSymbolsRange,
    rsVarRange = '\\ufe0e\\ufe0f';

/** Used to compose unicode capture groups. */
var rsAstral = '[' + rsAstralRange + ']',
    rsCombo = '[' + rsComboRange + ']',
    rsFitz = '\\ud83c[\\udffb-\\udfff]',
    rsModifier = '(?:' + rsCombo + '|' + rsFitz + ')',
    rsNonAstral = '[^' + rsAstralRange + ']',
    rsRegional = '(?:\\ud83c[\\udde6-\\uddff]){2}',
    rsSurrPair = '[\\ud800-\\udbff][\\udc00-\\udfff]',
    rsZWJ = '\\u200d';

/** Used to compose unicode regexes. */
var reOptMod = rsModifier + '?',
    rsOptVar = '[' + rsVarRange + ']?',
    rsOptJoin = '(?:' + rsZWJ + '(?:' + [rsNonAstral, rsRegional, rsSurrPair].join('|') + ')' + rsOptVar + reOptMod + ')*',
    rsSeq = rsOptVar + reOptMod + rsOptJoin,
    rsSymbol = '(?:' + [rsNonAstral + rsCombo + '?', rsCombo, rsRegional, rsSurrPair, rsAstral].join('|') + ')';

/** Used to match [string symbols](https://mathiasbynens.be/notes/javascript-unicode). */
var reUnicode = RegExp(rsFitz + '(?=' + rsFitz + ')|' + rsSymbol + rsSeq, 'g');

/**
 * Converts a Unicode `string` to an array.
 *
 * @private
 * @param {string} string The string to convert.
 * @returns {Array} Returns the converted array.
 */
function unicodeToArray(string) {
  return string.match(reUnicode) || [];
}

module.exports = unicodeToArray;


/***/ }),

/***/ "./node_modules/lodash/_unicodeWords.js":
/*!**********************************************!*\
  !*** ./node_modules/lodash/_unicodeWords.js ***!
  \**********************************************/
/***/ ((module) => {

/** Used to compose unicode character classes. */
var rsAstralRange = '\\ud800-\\udfff',
    rsComboMarksRange = '\\u0300-\\u036f',
    reComboHalfMarksRange = '\\ufe20-\\ufe2f',
    rsComboSymbolsRange = '\\u20d0-\\u20ff',
    rsComboRange = rsComboMarksRange + reComboHalfMarksRange + rsComboSymbolsRange,
    rsDingbatRange = '\\u2700-\\u27bf',
    rsLowerRange = 'a-z\\xdf-\\xf6\\xf8-\\xff',
    rsMathOpRange = '\\xac\\xb1\\xd7\\xf7',
    rsNonCharRange = '\\x00-\\x2f\\x3a-\\x40\\x5b-\\x60\\x7b-\\xbf',
    rsPunctuationRange = '\\u2000-\\u206f',
    rsSpaceRange = ' \\t\\x0b\\f\\xa0\\ufeff\\n\\r\\u2028\\u2029\\u1680\\u180e\\u2000\\u2001\\u2002\\u2003\\u2004\\u2005\\u2006\\u2007\\u2008\\u2009\\u200a\\u202f\\u205f\\u3000',
    rsUpperRange = 'A-Z\\xc0-\\xd6\\xd8-\\xde',
    rsVarRange = '\\ufe0e\\ufe0f',
    rsBreakRange = rsMathOpRange + rsNonCharRange + rsPunctuationRange + rsSpaceRange;

/** Used to compose unicode capture groups. */
var rsApos = "['\u2019]",
    rsBreak = '[' + rsBreakRange + ']',
    rsCombo = '[' + rsComboRange + ']',
    rsDigits = '\\d+',
    rsDingbat = '[' + rsDingbatRange + ']',
    rsLower = '[' + rsLowerRange + ']',
    rsMisc = '[^' + rsAstralRange + rsBreakRange + rsDigits + rsDingbatRange + rsLowerRange + rsUpperRange + ']',
    rsFitz = '\\ud83c[\\udffb-\\udfff]',
    rsModifier = '(?:' + rsCombo + '|' + rsFitz + ')',
    rsNonAstral = '[^' + rsAstralRange + ']',
    rsRegional = '(?:\\ud83c[\\udde6-\\uddff]){2}',
    rsSurrPair = '[\\ud800-\\udbff][\\udc00-\\udfff]',
    rsUpper = '[' + rsUpperRange + ']',
    rsZWJ = '\\u200d';

/** Used to compose unicode regexes. */
var rsMiscLower = '(?:' + rsLower + '|' + rsMisc + ')',
    rsMiscUpper = '(?:' + rsUpper + '|' + rsMisc + ')',
    rsOptContrLower = '(?:' + rsApos + '(?:d|ll|m|re|s|t|ve))?',
    rsOptContrUpper = '(?:' + rsApos + '(?:D|LL|M|RE|S|T|VE))?',
    reOptMod = rsModifier + '?',
    rsOptVar = '[' + rsVarRange + ']?',
    rsOptJoin = '(?:' + rsZWJ + '(?:' + [rsNonAstral, rsRegional, rsSurrPair].join('|') + ')' + rsOptVar + reOptMod + ')*',
    rsOrdLower = '\\d*(?:1st|2nd|3rd|(?![123])\\dth)(?=\\b|[A-Z_])',
    rsOrdUpper = '\\d*(?:1ST|2ND|3RD|(?![123])\\dTH)(?=\\b|[a-z_])',
    rsSeq = rsOptVar + reOptMod + rsOptJoin,
    rsEmoji = '(?:' + [rsDingbat, rsRegional, rsSurrPair].join('|') + ')' + rsSeq;

/** Used to match complex or compound words. */
var reUnicodeWord = RegExp([
  rsUpper + '?' + rsLower + '+' + rsOptContrLower + '(?=' + [rsBreak, rsUpper, '$'].join('|') + ')',
  rsMiscUpper + '+' + rsOptContrUpper + '(?=' + [rsBreak, rsUpper + rsMiscLower, '$'].join('|') + ')',
  rsUpper + '?' + rsMiscLower + '+' + rsOptContrLower,
  rsUpper + '+' + rsOptContrUpper,
  rsOrdUpper,
  rsOrdLower,
  rsDigits,
  rsEmoji
].join('|'), 'g');

/**
 * Splits a Unicode `string` into an array of its words.
 *
 * @private
 * @param {string} The string to inspect.
 * @returns {Array} Returns the words of `string`.
 */
function unicodeWords(string) {
  return string.match(reUnicodeWord) || [];
}

module.exports = unicodeWords;


/***/ }),

/***/ "./node_modules/lodash/camelCase.js":
/*!******************************************!*\
  !*** ./node_modules/lodash/camelCase.js ***!
  \******************************************/
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var capitalize = __webpack_require__(/*! ./capitalize */ "./node_modules/lodash/capitalize.js"),
    createCompounder = __webpack_require__(/*! ./_createCompounder */ "./node_modules/lodash/_createCompounder.js");

/**
 * Converts `string` to [camel case](https://en.wikipedia.org/wiki/CamelCase).
 *
 * @static
 * @memberOf _
 * @since 3.0.0
 * @category String
 * @param {string} [string=''] The string to convert.
 * @returns {string} Returns the camel cased string.
 * @example
 *
 * _.camelCase('Foo Bar');
 * // => 'fooBar'
 *
 * _.camelCase('--foo-bar--');
 * // => 'fooBar'
 *
 * _.camelCase('__FOO_BAR__');
 * // => 'fooBar'
 */
var camelCase = createCompounder(function(result, word, index) {
  word = word.toLowerCase();
  return result + (index ? capitalize(word) : word);
});

module.exports = camelCase;


/***/ }),

/***/ "./node_modules/lodash/capitalize.js":
/*!*******************************************!*\
  !*** ./node_modules/lodash/capitalize.js ***!
  \*******************************************/
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var toString = __webpack_require__(/*! ./toString */ "./node_modules/lodash/toString.js"),
    upperFirst = __webpack_require__(/*! ./upperFirst */ "./node_modules/lodash/upperFirst.js");

/**
 * Converts the first character of `string` to upper case and the remaining
 * to lower case.
 *
 * @static
 * @memberOf _
 * @since 3.0.0
 * @category String
 * @param {string} [string=''] The string to capitalize.
 * @returns {string} Returns the capitalized string.
 * @example
 *
 * _.capitalize('FRED');
 * // => 'Fred'
 */
function capitalize(string) {
  return upperFirst(toString(string).toLowerCase());
}

module.exports = capitalize;


/***/ }),

/***/ "./node_modules/lodash/deburr.js":
/*!***************************************!*\
  !*** ./node_modules/lodash/deburr.js ***!
  \***************************************/
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var deburrLetter = __webpack_require__(/*! ./_deburrLetter */ "./node_modules/lodash/_deburrLetter.js"),
    toString = __webpack_require__(/*! ./toString */ "./node_modules/lodash/toString.js");

/** Used to match Latin Unicode letters (excluding mathematical operators). */
var reLatin = /[\xc0-\xd6\xd8-\xf6\xf8-\xff\u0100-\u017f]/g;

/** Used to compose unicode character classes. */
var rsComboMarksRange = '\\u0300-\\u036f',
    reComboHalfMarksRange = '\\ufe20-\\ufe2f',
    rsComboSymbolsRange = '\\u20d0-\\u20ff',
    rsComboRange = rsComboMarksRange + reComboHalfMarksRange + rsComboSymbolsRange;

/** Used to compose unicode capture groups. */
var rsCombo = '[' + rsComboRange + ']';

/**
 * Used to match [combining diacritical marks](https://en.wikipedia.org/wiki/Combining_Diacritical_Marks) and
 * [combining diacritical marks for symbols](https://en.wikipedia.org/wiki/Combining_Diacritical_Marks_for_Symbols).
 */
var reComboMark = RegExp(rsCombo, 'g');

/**
 * Deburrs `string` by converting
 * [Latin-1 Supplement](https://en.wikipedia.org/wiki/Latin-1_Supplement_(Unicode_block)#Character_table)
 * and [Latin Extended-A](https://en.wikipedia.org/wiki/Latin_Extended-A)
 * letters to basic Latin letters and removing
 * [combining diacritical marks](https://en.wikipedia.org/wiki/Combining_Diacritical_Marks).
 *
 * @static
 * @memberOf _
 * @since 3.0.0
 * @category String
 * @param {string} [string=''] The string to deburr.
 * @returns {string} Returns the deburred string.
 * @example
 *
 * _.deburr('déjà vu');
 * // => 'deja vu'
 */
function deburr(string) {
  string = toString(string);
  return string && string.replace(reLatin, deburrLetter).replace(reComboMark, '');
}

module.exports = deburr;


/***/ }),

/***/ "./node_modules/lodash/isArray.js":
/*!****************************************!*\
  !*** ./node_modules/lodash/isArray.js ***!
  \****************************************/
/***/ ((module) => {

/**
 * Checks if `value` is classified as an `Array` object.
 *
 * @static
 * @memberOf _
 * @since 0.1.0
 * @category Lang
 * @param {*} value The value to check.
 * @returns {boolean} Returns `true` if `value` is an array, else `false`.
 * @example
 *
 * _.isArray([1, 2, 3]);
 * // => true
 *
 * _.isArray(document.body.children);
 * // => false
 *
 * _.isArray('abc');
 * // => false
 *
 * _.isArray(_.noop);
 * // => false
 */
var isArray = Array.isArray;

module.exports = isArray;


/***/ }),

/***/ "./node_modules/lodash/isObjectLike.js":
/*!*********************************************!*\
  !*** ./node_modules/lodash/isObjectLike.js ***!
  \*********************************************/
/***/ ((module) => {

/**
 * Checks if `value` is object-like. A value is object-like if it's not `null`
 * and has a `typeof` result of "object".
 *
 * @static
 * @memberOf _
 * @since 4.0.0
 * @category Lang
 * @param {*} value The value to check.
 * @returns {boolean} Returns `true` if `value` is object-like, else `false`.
 * @example
 *
 * _.isObjectLike({});
 * // => true
 *
 * _.isObjectLike([1, 2, 3]);
 * // => true
 *
 * _.isObjectLike(_.noop);
 * // => false
 *
 * _.isObjectLike(null);
 * // => false
 */
function isObjectLike(value) {
  return value != null && typeof value == 'object';
}

module.exports = isObjectLike;


/***/ }),

/***/ "./node_modules/lodash/isSymbol.js":
/*!*****************************************!*\
  !*** ./node_modules/lodash/isSymbol.js ***!
  \*****************************************/
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var baseGetTag = __webpack_require__(/*! ./_baseGetTag */ "./node_modules/lodash/_baseGetTag.js"),
    isObjectLike = __webpack_require__(/*! ./isObjectLike */ "./node_modules/lodash/isObjectLike.js");

/** `Object#toString` result references. */
var symbolTag = '[object Symbol]';

/**
 * Checks if `value` is classified as a `Symbol` primitive or object.
 *
 * @static
 * @memberOf _
 * @since 4.0.0
 * @category Lang
 * @param {*} value The value to check.
 * @returns {boolean} Returns `true` if `value` is a symbol, else `false`.
 * @example
 *
 * _.isSymbol(Symbol.iterator);
 * // => true
 *
 * _.isSymbol('abc');
 * // => false
 */
function isSymbol(value) {
  return typeof value == 'symbol' ||
    (isObjectLike(value) && baseGetTag(value) == symbolTag);
}

module.exports = isSymbol;


/***/ }),

/***/ "./node_modules/lodash/toString.js":
/*!*****************************************!*\
  !*** ./node_modules/lodash/toString.js ***!
  \*****************************************/
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var baseToString = __webpack_require__(/*! ./_baseToString */ "./node_modules/lodash/_baseToString.js");

/**
 * Converts `value` to a string. An empty string is returned for `null`
 * and `undefined` values. The sign of `-0` is preserved.
 *
 * @static
 * @memberOf _
 * @since 4.0.0
 * @category Lang
 * @param {*} value The value to convert.
 * @returns {string} Returns the converted string.
 * @example
 *
 * _.toString(null);
 * // => ''
 *
 * _.toString(-0);
 * // => '-0'
 *
 * _.toString([1, 2, 3]);
 * // => '1,2,3'
 */
function toString(value) {
  return value == null ? '' : baseToString(value);
}

module.exports = toString;


/***/ }),

/***/ "./node_modules/lodash/upperFirst.js":
/*!*******************************************!*\
  !*** ./node_modules/lodash/upperFirst.js ***!
  \*******************************************/
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var createCaseFirst = __webpack_require__(/*! ./_createCaseFirst */ "./node_modules/lodash/_createCaseFirst.js");

/**
 * Converts the first character of `string` to upper case.
 *
 * @static
 * @memberOf _
 * @since 4.0.0
 * @category String
 * @param {string} [string=''] The string to convert.
 * @returns {string} Returns the converted string.
 * @example
 *
 * _.upperFirst('fred');
 * // => 'Fred'
 *
 * _.upperFirst('FRED');
 * // => 'FRED'
 */
var upperFirst = createCaseFirst('toUpperCase');

module.exports = upperFirst;


/***/ }),

/***/ "./node_modules/lodash/words.js":
/*!**************************************!*\
  !*** ./node_modules/lodash/words.js ***!
  \**************************************/
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

var asciiWords = __webpack_require__(/*! ./_asciiWords */ "./node_modules/lodash/_asciiWords.js"),
    hasUnicodeWord = __webpack_require__(/*! ./_hasUnicodeWord */ "./node_modules/lodash/_hasUnicodeWord.js"),
    toString = __webpack_require__(/*! ./toString */ "./node_modules/lodash/toString.js"),
    unicodeWords = __webpack_require__(/*! ./_unicodeWords */ "./node_modules/lodash/_unicodeWords.js");

/**
 * Splits `string` into an array of its words.
 *
 * @static
 * @memberOf _
 * @since 3.0.0
 * @category String
 * @param {string} [string=''] The string to inspect.
 * @param {RegExp|string} [pattern] The pattern to match words.
 * @param- {Object} [guard] Enables use as an iteratee for methods like `_.map`.
 * @returns {Array} Returns the words of `string`.
 * @example
 *
 * _.words('fred, barney, & pebbles');
 * // => ['fred', 'barney', 'pebbles']
 *
 * _.words('fred, barney, & pebbles', /[^, ]+/g);
 * // => ['fred', 'barney', '&', 'pebbles']
 */
function words(string, pattern, guard) {
  string = toString(string);
  pattern = guard ? undefined : pattern;

  if (pattern === undefined) {
    return hasUnicodeWord(string) ? unicodeWords(string) : asciiWords(string);
  }
  return string.match(pattern) || [];
}

module.exports = words;


/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	(() => {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = (module) => {
/******/ 			var getter = module && module.__esModule ?
/******/ 				() => (module['default']) :
/******/ 				() => (module);
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/global */
/******/ 	(() => {
/******/ 		__webpack_require__.g = (function() {
/******/ 			if (typeof globalThis === 'object') return globalThis;
/******/ 			try {
/******/ 				return this || new Function('return this')();
/******/ 			} catch (e) {
/******/ 				if (typeof window === 'object') return window;
/******/ 			}
/******/ 		})();
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry needs to be wrapped in an IIFE because it needs to be in strict mode.
(() => {
"use strict";
/*!********************************!*\
  !*** ./resources/js/screen.js ***!
  \********************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _util_router__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./util/router */ "./resources/js/util/router.js");
/* harmony import */ var _routes_Common__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./routes/Common */ "./resources/js/routes/Common.js");
/* harmony import */ var _routes_pageTemplateUserSettings__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./routes/pageTemplateUserSettings */ "./resources/js/routes/pageTemplateUserSettings.js");
/* harmony import */ var _routes_pageTemplateWithSidemenu__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./routes/pageTemplateWithSidemenu */ "./resources/js/routes/pageTemplateWithSidemenu.js");
/* harmony import */ var _routes_postTypeArchiveTraining__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./routes/postTypeArchiveTraining */ "./resources/js/routes/postTypeArchiveTraining.js");
/* harmony import */ var _routes_blog__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./routes/blog */ "./resources/js/routes/blog.js");
// import local dependencies







/**
 *
 * @type {Router}
 */
const router = new _util_router__WEBPACK_IMPORTED_MODULE_0__["default"]({
  // All pages
  common: _routes_Common__WEBPACK_IMPORTED_MODULE_1__["default"],
  // User settings
  pageTemplateUserSettings: _routes_pageTemplateUserSettings__WEBPACK_IMPORTED_MODULE_2__["default"],
  // Sidemenu page
  pageTemplateWithSidemenu: _routes_pageTemplateWithSidemenu__WEBPACK_IMPORTED_MODULE_3__["default"],
  // Training archive
  postTypeArchiveTraining: _routes_postTypeArchiveTraining__WEBPACK_IMPORTED_MODULE_4__["default"],
  // Blog
  blog: _routes_blog__WEBPACK_IMPORTED_MODULE_5__["default"]
});

// Load Events
document.addEventListener('DOMContentLoaded', () => router.loadEvents());
})();

/******/ })()
;
//# sourceMappingURL=screen.js.map