document.addEventListener('DOMContentLoaded', function () {
    // Nasłuchuj na kliknięcia przycisków, które mają aktywować zakładkę
    const detailTriggers = document.querySelectorAll('[data-tab-target]');

    detailTriggers.forEach(trigger => {
        trigger.addEventListener('click', function (event) {
            // Upewnij się, że sekcja ze szczegółami jest rozwinięta
            const collapseTarget = document.querySelector(this.getAttribute('data-bs-target'));
            if (collapseTarget) {
                const bsCollapse = bootstrap.Collapse.getOrCreateInstance(collapseTarget);
                bsCollapse.show();
            }

            // Aktywuj odpowiednią zakładkę
            const tabTargetSelector = this.getAttribute('data-tab-target');
            if (tabTargetSelector) {
                const tabToActivate = document.querySelector(tabTargetSelector);
                if (tabToActivate) {
                    const tab = new bootstrap.Tab(tabToActivate);
                    tab.show();
                }
            }
        });
    });
});