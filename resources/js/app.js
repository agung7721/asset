import './bootstrap';

document.addEventListener('DOMContentLoaded', function () {
    const body = document.body;
    const toggle = document.getElementById('toggle-darkmode');
    const label = document.getElementById('darkmode-label');

    if (toggle && label) {
        // Cek preferensi dari localStorage
        if (localStorage.getItem('theme') === 'dark') {
            body.classList.add('dark-mode');
            label.textContent = 'Light Mode';
        }

        toggle.addEventListener('click', function (e) {
            e.preventDefault();
            body.classList.toggle('dark-mode');
            if (body.classList.contains('dark-mode')) {
                localStorage.setItem('theme', 'dark');
                label.textContent = 'Light Mode';
            } else {
                localStorage.setItem('theme', 'light');
                label.textContent = 'Dark Mode';
            }
        });
    }
});
