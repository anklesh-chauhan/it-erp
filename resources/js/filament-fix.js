document.addEventListener('DOMContentLoaded', () => {
    const observer = new MutationObserver(() => {
        document.querySelectorAll('.fi-select-panel:not([data-teleported])')
            .forEach(panel => {
                document.body.appendChild(panel);
                panel.dataset.teleported = 'true';
            });
    });

    observer.observe(document.body, { childList: true, subtree: true });
});
