import '../css/app.css';
import './bootstrap';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import IMask from 'imask';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

const maskDirective = {
    mounted(el, binding) {
        el._imask = IMask(el, { mask: binding.value });
    },
    updated(el, binding) {
        el._imask.updateOptions({ mask: binding.value });
    },
    unmounted(el) {
        el._imask.destroy();
    },
};

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .directive('mask', maskDirective)
            .use(ZiggyVue)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
