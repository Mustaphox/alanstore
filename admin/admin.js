document.addEventListener('DOMContentLoaded', () => {
  const body = document.body;
  const closeSidebar = () => body.classList.remove('sidebar-open');
  document.querySelector('[data-sidebar-toggle]')?.addEventListener('click', () => body.classList.toggle('sidebar-open'));
  document.querySelector('[data-sidebar-close]')?.addEventListener('click', closeSidebar);
  document.querySelectorAll('#admin-sidebar a').forEach((link) => link.addEventListener('click', closeSidebar));
  document.querySelectorAll('[data-confirm]').forEach((el) => el.addEventListener('click', (event) => {
    if (!confirm(el.dataset.confirm || 'Confirm this action?')) event.preventDefault();
  }));
  document.querySelectorAll('[data-table-search]').forEach((input) => input.addEventListener('input', () => {
    const query = input.value.trim().toLowerCase();
    document.querySelectorAll(input.dataset.tableSearch + ' tbody tr').forEach((row) => row.hidden = !row.textContent.toLowerCase().includes(query));
  }));
  document.querySelectorAll('[data-select-all]').forEach((box) => box.addEventListener('change', () => {
    document.querySelectorAll(box.dataset.selectAll).forEach((item) => item.checked = box.checked);
  }));
  setTimeout(() => document.querySelectorAll('.notice').forEach((n) => n.classList.add('notice-out')), 4500);
});
