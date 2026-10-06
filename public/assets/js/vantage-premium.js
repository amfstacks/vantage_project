(() => {
  'use strict';

  const qs = (selector, root = document) => root.querySelector(selector);
  const qsa = (selector, root = document) => [...root.querySelectorAll(selector)];
  const escapeHtml = (value = '') => String(value).replace(/[&<>'"]/g, (char) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', "'": '&#039;', '"': '&quot;' }[char]));
  const money = (value, currency = '₦') => `${currency}${Number(value || 0).toLocaleString('en-NG', { maximumFractionDigits: 0 })}`;
  const setBodyLocked = (locked) => document.body.classList.toggle('vl-modal-open', Boolean(locked));

  // Mobile navigation.
  const mobileToggle = qs('[data-mobile-toggle]');
  const mobilePanel = qs('[data-mobile-panel]');
  if (mobileToggle && mobilePanel) {
    mobileToggle.addEventListener('click', () => {
      const open = mobilePanel.classList.toggle('open');
      mobileToggle.setAttribute('aria-expanded', String(open));
      mobileToggle.innerHTML = open ? '<i class="fa-solid fa-xmark"></i>' : '<i class="fa-solid fa-bars"></i>';
    });
  }

  // Reveal animation. Can be re-run after AJAX injects cards.
  let revealObserver = null;
  const initReveal = (root = document) => {
    const elements = qsa('[data-reveal]:not(.is-visible)', root);
    if (!elements.length) return;

    if ('IntersectionObserver' in window) {
      if (!revealObserver) {
        revealObserver = new IntersectionObserver((entries) => {
          entries.forEach((entry) => {
            if (entry.isIntersecting) {
              entry.target.classList.add('is-visible');
              revealObserver.unobserve(entry.target);
            }
          });
        }, { threshold: 0.07, rootMargin: '0px 0px -24px 0px' });
      }
      elements.forEach((el) => revealObserver.observe(el));
    } else {
      elements.forEach((el) => el.classList.add('is-visible'));
    }
  };
  initReveal();

  // Lightweight searchable select: Select2-like UX without jQuery or another blocking dependency.
  const smartSelects = [];
  const closeSmartSelects = (except = null) => {
    smartSelects.forEach((instance) => {
      if (instance !== except) instance.close();
    });
  };

  const initSmartSelect = (select) => {
    if (!select || select.dataset.smartReady === '1') return;
    select.dataset.smartReady = '1';
    select.classList.add('vl-native-select');

    const wrap = document.createElement('div');
    wrap.className = 'vl-smart-select';
    wrap.innerHTML = `
      <button class="vl-smart-select__trigger" type="button" aria-expanded="false">
        <span></span><i class="fa-solid fa-chevron-down"></i>
      </button>
      <div class="vl-smart-select__panel" role="listbox">
        <div class="vl-smart-select__search"><i class="fa-solid fa-magnifying-glass"></i><input type="search" autocomplete="off" placeholder="${escapeHtml(select.dataset.searchPlaceholder || 'Search options…')}" aria-label="Search options"></div>
        <div class="vl-smart-select__options"></div>
        <div class="vl-smart-select__empty">No matching option</div>
      </div>`;
    select.insertAdjacentElement('afterend', wrap);

    const trigger = qs('.vl-smart-select__trigger', wrap);
    const triggerText = qs('span', trigger);
    const panel = qs('.vl-smart-select__panel', wrap);
    const search = qs('input', panel);
    const optionsWrap = qs('.vl-smart-select__options', panel);
    const empty = qs('.vl-smart-select__empty', panel);

    const renderOptions = () => {
      optionsWrap.innerHTML = '';
      [...select.options].forEach((option) => {
        const button = document.createElement('button');
        button.type = 'button';
        button.className = 'vl-smart-select__option';
        button.dataset.value = option.value;
        button.dataset.search = option.text.toLowerCase();
        button.setAttribute('role', 'option');
        button.setAttribute('aria-selected', option.selected ? 'true' : 'false');
        button.disabled = option.disabled;
        button.innerHTML = `<span>${escapeHtml(option.text)}</span>${option.selected ? '<i class="fa-solid fa-check"></i>' : ''}`;
        optionsWrap.appendChild(button);
      });
    };

    const sync = () => {
      const selected = select.options[select.selectedIndex] || select.options[0];
      triggerText.textContent = selected ? selected.text : 'Select';
      qsa('.vl-smart-select__option', optionsWrap).forEach((button) => {
        const isSelected = button.dataset.value === select.value;
        button.setAttribute('aria-selected', String(isSelected));
        const icon = qs('i', button);
        if (isSelected && !icon) button.insertAdjacentHTML('beforeend', '<i class="fa-solid fa-check"></i>');
        if (!isSelected && icon) icon.remove();
      });
    };

    const close = () => {
      wrap.classList.remove('open');
      trigger.setAttribute('aria-expanded', 'false');
      search.value = '';
      qsa('.vl-smart-select__option', optionsWrap).forEach((button) => { button.hidden = false; });
      empty.classList.remove('show');
    };

    const open = () => {
      closeSmartSelects(instance);
      wrap.classList.add('open');
      trigger.setAttribute('aria-expanded', 'true');
      window.setTimeout(() => search.focus({ preventScroll: true }), 30);
    };

    const instance = { select, wrap, close, open, sync };
    smartSelects.push(instance);
    select._vlSmartSync = sync;

    renderOptions();
    sync();

    trigger.addEventListener('click', () => wrap.classList.contains('open') ? close() : open());
    search.addEventListener('input', () => {
      const term = search.value.trim().toLowerCase();
      let visible = 0;
      qsa('.vl-smart-select__option', optionsWrap).forEach((button) => {
        const match = !term || button.dataset.search.includes(term);
        button.hidden = !match;
        if (match) visible += 1;
      });
      empty.classList.toggle('show', visible === 0);
    });
    optionsWrap.addEventListener('click', (event) => {
      const choice = event.target.closest('.vl-smart-select__option');
      if (!choice || choice.disabled) return;
      select.value = choice.dataset.value;
      sync();
      close();
      select.dispatchEvent(new Event('change', { bubbles: true }));
    });
    select.addEventListener('change', sync);
  };

  qsa('[data-search-select]').forEach(initSmartSelect);
  document.addEventListener('click', (event) => {
    if (!event.target.closest('.vl-smart-select')) closeSmartSelects();
  });
  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') closeSmartSelects();
  });

  // Home property finder: a compact sticky action until intentionally opened.
  const searchDock = qs('[data-search-dock]');
  if (searchDock) {
    const trigger = qs('[data-search-dock-trigger]', searchDock);
    const panel = qs('[data-search-dock-panel]', searchDock);
    const openDock = () => {
      searchDock.classList.add('open');
      trigger?.setAttribute('aria-expanded', 'true');
      panel?.setAttribute('aria-hidden', 'false');
    };
    const closeDock = () => {
      searchDock.classList.remove('open');
      trigger?.setAttribute('aria-expanded', 'false');
      panel?.setAttribute('aria-hidden', 'true');
      closeSmartSelects();
    };
    trigger?.addEventListener('click', () => searchDock.classList.contains('open') ? closeDock() : openDock());
    qs('[data-search-dock-close]', searchDock)?.addEventListener('click', closeDock);
  }

  // Listing-page mobile filter drawer/sticky action.
  const filterForm = qs('#propertyFilterForm');
  const results = qs('#propertyResults');
  const filterToggle = qs('[data-filter-toggle]');
  const closeFilter = () => {
    if (!filterForm) return;
    filterForm.classList.remove('mobile-open');
    filterToggle?.setAttribute('aria-expanded', 'false');
  };
  filterToggle?.addEventListener('click', () => {
    if (!filterForm) return;
    const open = filterForm.classList.toggle('mobile-open');
    filterToggle.setAttribute('aria-expanded', String(open));
  });
  qs('[data-filter-close]')?.addEventListener('click', closeFilter);

  if (filterForm && results) {
    filterForm.noValidate = true;
    let controller = null;
    let debounceTimer = null;

    const syncFilterUI = () => {
      qsa('[data-search-select]', filterForm).forEach((select) => select._vlSmartSync?.());
    };

    const updateCounts = (total) => {
      qsa('[data-results-count], [data-results-count-mobile]').forEach((node) => {
        node.textContent = Number(total || 0).toLocaleString();
      });
    };

    const loadResults = async (url, pushState = true, scroll = true) => {
      if (controller) controller.abort();
      controller = new AbortController();
      results.classList.add('vl-loading');

      try {
        const requestUrl = new URL(url, window.location.origin);
        const ajaxUrl = new URL(filterForm.dataset.ajaxUrl, window.location.origin);
        ajaxUrl.search = requestUrl.search;

        const response = await fetch(ajaxUrl.toString(), {
          headers: { 'X-Requested-With': 'XMLHttpRequest' },
          signal: controller.signal,
        });
        if (!response.ok) throw new Error('Could not load properties');
        const data = await response.json();
        results.innerHTML = data.html;
        updateCounts(data.total);
        initReveal(results);
        if (pushState) history.pushState({}, '', requestUrl.pathname + requestUrl.search);
        if (window.innerWidth <= 900) closeFilter();
        if (scroll) {
          window.scrollTo({ top: Math.max(0, results.getBoundingClientRect().top + window.scrollY - 130), behavior: 'smooth' });
        }
      } catch (error) {
        if (error.name !== 'AbortError') {
          results.innerHTML = '<div class="vl-empty"><i class="fa-solid fa-triangle-exclamation"></i><h3>We could not load the listings.</h3><p>Please check your connection and try again.</p></div>';
        }
      } finally {
        results.classList.remove('vl-loading');
      }
    };

    const submitFilters = (scroll = true) => {
      const params = new URLSearchParams(new FormData(filterForm));
      [...params.entries()].forEach(([key, value]) => {
        if (String(value).trim() === '') params.delete(key);
      });
      params.delete('page');
      const query = params.toString();
      loadResults(`${filterForm.action}${query ? `?${query}` : ''}`, true, scroll);
    };

    filterForm.addEventListener('submit', (event) => {
      event.preventDefault();
      submitFilters();
    });

    qsa('select', filterForm).forEach((select) => select.addEventListener('change', () => submitFilters(false)));
    qsa('input[type="number"]', filterForm).forEach((input) => input.addEventListener('change', () => submitFilters(false)));
    const searchInput = qs('input[name="q"]', filterForm);
    if (searchInput) {
      searchInput.addEventListener('input', () => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => submitFilters(false), 420);
      });
    }

    qs('[data-clear-filters]')?.addEventListener('click', (event) => {
      event.preventDefault();
      filterForm.reset();
      syncFilterUI();
      loadResults(filterForm.action, true, false);
    });

    results.addEventListener('click', (event) => {
      const link = event.target.closest('.vl-pagination a');
      if (!link) return;
      event.preventDefault();
      loadResults(link.href);
    });

    window.addEventListener('popstate', () => {
      const current = new URL(window.location.href);
      qsa('input, select', filterForm).forEach((field) => {
        if (!field.name) return;
        field.value = current.searchParams.get(field.name) || '';
      });
      syncFilterUI();
      loadResults(window.location.href, false, false);
    });
  }

  // Pricing-options modal used by every property card, including AJAX-loaded cards.
  const pricingModal = qs('[data-pricing-modal]');
  if (pricingModal) {
    const title = qs('[data-pricing-title]', pricingModal);
    const body = qs('[data-pricing-body]', pricingModal);
    const propertyLink = qs('[data-pricing-property-link]', pricingModal);

    const closePricing = () => {
      pricingModal.classList.remove('open');
      pricingModal.setAttribute('aria-hidden', 'true');
      setBodyLocked(false);
    };

    document.addEventListener('click', (event) => {
      const trigger = event.target.closest('[data-pricing-trigger]');
      if (!trigger) return;
      event.preventDefault();

      let options = [];
      try { options = JSON.parse(trigger.dataset.pricing || '[]'); } catch (_) { options = []; }
      const currency = trigger.dataset.currency || '₦';
      if (title) title.textContent = trigger.dataset.propertyTitle || 'Available prices';
      if (propertyLink) propertyLink.href = trigger.dataset.propertyUrl || '#';

      if (body) {
        body.innerHTML = options.length ? options.map((option, index) => {
          const hasDiscount = option.discount !== null && Number(option.discount) > 0 && Number(option.discount) < Number(option.price);
          const purpose = option.purpose ? `<span>${escapeHtml(option.purpose)}</span>` : '';
          const unit = String(option.unit || 'One Time');
          const unitLabel = unit.toLowerCase() === 'one time' ? 'One-time price' : `Per ${escapeHtml(unit)}`;
          const percentage = Number(option.percentage || 0);
          return `<article class="vl-pricing-modal-option">
            <div class="vl-pricing-modal-option__top"><span class="vl-pricing-index">${String(index + 1).padStart(2, '0')}</span><div>${purpose}<span>${unitLabel}</span></div>${percentage > 0 ? `<b>${percentage.toFixed(percentage % 1 ? 1 : 0)}% off</b>` : ''}</div>
            <div class="vl-pricing-modal-option__amount"><strong>${money(option.effective, currency)}</strong>${hasDiscount ? `<s>${money(option.price, currency)}</s>` : ''}</div>
          </article>`;
        }).join('') : '<div class="vl-empty vl-empty--compact"><p>No additional pricing options are available.</p></div>';
      }

      pricingModal.classList.add('open');
      pricingModal.setAttribute('aria-hidden', 'false');
      setBodyLocked(true);
    });

    qsa('[data-pricing-close]', pricingModal).forEach((button) => button.addEventListener('click', closePricing));
    document.addEventListener('keydown', (event) => { if (event.key === 'Escape' && pricingModal.classList.contains('open')) closePricing(); });
  }

  // Full property gallery with previous/next controls and thumbnail rail.
  const galleryModal = qs('[data-gallery-modal]');
  if (galleryModal) {
    let galleryData = [];
    let currentIndex = 0;
    try { galleryData = JSON.parse(qs('[data-gallery-data]', galleryModal)?.textContent || '[]'); } catch (_) { galleryData = []; }
    const view = qs('[data-gallery-view]', galleryModal);
    const counter = qs('[data-gallery-counter]', galleryModal);

    const updateGallery = () => {
      if (!galleryData.length || !view) return;
      currentIndex = (currentIndex + galleryData.length) % galleryData.length;
      const item = galleryData[currentIndex];
      view.src = item.url;
      view.alt = item.alt || 'Property image';
      if (counter) counter.textContent = `${currentIndex + 1} / ${galleryData.length}`;
      qsa('[data-gallery-jump]', galleryModal).forEach((button) => {
        button.classList.toggle('is-active', Number(button.dataset.galleryJump) === currentIndex);
      });
      qs(`[data-gallery-jump="${currentIndex}"]`, galleryModal)?.scrollIntoView({ inline: 'center', block: 'nearest', behavior: 'smooth' });
    };

    const openGallery = (index = 0) => {
      currentIndex = Number(index) || 0;
      updateGallery();
      galleryModal.classList.add('open');
      galleryModal.setAttribute('aria-hidden', 'false');
      setBodyLocked(true);
    };
    const closeGallery = () => {
      galleryModal.classList.remove('open');
      galleryModal.setAttribute('aria-hidden', 'true');
      setBodyLocked(false);
    };

    qsa('[data-gallery-open]').forEach((button) => button.addEventListener('click', () => openGallery(button.dataset.galleryOpen)));
    qs('[data-gallery-prev]', galleryModal)?.addEventListener('click', () => { currentIndex -= 1; updateGallery(); });
    qs('[data-gallery-next]', galleryModal)?.addEventListener('click', () => { currentIndex += 1; updateGallery(); });
    qsa('[data-gallery-jump]', galleryModal).forEach((button) => button.addEventListener('click', () => { currentIndex = Number(button.dataset.galleryJump); updateGallery(); }));
    qs('[data-gallery-close]', galleryModal)?.addEventListener('click', closeGallery);

    document.addEventListener('keydown', (event) => {
      if (!galleryModal.classList.contains('open')) return;
      if (event.key === 'Escape') closeGallery();
      if (event.key === 'ArrowLeft') { currentIndex -= 1; updateGallery(); }
      if (event.key === 'ArrowRight') { currentIndex += 1; updateGallery(); }
    });
  }

  // Fullscreen YouTube walkthrough. The iframe is only populated when opened.
  const videoModal = qs('[data-video-modal]');
  if (videoModal) {
    const frame = qs('[data-video-frame]', videoModal);
    const baseSrc = videoModal.dataset.videoSrc || '';
    const closeVideo = () => {
      videoModal.classList.remove('open');
      videoModal.setAttribute('aria-hidden', 'true');
      if (frame) frame.src = '';
      setBodyLocked(false);
    };
    qsa('[data-video-open]').forEach((button) => button.addEventListener('click', () => {
      if (frame && baseSrc) frame.src = `${baseSrc}${baseSrc.includes('?') ? '&' : '?'}autoplay=1`;
      videoModal.classList.add('open');
      videoModal.setAttribute('aria-hidden', 'false');
      setBodyLocked(true);
    }));
    qs('[data-video-close]', videoModal)?.addEventListener('click', closeVideo);
    videoModal.addEventListener('click', (event) => { if (event.target === videoModal) closeVideo(); });
    document.addEventListener('keydown', (event) => { if (event.key === 'Escape' && videoModal.classList.contains('open')) closeVideo(); });
  }

  // Enquiry drawer and sticky page action.
  const enquiryLayer = qs('[data-enquiry-layer]');
  if (enquiryLayer) {
    document.body.classList.add('vl-has-property-dock');
    const openEnquiry = () => {
      enquiryLayer.classList.add('open');
      enquiryLayer.setAttribute('aria-hidden', 'false');
      setBodyLocked(true);
      window.setTimeout(() => qs('input:not([type="hidden"])', enquiryLayer)?.focus({ preventScroll: true }), 250);
    };
    const closeEnquiry = () => {
      enquiryLayer.classList.remove('open');
      enquiryLayer.setAttribute('aria-hidden', 'true');
      setBodyLocked(false);
      closeSmartSelects();
    };
    qsa('[data-enquiry-open]').forEach((button) => button.addEventListener('click', openEnquiry));
    qsa('[data-enquiry-close]', enquiryLayer).forEach((button) => button.addEventListener('click', closeEnquiry));
    document.addEventListener('keydown', (event) => { if (event.key === 'Escape' && enquiryLayer.classList.contains('open')) closeEnquiry(); });
  }

  // Save enquiry first, then continue to WhatsApp.
  const requestForm = qs('#propertyRequestForm');
  if (requestForm) {
    const statusBox = qs('[data-request-status]', requestForm);
    requestForm.addEventListener('submit', async (event) => {
      event.preventDefault();
      const button = qs('button[type="submit"]', requestForm);
      const oldHtml = button ? button.innerHTML : '';
      if (button) {
        button.disabled = true;
        button.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> Saving request…';
      }
      if (statusBox) {
        statusBox.className = 'vl-request-status';
        statusBox.textContent = '';
      }

      try {
        const response = await fetch(requestForm.action, {
          method: 'POST',
          headers: { 'X-Requested-With': 'XMLHttpRequest' },
          body: new FormData(requestForm),
        });
        const data = await response.json();
        if (!response.ok || !data.ok) throw new Error(data.message || 'Could not send request');
        if (statusBox) {
          statusBox.classList.add('success');
          statusBox.textContent = data.message;
        }
        if (data.whatsapp_url) {
          window.setTimeout(() => { window.location.href = data.whatsapp_url; }, 650);
        }
      } catch (error) {
        if (statusBox) {
          statusBox.classList.add('error');
          statusBox.textContent = error.message || 'Could not send your request. Please try again.';
        }
      } finally {
        if (button) {
          button.disabled = false;
          button.innerHTML = oldHtml;
        }
      }
    });
  }
})();
