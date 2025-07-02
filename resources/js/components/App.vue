<template>
    <div class="container mx-auto py-10">
        <div v-if="emailStore.loading" class="text-gray-500">Генерация адреса...</div>
        <div v-else>
            <h1 class="text-2xl font-semibold mb-4">Ваш временный email:</h1>
            <div class="flex items-center gap-4 mb-6">
                <span class="text-lg font-mono bg-gray-100 px-3 py-1 rounded">{{ emailStore.email.email }}</span>
                <button @click="remove" class="text-red-600 hover:underline">Удалить</button>
            </div>

            <h2 class="text-xl font-medium mb-2">Входящие</h2>
            <div v-if="messageStore.loading" class="text-gray-500">Загрузка писем...</div>
            <div v-else>
                <p v-if="messageStore.messages.length === 0" class="text-gray-500">Нет писем</p>
                <ul v-else class="space-y-2">
                    <MessageItem v-for="msg in messageStore.messages"
                                 :key="msg.received_at + msg.subject"
                                 :message="msg"
                                 @open="openMessage" />
                </ul>
            </div>
        </div>
    </div>
</template>

<script setup>
import { onMounted, onBeforeUnmount, ref } from 'vue';
import { useEmailStore } from '../stores/email';
import { useMessageStore } from '../stores/messages';
import MessageItem from './MessageItem.vue';

const emailStore = useEmailStore();
const messageStore = useMessageStore();

const selected = ref(null);

function remove() {
  emailStore.remove();
}

function formatDate(val) {
  return new Date(val).toLocaleString('ru-RU');
}

function openMessage(msg) {
  selected.value = msg;
}
onMounted(() => {
  messageStore.startRealtime();
});

onBeforeUnmount(() => {
  messageStore.stopRealtime();
});
</script>
<style scoped>
.container {
  max-width: 700px;
}
</style>
