<template>
  <motion.div
    :initial="'hidden'"
    :while-in-view="'visible'"
    class="container w-full h-full py-10 px-4 sm:px-6 lg:px-8 flex flex-col lg:flex-row bg-[#F9FAFE] gap-6 lg:gap-8 justify-center overflow-hidden"
  >
    <!-- Left panel: Email controls -->
    <motion.div
      :variants="variants"
      :transition="transition"
      class="w-full lg:w-1/2 h-full lg:max-h-[50%] sm:max-h-[50%] flex-1 bg-transparent"
    >
      <!-- Email display -->
      <div class="w-full h-52 p-4 flex-1 bg-white rounded-xl">
        <input
          v-model="emailStore.email"
          class="w-full h-16 p-4 bg-[#F9FAFE] rounded-xl outline-none text-xl sm:text-2xl text-gray-500"
          readonly
        />
        <div class="flex flex-wrap gap-4 py-6 justify-between">
          <button
            @click="handleCopy"
            type="button"
            class="text-white cursor-pointer bg-indigo-400 hover:bg-indigo-500 focus:ring-4 focus:ring-indigo-300
              font-medium rounded-lg text-lg sm:text-2xl px-5 py-2.5 dark:hover:bg-indigo-600 focus:outline-none dark:focus:ring-indigo-800"
          >
            Копировать
          </button>
          <button
            @click="emailStore.recreateEmail"
            type="button"
            class="flex items-center gap-2 cursor-pointer font-medium text-lg sm:text-2xl px-5 py-2.5 bg-transparent"
          >
            <CgSpinner
              v-if="emailStore.loading"
              class="w-8 h-8 sm:w-12 sm:h-12 text-indigo-400 animate-spin"
            />
            <CgSpinner
              v-else
              class="w-8 h-8 sm:w-12 sm:h-12 text-indigo-400"
            />
            Обновить
          </button>
        </div>
      </div>

      <!-- Inbox messages -->
      <div class="w-full flex-1 mt-6">
        <div class="flex flex-row justify-between mt-4 p-1">
          <p class="font-medium text-2xl sm:text-3xl">Входящие</p>
          <button @click="isAscending = !isAscending" class="cursor-pointer">
            <CaArrowsHorizontal class="w-8 h-8 sm:w-10 sm:h-10 hover:text-gray-600" />
          </button>
        </div>

        <div
          v-if="Array.isArray(emailStore.messages) && emailStore.messages.length === 0"
          class="h-full flex text-base sm:text-xl text-gray-500 justify-center items-center"
        >
          У вас нет сообщений
        </div>

        <ul>
          <li
            v-for="msg in sortedMessages"
            :key="msg.subject + msg.received_at"
            @click="openMessage(msg)"
            class="w-full flex gap-2 items-start bg-transparent hover:bg-white border-b border-gray-200 p-4 rounded-lg cursor-pointer"
          >
            <HeFilledUiUserProfile class="w-16 h-16 sm:w-20 sm:h-20 text-indigo-400" />
            <div class="flex flex-col gap-1">
              <div class="text-lg sm:text-xl font-medium">{{ msg.subject }}</div>
              <div class="text-sm sm:text-lg text-gray-400">От: {{ msg.from }}</div>
            </div>
            <div class="ms-auto text-xs sm:text-lg text-gray-400">
              {{ new Date(msg.received_at).toLocaleString(undefined, { hour12: false }) }}
            </div>
          </li>
        </ul>
      </div>
    </motion.div>

    <!-- Right panel: Opened message -->
    <motion.div
      :variants="variants"
      :transition="transition"
      class="w-full lg:w-1/2 h-full flex-1 bg-white rounded-xl p-6 sm:p-8 box-border"
    >
      <div
        v-if="selectedMessage"
        class="flex flex-col items-start gap-4 h-full overflow-y-auto overflow-x-hidden"
      >
        <!-- Header -->
        <div class="flex border-b border-gray-200 flex-row w-full pb-2">
          <div class="flex gap-4 flex-col">
            <h2 class="text-2xl sm:text-3xl font-medium">{{ selectedMessage.subject }}</h2>
            <div class="text-lg sm:text-xl text-gray-500">От: {{ selectedMessage.from }}</div>
          </div>
          <div class="ms-auto text-sm sm:text-lg text-gray-500">
            {{ new Date(selectedMessage.received_at).toLocaleString(undefined, { hour12: false }) }}
          </div>
        </div>

        <!-- Message Body -->
        <div class="border-b border-gray-200 w-full py-2">
          <div class="text-base sm:text-lg">{{ selectedMessage.body_text }}</div>
        </div>

        <!-- Attachments -->
        <div class="w-full pt-2">
          <h4 class="text-base sm:text-lg font-medium">Вложения:</h4>
          <ul class="space-y-1 flex flex-col gap-3">
            <li
              v-for="file in selectedMessage.attachments"
              :key="file.url!"
              class="w-full flex items-center text-sm bg-[#F9FAFE] rounded-b-lg p-2"
            >
              <FlDocumentText class="w-6 h-6 sm:w-8 sm:h-8 text-indigo-400" />
              <a
                v-if="file.url"
                :href="file.url"
                :download="file.filename ?? 'attachment'"
                target="_blank"
                class="text-indigo-400 underline mx-3 text-sm sm:text-lg"
              >
                {{ file.filename ?? 'Unknown file' }}
              </a>
              <span v-if="file.size !== null" class="text-gray-400 text-sm sm:text-lg ms-auto">
                {{ (file.size / 1024).toFixed(1) }} KB
              </span>
            </li>
            <div v-if="selectedMessage && selectedMessage.attachments?.length === 0">
              В сообщение нет вложений
            </div>
          </ul>
        </div>
      </div>

      <div
        v-if="!selectedMessage"
        class="h-full flex text-xl sm:text-2xl text-gray-500 justify-center items-center"
      >
        Выберите сообщение
      </div>
    </motion.div>
  </motion.div>
