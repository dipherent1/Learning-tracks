import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

// Tippy for tooltips
import tippy from 'tippy.js';
import 'tippy.js/dist/tippy.css';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue);

        // Directive-like helper: initialize tippy on elements with data-tippy-content
        app.config.globalProperties.$initTooltips = () => {
            // destroy existing tippys first by selecting elements with data-tippy and calling destroy if available
            document.querySelectorAll('[data-tippy-root]').forEach((node) => {
                if (node._tippy) node._tippy.destroy();
            });

            tippy('[data-tippy-content]', {
                allowHTML: false,
                theme: document.documentElement.classList.contains('dark') ? 'dark' : 'light',
                delay: [100, 50],
                placement: 'top',
            });
        };

        // Initialize dark mode class from localStorage
        const prefersDark = localStorage.getItem('supporthub:dark');
        if (prefersDark === '1') document.documentElement.classList.add('dark');

        const vm = app.mount(el);

        // Run tooltips on initial mount and after Inertia navigations
        vm.$initTooltips();
        document.addEventListener('inertia:navigate', () => vm.$initTooltips());

        return vm;
    },
    progress: {
        color: '#4B5563',
    },
});
