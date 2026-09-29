(() => {
    const button = document.getElementById('workspaceMenuToggle');
    const panel = document.getElementById('sidebar');
    if (button && panel) {
        const mobile = matchMedia('(max-width: 991px)');
        const backdrop = document.querySelector('.workspace-backdrop');
        const close = panel.querySelector('.workspace-menu-close');
        const key = 'synapsegov.sidebar.collapsed';
        let collapsed = false;
        try { collapsed = localStorage.getItem(key) === '1'; } catch (_) {}
        let open = !mobile.matches && !collapsed;
        const render = () => {
            document.body.classList.toggle('navigation-open', open);
            document.body.classList.toggle('navigation-closed', !open);
            panel.classList.toggle('show', open);
            panel.inert = !open;
            panel.setAttribute('aria-hidden', String(!open));
            button.setAttribute('aria-expanded', String(open));
            button.setAttribute('aria-label', open ? 'Tutup menu navigasi' : 'Buka menu navigasi');
            backdrop.hidden = !(mobile.matches && open);
        };
        const setOpen = (value, restoreFocus = false) => {
            open = value;
            if (!mobile.matches) {
                collapsed = !open;
                try { localStorage.setItem(key, collapsed ? '1' : '0'); } catch (_) {}
            }
            render();
            if (restoreFocus) button.focus();
            else if (mobile.matches && open) close.focus();
        };
        button.addEventListener('click', () => setOpen(!open));
        close.addEventListener('click', () => setOpen(false, true));
        backdrop.addEventListener('click', () => setOpen(false, true));
        document.addEventListener('keydown', event => {
            if (!open || !mobile.matches) return;
            if (event.key === 'Escape') { event.preventDefault(); setOpen(false, true); }
            if (event.key === 'Tab') {
                const controls = [...panel.querySelectorAll('a[href],button:not([disabled])')].filter(el => el.getClientRects().length);
                const first = controls[0], last = controls[controls.length - 1];
                if (event.shiftKey && document.activeElement === first) { event.preventDefault(); last.focus(); }
                else if (!event.shiftKey && document.activeElement === last) { event.preventDefault(); first.focus(); }
            }
        });
        panel.querySelectorAll('a[href]').forEach(link => link.addEventListener('click', () => {
            if (mobile.matches) setOpen(false);
        }));
        mobile.addEventListener('change', () => { open = !mobile.matches && !collapsed; render(); });
        render();
    }
    document.querySelectorAll('[data-site-menu]').forEach(toggle => {
        const menu = document.getElementById(toggle.getAttribute('aria-controls'));
        if (!menu) return;
        const update = open => {
            menu.classList.toggle('site-menu-open', open);
            toggle.setAttribute('aria-expanded', String(open));
            toggle.setAttribute('aria-label', open ? 'Tutup navigasi' : 'Buka navigasi');
        };
        toggle.addEventListener('click', () => update(toggle.getAttribute('aria-expanded') !== 'true'));
        document.addEventListener('keydown', e => { if (e.key === 'Escape') { update(false); toggle.focus(); } });
        menu.querySelectorAll('a[href]').forEach(link => link.addEventListener('click', () => update(false)));
    });
})();