</template>


<script setup lang="ts">
import {useEmailStore} from '../stores/emailStore'
import {computed, onMounted, ref} from 'vue'
import { CgSpinner } from '@kalimahapps/vue-icons';
import { CaArrowsHorizontal } from '@kalimahapps/vue-icons';
import { HeFilledUiUserProfile } from '@kalimahapps/vue-icons';
import { FlDocumentText } from '@kalimahapps/vue-icons';
import { motion, cubicBezier } from 'motion-v'
import { isAccessor } from 'typescript';

const transition = { duration: 1, ease: cubicBezier(0.25, 0.1, 0.25, 1) }

const variants = {
  hidden: {
    filter: 'blur(10px)',
    transform: 'translateY(15%)',
    opacity: 0
  },
  visible: {
    filter: 'blur(0)',
    transform: 'translateY(0)',
    opacity: 1
  }
}

const emailStore = useEmailStore()
const selectedMessage = ref<EmailMessage | null>(null)
const useMock = import.meta.env.VITE_USE_MOCK === 'false'
const isAscending = ref(false)

onMounted(() => {
    if (useMock) {
        emailStore.loadMockMessages()
    } else {
        const savedEmail = localStorage.getItem('tempEmail')
        if (savedEmail) {
            emailStore.email = savedEmail
            emailStore.fetchMessages()
        } else {
            emailStore.recreateEmail()
        }
    }
})

const sortedMessages = computed(() => {
    return Array.isArray(emailStore.messages) ? [...emailStore.messages].sort((a, b) => {
        const timeA = new Date(a.received_at).getTime();
        const timeB = new Date(b.received_at).getTime();
        return isAscending.value ? timeA - timeB : timeB - timeA;
    }) 
    : []
})

function handleCopy() {
    navigator.clipboard.writeText(emailStore.email)
}

function openMessage(msg: EmailMessage) {
    selectedMessage.value = msg;
}
</script>