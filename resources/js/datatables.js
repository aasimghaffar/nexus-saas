/**
 * Nexus SaaS Admin - DataTables Utilities
 * Provides instant live table search, status/role filtering, bulk selection,
 * pagination controls, and CSV data export without external libraries.
 */

document.addEventListener('DOMContentLoaded', () => {
  initDataTables();
});

function initDataTables() {
  document.querySelectorAll('[data-table-container]').forEach(container => {
    const table = container.querySelector('table');
    if (!table) return;

    const searchInput = container.querySelector('[data-table-search]');
    const filterSelect = container.querySelector('[data-table-filter]');
    const selectAllCheckbox = container.querySelector('[data-table-select-all]');
    const bulkToolbar = container.querySelector('[data-table-bulk-actions]');
    const bulkCountSpan = container.querySelector('[data-table-selected-count]');
    const exportBtn = container.querySelector('[data-table-export]');
    const tbody = table.querySelector('tbody');
    if (!tbody) return;

    const rows = Array.from(tbody.querySelectorAll('tr'));
    const rowsPerPage = parseInt(container.getAttribute('data-per-page') || '8', 10);
    let currentPage = 1;
    let filteredRows = [...rows];

    // Search filter function
    function applyFilters() {
      const query = (searchInput ? searchInput.value : '').toLowerCase().trim();
      const filterValue = filterSelect ? filterSelect.value.toLowerCase() : 'all';

      filteredRows = rows.filter(row => {
        const text = row.textContent.toLowerCase();
        const matchesQuery = !query || text.includes(query);
        const statusAttr = row.getAttribute('data-status') || '';
        const roleAttr = row.getAttribute('data-role') || '';
        const matchesFilter = filterValue === 'all' || 
                              statusAttr.toLowerCase() === filterValue || 
                              roleAttr.toLowerCase() === filterValue;

        return matchesQuery && matchesFilter;
      });

      currentPage = 1;
      renderTable();
    }

    // Render table rows based on current page
    function renderTable() {
      const startIndex = (currentPage - 1) * rowsPerPage;
      const endIndex = startIndex + rowsPerPage;

      rows.forEach(row => (row.style.display = 'none'));

      filteredRows.slice(startIndex, endIndex).forEach(row => {
        row.style.display = '';
      });

      // Update Pagination UI
      renderPagination();
      updateSelectionCounts();
    }

    // Render Pagination Controls
    function renderPagination() {
      const paginationContainer = container.querySelector('[data-table-pagination]');
      const infoSpan = container.querySelector('[data-table-info]');

      const totalItems = filteredRows.length;
      const totalPages = Math.ceil(totalItems / rowsPerPage) || 1;
      const startCount = totalItems === 0 ? 0 : (currentPage - 1) * rowsPerPage + 1;
      const endCount = Math.min(currentPage * rowsPerPage, totalItems);

      if (infoSpan) {
        infoSpan.textContent = `Showing ${startCount} to ${endCount} of ${totalItems} results`;
      }

      if (!paginationContainer) return;
      paginationContainer.innerHTML = '';

      // Prev Button
      const prevBtn = document.createElement('button');
      prevBtn.type = 'button';
      prevBtn.className = `px-3 py-1 text-xs font-medium rounded-lg border border-slate-200 dark:border-dark-700 ${
        currentPage === 1 ? 'text-slate-300 dark:text-dark-600 cursor-not-allowed' : 'text-slate-600 dark:text-dark-300 hover:bg-slate-50 dark:hover:bg-dark-800'
      }`;
      prevBtn.innerHTML = `&larr; Prev`;
      prevBtn.disabled = currentPage === 1;
      prevBtn.onclick = () => {
        if (currentPage > 1) {
          currentPage--;
          renderTable();
        }
      };
      paginationContainer.appendChild(prevBtn);

      // Page numbers
      for (let i = 1; i <= totalPages; i++) {
        if (i === 1 || i === totalPages || (i >= currentPage - 1 && i <= currentPage + 1)) {
          const pageBtn = document.createElement('button');
          pageBtn.type = 'button';
          pageBtn.className = `px-3 py-1 text-xs font-medium rounded-lg border ${
            i === currentPage
              ? 'bg-brand-600 text-white border-brand-600'
              : 'border-slate-200 dark:border-dark-700 text-slate-600 dark:text-dark-300 hover:bg-slate-50 dark:hover:bg-dark-800'
          }`;
          pageBtn.textContent = i;
          pageBtn.onclick = () => {
            currentPage = i;
            renderTable();
          };
          paginationContainer.appendChild(pageBtn);
        } else if (i === currentPage - 2 || i === currentPage + 2) {
          const dots = document.createElement('span');
          dots.className = 'px-1 text-slate-400 dark:text-dark-500 text-xs';
          dots.textContent = '...';
          paginationContainer.appendChild(dots);
        }
      }

      // Next Button
      const nextBtn = document.createElement('button');
      nextBtn.type = 'button';
      nextBtn.className = `px-3 py-1 text-xs font-medium rounded-lg border border-slate-200 dark:border-dark-700 ${
        currentPage === totalPages || totalPages === 0
          ? 'text-slate-300 dark:text-dark-600 cursor-not-allowed'
          : 'text-slate-600 dark:text-dark-300 hover:bg-slate-50 dark:hover:bg-dark-800'
      }`;
      nextBtn.innerHTML = `Next &rarr;`;
      nextBtn.disabled = currentPage === totalPages || totalPages === 0;
      nextBtn.onclick = () => {
        if (currentPage < totalPages) {
          currentPage++;
          renderTable();
        }
      };
      paginationContainer.appendChild(nextBtn);
    }

    // Row selection & Select All
    if (selectAllCheckbox) {
      selectAllCheckbox.addEventListener('change', () => {
        const checkboxes = tbody.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(cb => {
          cb.checked = selectAllCheckbox.checked;
        });
        updateSelectionCounts();
      });
    }

    tbody.addEventListener('change', (e) => {
      if (e.target.matches('input[type="checkbox"]')) {
        updateSelectionCounts();
      }
    });

    function updateSelectionCounts() {
      const selected = tbody.querySelectorAll('input[type="checkbox"]:checked');
      const count = selected.length;

      if (bulkCountSpan) bulkCountSpan.textContent = count;
      if (bulkToolbar) {
        if (count > 0) {
          bulkToolbar.classList.remove('hidden');
        } else {
          bulkToolbar.classList.add('hidden');
        }
      }

      if (selectAllCheckbox) {
        const allCheckboxes = tbody.querySelectorAll('input[type="checkbox"]');
        selectAllCheckbox.checked = allCheckboxes.length > 0 && selected.length === allCheckboxes.length;
      }
    }

    // Attach search & filter events
    if (searchInput) {
      searchInput.addEventListener('input', applyFilters);
    }
    if (filterSelect) {
      filterSelect.addEventListener('change', applyFilters);
    }

    // Export to CSV
    if (exportBtn) {
      exportBtn.addEventListener('click', () => {
        let csvContent = 'data:text/csv;charset=utf-8,';
        const headers = Array.from(table.querySelectorAll('thead th'))
          .map(th => `"${th.textContent.trim().replace(/"/g, '""')}"`)
          .filter(h => h !== '""');
        csvContent += headers.join(',') + '\r\n';

        filteredRows.forEach(row => {
          const cells = Array.from(row.querySelectorAll('td'))
            .map(td => `"${td.textContent.trim().replace(/\s+/g, ' ').replace(/"/g, '""')}"`)
            .filter(c => c !== '""');
          if (cells.length) {
            csvContent += cells.join(',') + '\r\n';
          }
        });

        const encodedUri = encodeURI(csvContent);
        const link = document.createElement('a');
        link.setAttribute('href', encodedUri);
        link.setAttribute('download', `nexus_export_${new Date().toISOString().slice(0, 10)}.csv`);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);

        if (window.NexusApp && window.NexusApp.showToast) {
          window.NexusApp.showToast({
            title: 'Export Successful',
            message: 'Your table data has been downloaded as CSV.',
            type: 'success'
          });
        }
      });
    }

    // Initial render
    applyFilters();
  });
}
