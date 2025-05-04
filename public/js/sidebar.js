document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.getElementById('custom-sidebar');
    const content = document.getElementById('main-content');
    const toggleBtn = document.getElementById('toggle-sidebar');
    const toggleIcon = document.getElementById('toggle-icon');

    if (!sidebar || !content || !toggleBtn || !toggleIcon) {
        console.error('Required elements not found!');
        return;
    }

    const toggleSidebar = () => {
        sidebar.classList.toggle('collapsed');
        toggleBtn.classList.toggle('expanded');
        toggleBtn.classList.toggle('collapsed');
        content.style.marginRight = sidebar.classList.contains('collapsed') ? '0' : '250px';
        toggleIcon.setAttribute('d', sidebar.classList.contains('collapsed')
            ? 'M4 6h16M4 12h16M4 18h16' // Hamburger icon
            : 'M6 18L18 6M6 6l12 12'  // X icon
        );
    };

    // Attach event listener using event delegation
    document.body.addEventListener('click', function (event) {
        if (event.target.closest('#toggle-sidebar')) {
            toggleSidebar();
        }
    });

    // Set initial state to collapsed
    sidebar.classList.add('collapsed');
    toggleBtn.classList.add('collapsed');
    content.style.marginRight = '0';
    toggleIcon.setAttribute('d', 'M4 6h16M4 12h16M4 18h16'); // Set hamburger icon initially

    // Handle Livewire updates
    if (window.Livewire) {
        window.Livewire.hook('element.updated', () => {
            console.log('Livewire updated DOM, ensuring event listeners remain active.');
        });
    }
});
