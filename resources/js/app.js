import './bootstrap';

import { createApp } from 'vue';
import { createPinia } from 'pinia';
import App from './components/App.vue';
import { useEmailStore } from './stores/email';

const app = createApp(App);
const pinia = createPinia();
app.use(pinia);

// Инициализация email-хранилища перед монтированием приложения
const emailStore = useEmailStore();
emailStore.init().finally(() => {
    app.mount('#app');
});
