const headTitle = document.getElementById('head-title')
const menuBtn = document.getElementById('toggleBtn')

document.addEventListener('DOMContentLoaded', function () {
    const navbar = document.getElementById('navbar');
    const toggleBtn = document.getElementById('toggleBtn');

    toggleBtn.addEventListener('click', function () {
        navbar.classList.toggle('open');
        if (navbar.classList.contains('open')) {
            toggleBtn.innerHTML = '&times;';
            headTitle.classList.add('pushed')
        } else {
            toggleBtn.innerHTML = '&#9776;';
            headTitle.classList.remove('pushed')
        }
    });
});


