<template>
  <li @click="$emit('open', message)" class="border p-3 rounded bg-white shadow cursor-pointer hover:bg-gray-50">
    <div class="text-sm text-gray-600">От: {{ message.from }}</div>
    <div class="font-semibold truncate">{{ message.subject || 'Без темы' }}</div>
    <div class="text-xs text-gray-500">{{ formatDate(message.received_at) }}</div>
  </li>
</template>

<script setup>
defineProps({
    message: {
        type: Object,
        required: true,
    },
});
function formatDate(val) {
  return new Date(val).toLocaleString('ru-RU');
}
</script>
