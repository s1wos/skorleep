import { defineStore } from 'pinia';
import axios from 'axios';
import { useEmailStore } from './email';

export const useMessageStore = defineStore('messages', {
    state: () => ({
        messages: [],
        loading: false,
        channel: null,
    }),

    actions: {
        async fetchInitial() {
            this.loading = true;
            try {
                const { data } = await axios.get('/api/messages');
                this.messages = data;
            } finally {
                this.loading = false;
            }
        },

        startRealtime() {
            const emailStore = useEmailStore();
            const address = emailStore.email?.email;
            if (!address || this.channel) return;

            // начальная загрузка
            this.fetchInitial();

            this.channel = window.Echo.private(`emails.${address}`)
                .listen('NewMessageReceived', (e) => {
                    this.messages.unshift(e.message);
                });
        },

        stopRealtime() {
            if (this.channel) {
                this.channel.stopListening('NewMessageReceived');
                this.channel.unsubscribe();
                this.channel = null;
            }
            this.messages = [];
        },
    },
}); 