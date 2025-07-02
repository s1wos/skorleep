import { defineStore } from 'pinia';
import axios from 'axios';
import { useMessageStore } from './messages';

export const useEmailStore = defineStore('email', {
    state: () => ({
        email: null,
        loading: false,
    }),

    getters: {
        hasEmail: (state) => !!state.email,
    },

    actions: {
        async init() {
            if (this.loading) return;
            this.loading = true;
            try {
                // Пытаемся восстановить email из localStorage
                const stored = localStorage.getItem('email');
                if (stored) {
                    try {
                        const parsed = JSON.parse(stored);
                        if (parsed && typeof parsed.email === 'string') {
                            this.email = parsed;
                            // Запускаем polling при загрузке из localStorage
                            useMessageStore().startRealtime();
                            return;
                        }
                    } catch (_) {
                        // некорректный JSON — просто удаляем
                        localStorage.removeItem('email');
                    }
                }

                // Если не нашли — создаём/получаем через API (сервер сам создаст при отсутствии)
                try {
                    const { data } = await axios.get('/api/email');
                    this.email = data;
                    localStorage.setItem('email', JSON.stringify(data));

                    // Запускаем polling, если ещё не запущен
                    const msgStore = useMessageStore();
                    msgStore.startRealtime();
                } catch (error) {
                    if (error?.response?.status === 429) {
                        alert('Слишком частые запросы. Подождите перед созданием нового email.');
                    } else {
                        alert('Ошибка при создании email.');
                    }
                    return;
                }
            } finally {
                this.loading = false;
            }
        },

        async remove() {
            const msgStore = useMessageStore();

            if (!this.email) {
                await this.init();
                return;
            }

            // Подтверждение удаления
            const confirmed = window.confirm('Удалить текущий временный ящик и все письма?');
            if (!confirmed) {
                return;
            }

            try {
                await axios.delete('/api/email');
            } catch (error) {
                if (error?.response?.status === 403) {
                    alert('Нет доступа к удалению этого ящика.');
                } else {
                    alert('Ошибка при удалении ящика.');
                }
                return;
            }

            // Успешно удалено
            localStorage.removeItem('email');
            this.email = null;

            // Остановить polling и очистить письма
            msgStore.stopRealtime();
            msgStore.messages = [];

            // Создать новый ящик
            await this.init();
        },
    },
}); 