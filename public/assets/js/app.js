document.querySelectorAll('[data-modal-target]').forEach((button) => {
    button.addEventListener('click', () => {
        const modal = document.getElementById(button.dataset.modalTarget);

        if (modal) {
            modal.hidden = false;
        }
    });
});

document.querySelectorAll('[data-modal-close]').forEach((button) => {
    button.addEventListener('click', () => {
        const modal = button.closest('.modal');

        if (modal) {
            modal.hidden = true;
        }
    });
});

document.addEventListener('keydown', (event) => {
    if (event.key !== 'Escape') {
        return;
    }

    document.querySelectorAll('.modal:not([hidden])').forEach((modal) => {
        modal.hidden = true;
    });
});
