(() => {
  const toggle = document.querySelector('[data-admin-toggle]');
  const sidebar = document.querySelector('[data-admin-sidebar]');
  if (toggle && sidebar) {
    toggle.addEventListener('click', () => sidebar.classList.toggle('open'));
    document.addEventListener('click', (e) => {
      if (window.innerWidth <= 900 && sidebar.classList.contains('open') && !sidebar.contains(e.target) && !toggle.contains(e.target)) {
        sidebar.classList.remove('open');
      }
    });
  }

  const imageInput = document.querySelector('[data-image-input]');
  const preview = document.querySelector('[data-image-preview]');
  if (imageInput && preview) {
    imageInput.addEventListener('change', () => {
      preview.innerHTML = '';
      [...imageInput.files].slice(0, 20).forEach((file) => {
        const url = URL.createObjectURL(file);
        const item = document.createElement('div');
        item.className = 'va-image-card';
        item.innerHTML = `<img src="${url}" alt="New property image preview"><div class="va-image-card__foot"><small>${file.name}</small></div>`;
        preview.appendChild(item);
      });
    });
  }

  const priceList = document.querySelector('[data-price-list]');
  const addPrice = document.querySelector('[data-add-price]');
  const template = document.getElementById('priceRowTemplate');

  const formatPercent = (value) => {
    const rounded = Math.round(value * 100) / 100;
    return Number.isInteger(rounded) ? String(rounded) : rounded.toFixed(2).replace(/0+$/, '').replace(/\.$/, '');
  };

  const updateDiscount = (row) => {
    if (!row) return;
    const priceInput = row.querySelector('[data-price-input]');
    const discountInput = row.querySelector('[data-discount-input]');
    const hidden = row.querySelector('[data-discount-hidden]');
    const badge = row.querySelector('[data-discount-badge]');
    const help = row.querySelector('[data-discount-help]');

    const price = Number(priceInput?.value || 0);
    const discount = Number(discountInput?.value || 0);

    row.classList.remove('has-price-error');
    discountInput?.setCustomValidity('');

    if (price > 0 && discount > 0 && discount >= price) {
      if (hidden) hidden.value = '';
      if (badge) {
        badge.textContent = 'Invalid discount';
        badge.classList.remove('has-discount');
        badge.classList.add('has-error');
      }
      if (help) help.textContent = 'Discount price must be lower than the regular price.';
      if (discountInput) discountInput.setCustomValidity('Discount price must be lower than the regular price.');
      row.classList.add('has-price-error');
      return;
    }

    if (price > 0 && discount > 0) {
      const percent = ((price - discount) / price) * 100;
      const text = formatPercent(percent);
      if (hidden) hidden.value = text;
      if (badge) {
        badge.textContent = `${text}% OFF`;
        badge.classList.add('has-discount');
        badge.classList.remove('has-error');
      }
      if (help) help.textContent = `Customer saves ${text}% on this price option.`;
      return;
    }

    if (hidden) hidden.value = '';
    if (badge) {
      badge.textContent = 'No discount';
      badge.classList.remove('has-discount', 'has-error');
    }
    if (help) help.textContent = 'Percentage is calculated automatically.';
  };

  const refreshPriceNumbers = () => {
    if (!priceList) return;
    [...priceList.querySelectorAll('[data-price-row]')].forEach((row, index) => {
      const number = row.querySelector('.va-price-number');
      if (number) number.textContent = `#${index + 1}`;
      updateDiscount(row);
    });
  };

  if (priceList) {
    priceList.addEventListener('input', (e) => {
      if (e.target.matches('[data-price-input], [data-discount-input]')) {
        updateDiscount(e.target.closest('[data-price-row]'));
      }
    });

    priceList.addEventListener('click', (e) => {
      const remove = e.target.closest('[data-remove-price]');
      if (!remove) return;
      const rows = priceList.querySelectorAll('[data-price-row]');
      if (rows.length <= 1) {
        const row = remove.closest('[data-price-row]');
        row?.querySelector('[data-price-input]')?.focus();
        return;
      }
      remove.closest('[data-price-row]')?.remove();
      refreshPriceNumbers();
    });

    refreshPriceNumbers();
  }

  if (priceList && addPrice && template) {
    let nextIndex = Number(priceList.dataset.nextIndex || priceList.querySelectorAll('[data-price-row]').length);
    addPrice.addEventListener('click', () => {
      const number = priceList.querySelectorAll('[data-price-row]').length + 1;
      const html = template.innerHTML
        .replaceAll('__INDEX__', String(nextIndex))
        .replaceAll('__NUMBER__', String(number));
      const wrapper = document.createElement('div');
      wrapper.innerHTML = html.trim();
      const row = wrapper.firstElementChild;
      if (!row) return;
      priceList.appendChild(row);
      nextIndex += 1;
      row.querySelector('[data-price-input]')?.focus();
      row.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
      refreshPriceNumbers();
    });
  }
})();
