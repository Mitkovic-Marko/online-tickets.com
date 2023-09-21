const logo = document.getElementById('logo');
const dropdownMenu = document.getElementById('dropdown-menu');

logo.addEventListener('click', function() {
  dropdownMenu.style.display = dropdownMenu.style.display === 'block' ? 'none' : 'block';
});
