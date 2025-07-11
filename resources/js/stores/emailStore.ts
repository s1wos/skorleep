import axios from "axios";
import { defineStore } from "pinia";

axios.defaults.withCredentials = true
axios.defaults.baseURL = '/api'

export const useEmailStore = defineStore('email', {
    state: () => ({
        email: 'example@mmail.com',
        messages: [] as any[],
        loading: false,
    }),

    actions: {
        async fetchCreateEmail() {
            this.loading = true
            try{
                const res = await axios.get('/email')
                this.email = res.data.email
            } finally {
                this.loading = false
            }
        },

        async recreateEmail() {
            this.loading = true
            try{
                const res = await axios.post('/emails')
                this.email = res.data.data.email,
                this.messages = []
                localStorage.setItem('tempEmail', this.email)
                await this.fetchMessages()
            } finally {
                this.loading = false
            }
        },

        async fetchMessages() {
            try {
                const res = await axios.get('/messages')
                this.messages = res.data
            } catch (e) {
                console.error('Fetch messages failed', e)
            }
        },
        loadMockMessages() {
            this.messages = [
                {
                    from: 'test@site.com',
                    subject: 'Test Email',
                    body_text: 'This is a mocked email. This is a mocked email. This is a mocked email. This is a mocked email. This is a mocked email. This is a mocked email. This is a mocked email.',
                    received_at: new Date().toISOString(),
                    attachments: [
                        {
                            filename: 'update.pdf',
                            url: 'https://via.placeholder.com/150',
                            mime: 'application/pdf',
                            size: 204800,
                        },
                        {
                            filename: 'update.pdf',
                            url: 'https://via.placeholder.com/150',
                            mime: 'application/pdf',
                            size: 204800,
                        },
                        {
                            filename: 'update.pdf',
                            url: 'https://via.placeholder.com/150',
                            mime: 'application/pdf',
                            size: 204800,
                        },
                        {
                            filename: 'update.pdf',
                            url: 'https://via.placeholder.com/150',
                            mime: 'application/pdf',
                            size: 204800,
                        },
                    ],
                },
                {
                    from: 'admin@example.com',
                    subject: 'Welcome to TempMail!',
                    body_text: 'This is your temporary email inbox. Enjoy.',
                    received_at: new Date().toISOString(),
                    attachments: [],
                },
            ]
        }
    }
})