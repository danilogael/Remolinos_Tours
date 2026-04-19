(() => {
  const currencyMenu = document.querySelector('.currency-menu');
  const currencyBtn = document.getElementById('currency-btn');
  const currencyDropdown = document.querySelector('.currency-dropdown');
  const prices = document.querySelectorAll('.price'); // Todos los precios que se convierten

  // Tipos de cambio (puedes actualizar manualmente o desde un admin)
  const exchangeRates = {
    'MXN': 1,
    'USD': 0.054, // 1 MXN = 0.054 USD
    'EUR': 0.049
  };

  let currentCurrency = currencyBtn ? currencyBtn.textContent.trim() : 'MXN';

  // Mostrar/ocultar dropdown
  if (currencyBtn && currencyMenu) {
    currencyBtn.addEventListener('click', (e) => {
      e.stopPropagation();
      currencyMenu.classList.toggle('active');
    });

    document.addEventListener('click', () => {
      currencyMenu.classList.remove('active');
    });
  }

  // Cambiar moneda
  if (currencyDropdown) {
    currencyDropdown.querySelectorAll('li').forEach(li => {
      li.addEventListener('click', () => {
        const newCurrency = li.dataset.currency;
        if (newCurrency === currentCurrency) return;

        currentCurrency = newCurrency;
        currencyBtn.textContent = currentCurrency;

        // Guardar en sesión vía PHP (fetch)
        fetch('/viaje/viaje/LoginAPI/setCurrency.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: `currency=${currentCurrency}`
        }).catch(err => console.error(err));

        // Actualizar todos los precios visibles
        prices.forEach(priceEl => {
          const baseMXN = parseFloat(priceEl.dataset.mxn); // Precio base en MXN
          const newPrice = (baseMXN * exchangeRates[currentCurrency]).toFixed(2);
          priceEl.textContent = `${currentCurrency} ${newPrice}`;
        });
      });
    });
  }
})();
